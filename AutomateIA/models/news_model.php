<?php
// On importe le fichier de connexion à la base de données.
// 'require_once' s'assure qu'on ne l'importe qu'une seule fois pour éviter les erreurs.
require_once 'db_model.php';
 
/**
 * LECTURE : Récupère la liste des news
 */
function get_all_news($limit = 50) {
    // 1. On appelle notre fonction getDB() (définie dans db_model.php) pour ouvrir la connexion
    $db = getDB();
    
    // 2. On prépare notre phrase SQL. 'TOP $limit' permet de ne pas surcharger 
    // l'application si on a 10 000 articles. On trie du plus récent au plus ancien (DESC).
    $stmt = $db->query("SELECT TOP $limit new_id, new_title, new_article, new_date, new_lastby FROM [dbo].[News] ORDER BY new_date DESC");
    
    // 3. fetchAll() prend tous les résultats trouvés par SQL et les transforme en un tableau PHP.
    return $stmt->fetchAll();
}

/**
 * LECTURE : Récupère l'historique des logs
 */
function get_all_logs($limit = 100) {
    $db = getDB();
    $stmt = $db->query("SELECT TOP $limit logn_id, logn_idnews, logn_title, logn_date, logn_lastby, logn_typeev FROM [dbo].[LogsNews] ORDER BY logn_id DESC");
    return $stmt->fetchAll();
}

/**
 * CRÉATION : Insère une nouvelle news
 */
function insert_news($title, $article, $lastby) {
    $db = getDB();
    
    // SÉCURITÉ : On utilise 'prepare' avec des points d'interrogation (?).
    // C'est crucial pour éviter les "Injections SQL" (le fait qu'un pirate tape du code SQL dans le titre).
    $stmt = $db->prepare("INSERT INTO [dbo].[News] (new_title, new_article, new_date, new_lastby) VALUES (?, ?, GETDATE(), ?)");
    
    // On exécute la requête en remplaçant les (?) par les vraies variables, dans le même ordre.
    $stmt->execute([$title, $article, $lastby]);
    
    // On demande à la base de données : "Quel est l'ID (le numéro) de la ligne que tu viens de créer ?"
    return $db->lastInsertId();
}

/**
 * SUPPRESSION : Supprime une news
 */
function remove_news($id, $lastby) {
    $db = getDB();
    
    // Astuce : Avant de supprimer, on change l'auteur de la news.
    // Pourquoi ? Si ta base de données a un "Trigger" (un automatisme) qui enregistre les suppressions, 
    // il saura ainsi QUI a demandé la suppression.
    $db->prepare("UPDATE [dbo].[News] SET new_lastby=? WHERE new_id=?")->execute([$lastby, $id]);
    
    // Ensuite, on supprime vraiment la ligne.
    $db->prepare("DELETE FROM [dbo].[News] WHERE new_id=?")->execute([$id]);
}

/**
 * MODIFICATION : Met à jour une news (Partielle ou Totale)
 */
function update_news($id, $title, $article, $lastby) {
    $db = getDB();
    
    /* --- ÉTAPE 1 : ALLER CHERCHER L'ANCIENNE NEWS --- */
    // On veut éviter d'écraser un texte par du vide si l'IA n'a voulu changer que le titre.
    $stmt = $db->prepare("SELECT new_title, new_article FROM [dbo].[News] WHERE new_id = ?");
    $stmt->execute([$id]);
    $old = $stmt->fetch(); // On lit le résultat

    // Si $old est vide, c'est que l'ID n'existe pas dans la base de données. On stoppe tout !
    if (!$old) {
        throw new Exception("Impossible de modifier : la news #$id n'existe pas.");
    }

    /* --- ÉTAPE 2 : LE MÉLANGE (FUSION) --- */
    // On utilise une "condition ternaire" (une condition if/else écrite sur une ligne) :
    // (Condition) ? (Si vrai) : (Si faux)
    // Exemple : Le titre est-il non-vide ? SI OUI, on prend le nouveau titre. SI NON, on garde l'ancien titre.
    $final_title   = (!empty($title))   ? $title   : $old['new_title'];
    $final_article = (!empty($article)) ? $article : $old['new_article'];

    /* --- ÉTAPE 3 : LA SAUVEGARDE FINALE --- */
    // On envoie le mélange final à la base de données. On actualise aussi la date avec GETDATE().
    $updateStmt = $db->prepare("UPDATE [dbo].[News] SET new_title=?, new_article=?, new_date=GETDATE(), new_lastby=? WHERE new_id=?");
    $updateStmt->execute([$final_title, $final_article, $lastby, $id]);
}
?>