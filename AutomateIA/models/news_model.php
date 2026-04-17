<?php
require_once 'db_model.php';
 
function get_all_news($limit = 50) {
    $db = getDB();
    $stmt = $db->query("SELECT TOP $limit new_id, new_title, new_article, new_date, new_lastby FROM [dbo].[News] ORDER BY new_date DESC");
    return $stmt->fetchAll();
}

function get_all_logs($limit = 100) {
    $db = getDB();
    $stmt = $db->query("SELECT TOP $limit logn_id, logn_idnews, logn_title, logn_date, logn_lastby, logn_typeev FROM [dbo].[LogsNews] ORDER BY logn_id DESC");
    return $stmt->fetchAll();
}

function insert_news($title, $article, $lastby) {
    $db = getDB();
    $stmt = $db->prepare("INSERT INTO [dbo].[News] (new_title, new_article, new_date, new_lastby) VALUES (?, ?, GETDATE(), ?)");
    $stmt->execute([$title, $article, $lastby]);
    return $db->lastInsertId();
}

function remove_news($id, $lastby) {
    $db = getDB();
    // On met à jour l'auteur pour les logs (si tu as un trigger)
    $db->prepare("UPDATE [dbo].[News] SET new_lastby=? WHERE new_id=?")->execute([$lastby, $id]);
    $db->prepare("DELETE FROM [dbo].[News] WHERE new_id=?")->execute([$id]);
}

?>