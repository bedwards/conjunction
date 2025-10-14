<?php

require_once __DIR__ . '/../vendor/autoload.php';
use Conjunction\Entity\Conjunction;

header('Content-Type: application/json');
$container = require __DIR__ . '/../config/container.php';
$action = $_GET['action'] ?? '';

function createSession($container): array  // Change: add param, return array
{
    $sessionRepo = $container->get(\Conjunction\Repository\GameSessionRepositoryInterface::class);
    $session = $sessionRepo->create('');
    return ['session_token' => $session->getSessionToken()];
}

function getRandomPair($container): array  // Change: add param, return array
{
    $token = $_GET['token'] ?? throw new \Exception('Missing token');
    $pairRepo = $container->get(\Conjunction\Repository\SentencePairRepositoryInterface::class);
    $sessionRepo = $container->get(\Conjunction\Repository\GameSessionRepositoryInterface::class);

    $session = $sessionRepo->findByToken($token);
    $pair = $pairRepo->findRandomByDifficulty(1);

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

function check($container): array  // Change: add param, return array
{
    $input = json_decode(file_get_contents('php://input'), true);
    $pairRepo = $container->get(\Conjunction\Repository\SentencePairRepositoryInterface::class);

    $pair = $pairRepo->find($input['pair_id']);
    $checker = $container->get(\Conjunction\Service\ConjunctionChecker::class);

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
            $resp = createSession($container);  // Change: pass $container
            break;
        case 'next':
            $resp = getRandomPair($container);  // Change: pass $container
            break;
        case 'check':
            $resp = check($container);  // Change: pass $container
            break;
        default:
            throw new \Exception('Invalid action');
    }
    echo json_encode($resp);
} catch (\Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
