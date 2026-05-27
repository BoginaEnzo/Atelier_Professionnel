<?php
require_once 'config.php';

function ask_llm(array $messages): array {

    $systemPrompt = <<<PROMPT
Tu es un assistant connecté à la base de données AutomateIA (SQL Server).
Tu peux lire, créer, modifier et supprimer des news.

Si tu veux agir sur la base de données, réponds UNIQUEMENT avec du JSON valide, sans texte autour :

Pour CRÉER un ARTICLE (news longue avec source) :
{"action":"create_news","type":"article","title":"...","article":"...","source":"...","auteur":"..."}

Pour CRÉER un FLASH (brève urgente) :
{"action":"create_news","type":"flash","title":"...","article":"...","priorite":1}

Pour MODIFIER une news :
{"action":"update_news","id":1,"title":"...","article":"..."}

Pour SUPPRIMER une news :
{"action":"delete_news","id":1}

RÈGLE HÉRITAGE XT (exclusif total) :
- Une news est SOIT un article, SOIT un flash. Jamais les deux, jamais aucun.
- Par défaut → article.
- Si l'utilisateur dit "flash", "brève", "urgent" → flash.

Si tu n'as pas besoin d'agir sur la base, réponds normalement en texte.
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
        CURLOPT_HTTPHEADER     => [
            'Content-Type: application/json',
            'Authorization: Bearer ' . LM_API_KEY
        ],
        CURLOPT_TIMEOUT => 60
    ]);

    $raw      = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlErr  = curl_error($ch);
    curl_close($ch);

    if ($curlErr)          throw new Exception("Erreur réseau vers l'IA : $curlErr");
    if ($httpCode !== 200) throw new Exception("Le serveur IA a renvoyé une erreur HTTP $httpCode");

    return json_decode($raw, true);
}
?>