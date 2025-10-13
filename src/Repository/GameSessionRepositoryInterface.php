<?php

namespace Conjunction\Repository;

use Conjunction\Entity\GameSession;

/**
 * Repository interface for game sessions
 * SOLID Principles:
 * - DIP: Abstraction for session data access
 * - ISP: Minimal interface for session operations
 */
interface GameSessionRepositoryInterface
{
    /**
     * Create a new game session
     */
    public function create(string $sessionToken): GameSession;

    /**
     * Find session by token
     */
    public function findByToken(string $token): ?GameSession;

    /**
     * Record an answer for a session
     */
    public function recordAnswer(
        int $sessionId,
        int $pairId,
        string $userChoice,
        bool $wasCorrect,
        int $responseTimeMs
    ): void;
}
