<?php

namespace ConjunctionJunction\Repository;

use ConjunctionJunction\Entity\GameSession;

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
     * Update session statistics
     */
    public function update(GameSession $session): void;

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
