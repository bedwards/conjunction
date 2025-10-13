<?php

namespace Conjunction\Repository;

use Conjunction\Entity\GameSession;
use PDO;

/**
 * Raw PDO implementation for game sessions (fast, simple operations)
 * SOLID Principles:
 * - SRP: Only responsible for session data access
 * - DIP: Implements interface, could swap for Redis/Memcached later
 *
 * @extends EntityRepository<GameSession>
 */
class GameSessionRepository implements GameSessionRepositoryInterface
{
    public function __construct(
        private PDO $pdo
    ) {
    }

    #[\Override]
    public function create(string $sessionToken): GameSession
    {
        throw new \BadMethodCallException("Method " . __METHOD__ . " is not yet implemented.");
        // INSERT INTO game_sessions and return new GameSession object
    }

    #[\Override]
    public function findByToken(string $token): ?GameSession
    {
        throw new \BadMethodCallException("Method " . __METHOD__ . " is not yet implemented.");
        // SELECT and hydrate GameSession object
    }

    #[\Override]
    public function update(GameSession $session): void
    {
        throw new \BadMethodCallException("Method " . __METHOD__ . " is not yet implemented.");
        // UPDATE game_sessions SET total_questions, correct_answers, last_activity
    }

    #[\Override]
    public function recordAnswer(
        int $sessionId,
        int $pairId,
        string $userChoice,
        bool $wasCorrect,
        int $responseTimeMs
    ): void {
        throw new \BadMethodCallException("Method " . __METHOD__ . " is not yet implemented.");
        // INSERT INTO session_answers
    }
}
