<?php

namespace ConjunctionJunction\Service;

use ConjunctionJunction\Entity\GameSession;
use ConjunctionJunction\Repository\GameSessionRepositoryInterface;

/**
 * Service for managing game sessions
 * SOLID Principles:
 * - SRP: Only responsible for session lifecycle management
 * - DIP: Depends on repository interface
 */
class SessionManager
{
    public function __construct(
        private GameSessionRepositoryInterface $sessionRepository
    ) {
    }

    /**
     * Create a new game session
     * @return string Session token
     */
    public function createSession(): string
    {
        // TODO: Implement
        // Generate unique token, create session, return token
    }

    /**
     * Record an answer for a session
     */
    public function recordAnswer(
        string $token,
        int $pairId,
        string $userChoice,
        bool $wasCorrect,
        int $responseTimeMs
    ): void {
        // TODO: Implement
        // Find session, update stats, record answer
    }

    /**
     * Get session by token
     */
    public function getSession(string $token): ?GameSession
    {
        // TODO: Implement
    }

    /**
     * Generate unique session token
     * SRP: Isolated token generation
     */
    private function generateToken(): string
    {
        // TODO: Implement
        // Use bin2hex(random_bytes(32))
    }
}
