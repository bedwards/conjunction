<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Conjunction\Entity\Conjunction;
use Conjunction\Repository\GameSessionRepositoryInterface;
use Conjunction\Repository\SentencePairRepositoryInterface;
use Conjunction\Service\ConjunctionChecker;

header('Content-Type: application/json; charset=utf-8');
$container = require __DIR__ . '/../config/container.php';
$action = $_GET['action'] ?? '';

function createSession($container): array
{
    $sessionRepo = $container->get(GameSessionRepositoryInterface::class);
    $session = $sessionRepo->create('');
    return ['session_token' => $session->getSessionToken()];
}

function getRandomPair($container): array
{
    $token = $_GET['token'] ?? throw new \Exception('Missing token');

    // Get difficulty from query parameter, default to 1 (easy)
    $difficulty = isset($_GET['difficulty']) ? (int)$_GET['difficulty'] : 1;

    // Validate difficulty range (1-3)
    if ($difficulty < 1 || $difficulty > 3) {
        $difficulty = 1;
    }

    $pairRepo = $container->get(SentencePairRepositoryInterface::class);
    $sessionRepo = $container->get(GameSessionRepositoryInterface::class);

    $session = $sessionRepo->findByToken($token);

    if ($session === null) {
        throw new \Exception('Session not found or token expired. Please create a new session.');
    }

    // Use the difficulty parameter instead of hardcoded 1
    $pair = $pairRepo->findRandomByDifficulty($difficulty);

    if ($pair === null) {
        throw new \Exception('No sentence pairs available at this difficulty level.');
    }

    return [
        'pair' => [
            'id' => $pair->getId(),
            'first_part' => $pair->getFirstPart(),
            'second_part' => $pair->getSecondPart(),
        ],
        'session' => [
            'total_questions' => $session->getTotalQuestions(),
            'correct_answers' => $session->getCorrectAnswers(),
            'accuracy' => round($session->getAccuracy() * 100, 2),
        ],
    ];
}

function check($container): array
{
    $input = json_decode(file_get_contents('php://input'), true);
    $pairRepo = $container->get(SentencePairRepositoryInterface::class);
    $sessionRepo = $container->get(GameSessionRepositoryInterface::class);

    if (!isset($input['pair_id'])) {
        throw new \Exception('Missing pair ID for check action.');
    }

    $pair = $pairRepo->find($input['pair_id']);

    if ($pair === null) {
        throw new \Exception('Sentence pair not found.');
    }

    if (!isset($input['choice'], $input['session_token'], $input['response_time_ms'])) {
        throw new \Exception('Missing required check parameters.');
    }

    $checker = $container->get(ConjunctionChecker::class);

    $verdict = $checker->check(
        $pair,
        Conjunction::from($input['choice']),
        $input['session_token'],
        $input['response_time_ms']
    );

    $session = $sessionRepo->findByToken($input['session_token']);

    if ($session === null) {
        throw new \Exception('Session not found after recording answer.');
    }

    return [
        'verdict' => $verdict->toArray(),
        'session' => [
            'total_questions' => $session->getTotalQuestions(),
            'correct_answers' => $session->getCorrectAnswers(),
            'accuracy' => round($session->getAccuracy() * 100, 2),
        ],
    ];
}

try {
    switch ($action) {
        case 'create_session':
            $resp = createSession($container);
            break;
        case 'next':
            $resp = getRandomPair($container);
            break;
        case 'check':
            $resp = check($container);
            break;
        default:
            throw new \Exception('Invalid action');
    }
    echo json_encode($resp);
} catch (\Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
