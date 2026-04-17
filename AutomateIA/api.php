<?php
// 1. LES EN-TÊTES (Headers)
// On prévient le navigateur Javascript qui nous écoute que la réponse sera TOUJOURS du JSON.
header('Content-Type: application/json');
// On autorise tout le monde (*) à interroger cette API (très utile en développement local)
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
 
// Si le navigateur fait une pré-requête de sécurité (OPTIONS), on coupe court et on dit OK.
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { exit(0); }

// On importe nos "boîtes à outils" (Modèles)
require_once 'models/news_model.php';
require_once 'models/llm_model.php';

// On regarde quelle action est inscrite dans l'URL (ex: api.php?action=chat)
// L'opérateur '??' signifie "S'il n'y a pas d'action, on met une chaîne vide".
$action = $_GET['action'] ?? '';

// Le bloc "try/catch" est un filet de sécurité. Si n'importe quel code plante 
// (ex: base de données éteinte), ça "saute" directement dans le catch pour renvoyer une erreur propre.
try {
    
    // Le "switch" agit comme un grand aiguillage de train.
    switch ($action) {
     
        // Si le Javascript demande "?action=news" -> On lui renvoie la liste des news
        case 'news':
            echo json_encode(['success' => true, 'data' => get_all_news()]);
            break;

        // Si le Javascript demande "?action=logs" -> On lui renvoie l'historique
        case 'logs':
            echo json_encode(['success' => true, 'data' => get_all_logs()]);
            break;

        // CRÉATION MANUELLE (Via le bouton + du site)
        case 'create_news':
            // Pour lire les données envoyées par Javascript en méthode POST, on utilise file_get_contents('php://input')
            $body    = json_decode(file_get_contents('php://input'), true);
            $title   = trim($body['title']   ?? '');
            $article = trim($body['article'] ?? '');
            $lastby  = trim($body['lastby']  ?? 'CHAT');
            
            // Si le titre ou l'article sont vides, on jette une Exception (erreur volontaire).
            if (!$title || !$article) throw new Exception('Titre et article requis.');
            
            $id = insert_news($title, $article, $lastby);
            echo json_encode(['success' => true, 'id' => $id, 'message' => "News #$id créée."]);
            break;

        // MODIFICATION MANUELLE (Si on l'ajoute plus tard sur le site)
        case 'update_news':
            $body    = json_decode(file_get_contents('php://input'), true);
            $id      = (int)($body['id']     ?? 0);
            $title   = trim($body['title']   ?? '');
            $article = trim($body['article'] ?? '');
            $lastby  = trim($body['lastby']  ?? 'CHAT');
            
            if (!$id) throw new Exception('ID requis pour la modification.');
            
            update_news($id, $title, $article, $lastby);
            echo json_encode(['success' => true, 'message' => "News #$id modifiée."]);
            break;

        // SUPPRESSION (Via la petite croix sur le site)
        case 'delete_news':
            $body   = json_decode(file_get_contents('php://input'), true);
            $id     = (int)($body['id']    ?? 0);
            $lastby = trim($body['lastby'] ?? 'JFE');
            
            if (!$id) throw new Exception('ID manquant.');
            
            remove_news($id, $lastby);
            echo json_encode(['success' => true, 'message' => "News #$id supprimée."]);
            break;

        // LE CŒUR DE L'IA : L'ACTION CHAT
        case 'chat':
            // 1. On lit ce que l'utilisateur a tapé dans la barre de chat
            $body     = json_decode(file_get_contents('php://input'), true);
            $messages = $body['messages'] ?? [];
            $lastby   = trim($body['lastby'] ?? 'CHAT');
            
            if (empty($messages)) throw new Exception('Messages manquants.');

            // 2. On envoie tout l'historique à LM Studio et on récupère sa réponse.
            $result  = ask_llm($messages);
            
            // On extrait le texte précis généré par l'IA dans l'arborescence complexe du retour API
            $content = trim($result['choices'][0]['message']['content'] ?? '');
            $usage   = $result['usage'] ?? null; // On récupère aussi les tokens utilisés

            // 3. LE DÉTECTEUR D'ACTIONS CACHÉES
            // On essaie de convertir la réponse de l'IA en tableau JSON.
            $decoded = json_decode($content, true);
            
            // json_last_error() === JSON_ERROR_NONE vérifie si l'IA a bien répondu en format JSON valide.
            // S'il y a bien la clé 'action', c'est que l'IA veut agir sur la base !
            if (json_last_error() === JSON_ERROR_NONE && isset($decoded['action'])) {
                
                /* ---- SI L'IA VEUT CRÉER UNE NEWS ---- */
                if ($decoded['action'] === 'create_news') {
                    $newId = insert_news($decoded['title'] ?? '', $decoded['article'] ?? '', $lastby);
                    
                    // On répond au Javascript en lui glissant le flag 'db_action' => 'create_news'.
                    // Le Javascript verra ce flag, et saura qu'il doit rafraîchir la liste de droite !
                    echo json_encode([
                        'success' => true,
                        'reply' => "✅ L'IA a créé la news (ID #$newId) !",
                        'db_action' => 'create_news', 
                        'usage' => $usage
                    ]);
                    break; // On coupe le script ici pour ne pas envoyer la réponse de base ci-dessous.
                }

                /* ---- SI L'IA VEUT MODIFIER UNE NEWS ---- */
                if ($decoded['action'] === 'update_news') {
                    $id = (int)($decoded['id'] ?? 0); // On récupère l'ID ciblé par l'IA
                    if ($id > 0) {
                        // On appelle notre super fonction de mise à jour partielle. 
                        // Si le titre ou l'article n'ont pas été donnés par l'IA, ça envoie 'null'.
                        update_news($id, $decoded['title'] ?? null, $decoded['article'] ?? null, $lastby);
                        
                        echo json_encode([
                            'success' => true,
                            'reply' => "✏️ L'IA a mis à jour la news #$id !",
                            'db_action' => 'create_news', // On triche un peu en gardant 'create_news' pour forcer le rafraichissement visuel du JS.
                            'usage' => $usage
                        ]);
                        break;
                    }
                }
            }

            // 4. LA RÉPONSE NORMALE
            // Si la réponse de l'IA n'était pas du JSON (ex: "Bonjour, comment puis-je vous aider ?"), 
            // le code arrive ici et renvoie simplement le texte à afficher dans la bulle de chat.
            echo json_encode(['success' => true, 'reply' => $content, 'usage' => $usage]);
            break;

        // Si l'action demandée dans l'URL n'existe pas dans ce switch.
        default:
            throw new Exception("L'action demandée est inconnue.");
    }

// LE FILET DE SÉCURITÉ
} catch (Exception $e) {
    // Si n'importe quel bloc 'throw new Exception' est déclenché, on atterrit ici.
    // On renvoie une belle erreur formatée en JSON au Javascript pour qu'il l'affiche en rouge.
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>