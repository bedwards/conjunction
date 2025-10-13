<?php

require_once __DIR__ . '/../vendor/autoload.php';

use ConjunctionJunction\Entity\Conjunction;

header('Content-Type: application/json');

$container = require __DIR__ . '/../config/container.php';

$action = $_GET['action'] ?? '';

try {
    switch ($action) {
        case 'create_session':
            // TODO: Use SessionManager to create session
            // Return: {session_token: "..."}
            break;

        case 'next':
            // TODO: Use SentencePairFactory to get random pair
            // Return: {pair: {...}, session: {...}}
            break;

        case 'check':
            // TODO: Parse JSON body, use ConjunctionChecker
            // Return: {verdict: {...}, session: {...}}
            break;

        default:
            throw new \Exception('Invalid action');
    }
} catch (\Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
