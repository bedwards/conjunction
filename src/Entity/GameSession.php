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
        return $this->id;
    }

    public function getSessionToken(): string
    {
        return $this->sessionToken;
    }

    public function getTotalQuestions(): int
    {
        return $this->totalQuestions;
    }

    public function getCorrectAnswers(): int
    {
        return $this->correctAnswers;
    }

    /**
     * Record an answer and update statistics
     * SRP: Single responsibility of updating session state
     */
    public function recordAnswer(bool $wasCorrect): void
    {
        /*
         * Unlike Java where objects live in shared memory
         * across threads, each PHP request gets its own
         * isolated process/memory space. A GameSession
         * object only exists for the duration of one HTTP
         * request.
         */
        if ($wasCorrect) {
            $this->correctAnswers++;
        }
        $this->totalQuestions++;
    }

    /**
     * Calculate accuracy percentage
     * SRP: Calculation logic isolated in one place
     */
    public function getAccuracy(): float
    {
        if ($this->totalQuestions == 0) {
            return 0.0;
        }
        return $this->correctAnswers / $this->totalQuestions;
    }

    public function getStartedAt(): \DateTimeInterface
    {
        return $this->startedAt;
    }

    public function getLastActivity(): \DateTimeInterface
    {
        return $this->lastActivity;
    }
}
