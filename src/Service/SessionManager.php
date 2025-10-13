<?php

namespace Conjunction\Service;

use Conjunction\Entity\GameSession;
use Conjunction\Repository\GameSessionRepositoryInterface;

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
        $token = bin2hex(random_bytes(32));
        $this->sessionRepository->create($token);
        return $token;
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
        throw new \BadMethodCallException("Method " . __METHOD__ . " is not yet implemented.");
        // Find session, update stats, record answer
    }

    /**
     * Get session by token
     */
    public function getSession(string $token): ?GameSession
    {
        throw new \BadMethodCallException("Method " . __METHOD__ . " is not yet implemented.");
    }

    /**
     * Generate unique session token
     * SRP: Isolated token generation
     */
    private function generateToken(): string
    {
        throw new \BadMethodCallException("Method " . __METHOD__ . " is not yet implemented.");
        // Use bin2hex(random_bytes(32))
    }
}
