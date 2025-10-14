<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Conjunction\Entity\Conjunction;
use Conjunction\Repository\GameSessionRepositoryInterface;
use Conjunction\Repository\SentencePairRepositoryInterface;
use Conjunction\Service\ConjunctionChecker;

// FIX: Explicitly setting the charset to UTF-8 for JSON responses
header('Content-Type: application/json; charset=utf-8');
$container = require __DIR__ . '/../config/container.php';
$action = $_GET['action'] ?? '';

function createSession($container): array // Change: add param, return array
{
    $sessionRepo = $container->get(GameSessionRepositoryInterface::class);
    $session = $sessionRepo->create('');
    return ['session_token' => $session->getSessionToken()];
}

function getRandomPair($container): array // Change: add param, return array
{
    $token = $_GET['token'] ?? throw new \Exception('Missing token');
    $pairRepo = $container->get(SentencePairRepositoryInterface::class);
    $sessionRepo = $container->get(GameSessionRepositoryInterface::class);

    $session = $sessionRepo->findByToken($token);

    // FIX: Check if the session was found before accessing its methods
    if ($session === null) {
        throw new \Exception('Session not found or token expired. Please create a new session.');
    }

    $pair = $pairRepo->findRandomByDifficulty(1);

    // FIX: Also check if a pair was found, though less likely given previous context.
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

function check($container): array // Change: add param, return array
{
    $input = json_decode(file_get_contents('php://input'), true);
    $pairRepo = $container->get(SentencePairRepositoryInterface::class);

    // Check if pair_id exists to prevent potential undefined array key warning
    if (!isset($input['pair_id'])) {
        throw new \Exception('Missing pair ID for check action.');
    }

    $pair = $pairRepo->find($input['pair_id']);

    // Check if the pair was found
    if ($pair === null) {
        throw new \Exception('Sentence pair not found.');
    }

    // Check if necessary inputs are present
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

    return ['verdict' => $verdict->toArray()];
}

try {
    switch ($action) {
        case 'create_session':
            $resp = createSession($container); // Change: pass $container
            break;
        case 'next':
            $resp = getRandomPair($container); // Change: pass $container
            break;
        case 'check':
            $resp = check($container); // Change: pass $container
            break;
        default:
            throw new \Exception('Invalid action');
    }
    echo json_encode($resp);
} catch (\Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
