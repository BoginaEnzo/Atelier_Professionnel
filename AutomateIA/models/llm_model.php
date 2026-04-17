<?php
require_once __DIR__ . '/../config.php';

function ask_llm($messages) {
    $systemPrompt = <<<PROMPT
Tu es un assistant intelligent connecté à la base de données AutomateIA (SQL Server).
Si l'utilisateur te demande de CRÉER une news, réponds UNIQUEMENT avec ce JSON exact :
{"action":"create_news","title":"TITRE ICI","article":"CONTENU ICI"}
Sinon, réponds normalement en français de façon claire et concise.
PROMPT;

    $payload = [
        'model'       => LM_MODEL,
        'messages'    => array_merge([['role' => 'system', 'content' => $systemPrompt]], $messages),
        'temperature' => 0.7,
        'max_tokens'  => 500,
        'stream'      => false
    ];

    $ch = curl_init(LM_API_URL);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => json_encode($payload),
        CURLOPT_HTTPHEADER     => ['Content-Type: application/json', 'Authorization: Bearer ' . LM_API_KEY],
        CURLOPT_TIMEOUT        => 60
    ]);

    $raw = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlErr = curl_error($ch);
    curl_close($ch);

    if ($curlErr) throw new Exception("Erreur cURL : $curlErr");
    if ($httpCode !== 200) throw new Exception("Erreur LM Studio HTTP $httpCode");

    return json_decode($raw, true);
}

?>
