<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
 
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { exit(0); }

// Inclusion de nos modèles
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


            $result  = ask_llm($messages);
            $content = trim($result['choices'][0]['message']['content'] ?? '');
            $usage   = $result['usage'] ?? null;

            $decoded = json_decode($content, true);
            if (json_last_error() === JSON_ERROR_NONE && isset($decoded['action']) && $decoded['action'] === 'create_news') {
                $title   = $decoded['title']   ?? '';
                $article = $decoded['article'] ?? '';
                
                $newId = insert_news($title, $article, $lastby);
                
                echo json_encode([
                    'success'   => true,
                    'reply'     => "✅ News **\"$title\"** créée avec succès (ID #$newId) !",
                    'db_action' => 'create_news',
                    'new_id'    => $newId,
                    'usage'     => $usage
                ]);
                break;
            }

            echo json_encode([
                'success' => true,
                'reply'   => $content,
                'usage'   => $usage
            ]);
            break;

        default:
            throw new Exception('Action inconnue.');
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

?>