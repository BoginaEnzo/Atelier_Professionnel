<?php
require_once 'config.php';
require_once 'db_model.php';

function get_all_news($limit = 50) {
    $db   = getDB();
    $stmt = $db->query("
        SELECT TOP $limit
            n.new_id, n.new_title, n.new_article, n.new_date, n.new_lastby, n.new_type,
            a.art_source, a.art_auteur, a.art_nb_mots,
            f.flash_priorite, f.flash_expire_date
        FROM [dbo].[News] n
        LEFT JOIN [dbo].[NewsArticle] a ON a.art_id   = n.new_id
        LEFT JOIN [dbo].[NewsFlash]   f ON f.flash_id = n.new_id
        ORDER BY n.new_date DESC
    ");
    return $stmt->fetchAll();
}

function get_all_logs($limit = 100) {
    $db   = getDB();
    $stmt = $db->query("
        SELECT TOP $limit logn_id, logn_idnews, logn_title, logn_date, logn_lastby, logn_typeev
        FROM [dbo].[LogsNews]
        ORDER BY logn_id DESC
    ");
    return $stmt->fetchAll();
}

// ✅ MODIFIÉ : $type ajouté
function insert_news($title, $article, $lastby, $type = 'article') {
    $db   = getDB();
    $stmt = $db->prepare("
        INSERT INTO [dbo].[News] (new_title, new_article, new_date, new_lastby, new_type)
        VALUES (?, ?, GETDATE(), ?, ?)
    ");
    $stmt->execute([$title, $article, $lastby, $type]);
    return $db->lastInsertId();
}

// ✅ NOUVEAU : table fille article
function insert_news_article(int $news_id, string $source, string $auteur, int $nb_mots): void {
    $db   = getDB();
    $stmt = $db->prepare("
        INSERT INTO [dbo].[NewsArticle] (art_id, art_source, art_auteur, art_nb_mots)
        VALUES (?, ?, ?, ?)
    ");
    $stmt->execute([$news_id, $source, $auteur, $nb_mots]);
}

// ✅ NOUVEAU : table fille flash
function insert_news_flash(int $news_id, int $priorite = 1, ?string $expire_date = null): void {
    $db   = getDB();
    $stmt = $db->prepare("
        INSERT INTO [dbo].[NewsFlash] (flash_id, flash_priorite, flash_expire_date)
        VALUES (?, ?, ?)
    ");
    $stmt->execute([$news_id, $priorite, $expire_date]);
}

// ✅ MODIFIÉ : supprime les tables filles avant la mère (contrainte FK)
function remove_news($id, $lastby) {
    $db = getDB();
    $db->prepare("UPDATE [dbo].[News] SET new_lastby=? WHERE new_id=?")->execute([$lastby, $id]);
    $db->prepare("DELETE FROM [dbo].[NewsArticle] WHERE art_id=?")->execute([$id]);
    $db->prepare("DELETE FROM [dbo].[NewsFlash]   WHERE flash_id=?")->execute([$id]);
    $db->prepare("DELETE FROM [dbo].[News] WHERE new_id=?")->execute([$id]);
}

function update_news($id, $title, $article, $lastby) {
    $db   = getDB();
    $stmt = $db->prepare("SELECT new_title, new_article FROM [dbo].[News] WHERE new_id = ?");
    $stmt->execute([$id]);
    $old = $stmt->fetch();
    if (!$old) throw new Exception("Impossible de modifier : la news #$id n'existe pas.");
    $final_title   = (!empty($title))   ? $title   : $old['new_title'];
    $final_article = (!empty($article)) ? $article : $old['new_article'];
    $db->prepare("UPDATE [dbo].[News] SET new_title=?, new_article=?, new_date=GETDATE(), new_lastby=? WHERE new_id=?")
       ->execute([$final_title, $final_article, $lastby, $id]);
}
?>