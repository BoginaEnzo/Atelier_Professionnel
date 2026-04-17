<?php
// On importe le fichier de configuration (pour récupérer l'URL du serveur IA et le modèle)
require_once __DIR__ . '/../config.php';

/**
 * Envoie la conversation au modèle IA (LM Studio) et récupère sa réponse
 */
function ask_llm($messages) {
    
    // 1. LE PROMPT SYSTÈME (L'antisèche de l'IA)
    // C'est ici qu'on "programme" le comportement de l'IA. On lui donne son rôle et 
    // ses contraintes. La syntaxe <<<PROMPT permet d'écrire un texte sur plusieurs lignes.
    $systemPrompt = <<<PROMPT
        Tu es un assistant intelligent connecté à la base de données AutomateIA (SQL Server).

        Si l'utilisateur te demande de CRÉER une news, réponds UNIQUEMENT avec ce format JSON strict :
        {"action":"create_news","title":"TITRE","article":"CONTENU"}

        Si l'utilisateur te demande de MODIFIER une news existante (en te donnant son ID), renvoie UNIQUEMENT le JSON avec les champs que tu souhaites changer. Laisse tomber les autres.
        Exemple pour changer juste le titre : {"action":"update_news","id":12,"title":"Nouveau titre"}
        Exemple pour changer juste l'article : {"action":"update_news","id":12,"article":"Nouveau texte"}

        Si l'utilisateur veut juste discuter, réponds normalement en français de façon claire et concise.
    PROMPT;
 
    // 2. LE PAQUET À ENVOYER (Le payload)
    // On prépare les données selon les règles imposées par OpenAI/LM Studio.
    $payload = [
        'model'       => LM_MODEL, // Ex: qwen2.5-coder-7b-instruct
        // array_merge combine deux tableaux : On place notre antisèche (System) TOUT EN HAUT,
        // puis on rajoute tout l'historique des messages ($messages) en dessous.
        'messages'    => array_merge([['role' => 'system', 'content' => $systemPrompt]], $messages),
        'temperature' => 0.7, // 0 = Robot très strict. 1 = IA très créative. 0.7 est un bon équilibre.
        'max_tokens'  => 500, // La longueur maximale de la réponse autorisée.
        'stream'      => false // On veut toute la réponse d'un coup (pas mot par mot).
    ];

    // 3. LA LIVRAISON (Via cURL)
    // cURL est une librairie PHP qui simule un navigateur web invisible pour interroger des API.
    $ch = curl_init(LM_API_URL);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true, // On veut que cURL nous renvoie le résultat dans une variable
        CURLOPT_POST           => true, // On envoie des données, c'est donc une méthode POST
        CURLOPT_POSTFIELDS     => json_encode($payload), // On convertit notre tableau PHP en texte JSON
        CURLOPT_HTTPHEADER     => [ // On prévient le serveur qu'on lui envoie du JSON
            'Content-Type: application/json', 
            'Authorization: Bearer ' . LM_API_KEY
        ],
        CURLOPT_TIMEOUT        => 60 // On attend maximum 60 secondes. Si l'IA est trop lente, ça coupe.
    ]);

    // On clique sur le bouton "Envoyer" !
    $raw = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE); // On vérifie le code de retour (200 = OK)
    $curlErr = curl_error($ch); // S'il y a une erreur réseau, on la capture.
    curl_close($ch);

    // 4. GESTION DES ERREURS
    // Si cURL a planté (ex: LM Studio est éteint), on déclenche une alarme d'erreur (Exception).
    if ($curlErr) throw new Exception("Erreur réseau vers l'IA : $curlErr");
    if ($httpCode !== 200) throw new Exception("Le serveur IA a renvoyé une erreur HTTP $httpCode");

    // 5. LE RETOUR
    // L'API nous a renvoyé du JSON brut. On le convertit en tableau PHP lisible et on le renvoie.
    return json_decode($raw, true);
}
?>