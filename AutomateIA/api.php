<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
 
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { exit(0); }

require_once 'models/news_model.php';
require_once 'models/llm_model.php';

$action = $_GET['action'] ?? '';

try {
    switch ($action) {
     
        case 'news':
            echo json_encode(['success' => true, 'data' => get_all_news()]);
            break;

        case 'logs':
            echo json_encode(['success' => true, 'data' => get_all_logs()]);
            break;

        case 'create_news':
            $body    = json_decode(file_get_contents('php://input'), true);
            $title   = trim($body['title']   ?? '');
            $article = trim($body['article'] ?? '');
            $lastby  = trim($body['lastby']  ?? 'CHAT');
            if (!$title || !$article) throw new Exception('Titre et article requis.');
            $id = insert_news($title, $article, $lastby);
            echo json_encode(['success' => true, 'id' => $id, 'message' => "News #$id créée."]);
            break;

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

        case 'delete_news':
            $body   = json_decode(file_get_contents('php://input'), true);
            $id     = (int)($body['id']    ?? 0);
            $lastby = trim($body['lastby'] ?? 'JFE');
            if (!$id) throw new Exception('ID manquant.');
            remove_news($id, $lastby);
            echo json_encode(['success' => true, 'message' => "News #$id supprimée."]);
            break;

        case 'chat':
            $body     = json_decode(file_get_contents('php://input'), true);
            $messages = $body['messages'] ?? [];
            $lastby   = trim($body['lastby'] ?? 'CHAT');
            if (empty($messages)) throw new Exception('Messages manquants.');

            // Appel au LLM
            $result  = ask_llm($messages);
            $content = trim($result['choices'][0]['message']['content'] ?? '');
            $usage   = $result['usage'] ?? null;

            // Analyse de la réponse (Détection d'action JSON)
            $decoded = json_decode($content, true);
            if (json_last_error() === JSON_ERROR_NONE && isset($decoded['action'])) {
                
                // CAS 1 : CREATION
                if ($decoded['action'] === 'create_news') {
                    $newId = insert_news($decoded['title'] ?? '', $decoded['article'] ?? '', $lastby);
                    echo json_encode([
                        'success' => true,
                        'reply' => "✅ News créée avec succès (ID #$newId) !",
                        'db_action' => 'create_news',
                        'usage' => $usage
                    ]);
                    break;
                }

                // CAS 2 : MODIFICATION (PARTIELLE OU TOTALE)
                if ($decoded['action'] === 'update_news') {
                    $id = (int)($decoded['id'] ?? 0);
                    if ($id > 0) {
                        update_news($id, $decoded['title'] ?? null, $decoded['article'] ?? null, $lastby);
                        echo json_encode([
                            'success' => true,
                            'reply' => "✏️ News #$id mise à jour !",
                            'db_action' => 'create_news', // On force le rafraîchissement des listes côté client
                            'usage' => $usage
                        ]);
                        break;
                    }
                }
            }

            // Réponse textuelle simple
            echo json_encode(['success' => true, 'reply' => $content, 'usage' => $usage]);
            break;

        default:
            throw new Exception('Action inconnue.');
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>