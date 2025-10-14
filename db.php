<?php

// 1. BOOTSTRAP: Load dependencies and the configured service container
require_once __DIR__ . '/vendor/autoload.php';
// The container holds your configured database connection
$container = require __DIR__ . '/config/container.php';

use Conjunction\Repository\GameSessionRepositoryInterface;

// 2. SETUP: Get the Game Session Repository
$sessionRepo = $container->get(GameSessionRepositoryInterface::class);

echo "--- Session Sanity Check: Create and Retrieve ---\n";

// --- Operation 1: Create a NEW Session ---
echo "STEP 1: Creating new session...\n";
$session = $sessionRepo->create('');
$token = $session->getSessionToken();

echo "   -> Session created with token: " . $token . "\n";

// --- Operation 2: Find the NEW Session by its Token ---
echo "STEP 2: Attempting to find new session by token...\n";

$foundSession = $sessionRepo->findByToken($token);

// 3. REPORT: Check the result
if ($foundSession) {
    echo "\n[ SUCCESS ] Session successfully created AND retrieved immediately.\n";
    echo "   -> Found token: " . $foundSession->getSessionToken() . "\n";
} else {
    echo "\n[ FAILURE ] Session created, but NOT found by token.\n";
    echo "This means the database write/read path is still failing.\n";
}

echo "--- Test Complete ---\n";
