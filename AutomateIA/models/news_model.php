<?php
require_once 'db_model.php';
 
/**
 * Récupère les dernières news
 */
function get_all_news($limit = 50) {
    $db = getDB();
    $stmt = $db->query("SELECT TOP $limit new_id, new_title, new_article, new_date, new_lastby FROM [dbo].[News] ORDER BY new_date DESC");
    return $stmt->fetchAll();
}

/**
 * Récupère l'historique des logs
 */
function get_all_logs($limit = 100) {
    $db = getDB();
    $stmt = $db->query("SELECT TOP $limit logn_id, logn_idnews, logn_title, logn_date, logn_lastby, logn_typeev FROM [dbo].[LogsNews] ORDER BY logn_id DESC");
    return $stmt->fetchAll();
}

/**
 * Insère une nouvelle news
 */
function insert_news($title, $article, $lastby) {
    $db = getDB();
    $stmt = $db->prepare("INSERT INTO [dbo].[News] (new_title, new_article, new_date, new_lastby) VALUES (?, ?, GETDATE(), ?)");
    $stmt->execute([$title, $article, $lastby]);
    return $db->lastInsertId();
}

/**
 * Supprime une news
 */
function remove_news($id, $lastby) {
    $db = getDB();
    // On met à jour l'auteur pour que le trigger de log puisse l'enregistrer
    $db->prepare("UPDATE [dbo].[News] SET new_lastby=? WHERE new_id=?")->execute([$lastby, $id]);
    $db->prepare("DELETE FROM [dbo].[News] WHERE new_id=?")->execute([$id]);
}

/**
 * MODIFICATION : Met à jour une news (partiellement ou totalement)
 */
function update_news($id, $title, $article, $lastby) {
    $db = getDB();
    
    // 1. On récupère d'abord les données actuelles en base
    $stmt = $db->prepare("SELECT new_title, new_article FROM [dbo].[News] WHERE new_id = ?");
    $stmt->execute([$id]);
    $old = $stmt->fetch();

    if (!$old) {
        throw new Exception("La news #$id n'existe pas.");
    }

    // 2. Fusion des données : si la nouvelle valeur est vide, on garde l'ancienne
    $final_title   = (!empty($title))   ? $title   : $old['new_title'];
    $final_article = (!empty($article)) ? $article : $old['new_article'];

    // 3. Exécution de la mise à jour
    $updateStmt = $db->prepare("UPDATE [dbo].[News] SET new_title=?, new_article=?, new_date=GETDATE(), new_lastby=? WHERE new_id=?");
    $updateStmt->execute([$final_title, $final_article, $lastby, $id]);
}
?>