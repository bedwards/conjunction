<?php

namespace Conjunction\Entity;

/**
 * Simple DTO for game session (no ORM overhead)
 * SOLID Principles:
 * - SRP: Only represents session data and calculates accuracy
 * - ISP: Small interface with only needed methods
 */
class GameSession
{
    public function __construct(
        private ?int $id,
        private string $sessionToken,
        private int $totalQuestions = 0,
        private int $correctAnswers = 0,
        private ?\DateTimeInterface $startedAt = null,
        private ?\DateTimeInterface $lastActivity = null
    ) {
        $this->startedAt = $startedAt ?? new \DateTime();
        $this->lastActivity = $lastActivity ?? new \DateTime();
    }

    public function getId(): ?int
    {
        // TODO: Implement
    }

    public function getSessionToken(): string
    {
        // TODO: Implement
    }

    public function getTotalQuestions(): int
    {
        // TODO: Implement
    }

    public function getCorrectAnswers(): int
    {
        // TODO: Implement
    }

    /**
     * Record an answer and update statistics
     * SRP: Single responsibility of updating session state
     */
    public function recordAnswer(bool $wasCorrect): void
    {
        // TODO: Implement - increment totals
    }

    /**
     * Calculate accuracy percentage
     * SRP: Calculation logic isolated in one place
     */
    public function getAccuracy(): float
    {
        // TODO: Implement - return 0.0 if no questions answered
    }

    public function getStartedAt(): \DateTimeInterface
    {
        // TODO: Implement
    }

    public function getLastActivity(): \DateTimeInterface
    {
        // TODO: Implement
    }
}
