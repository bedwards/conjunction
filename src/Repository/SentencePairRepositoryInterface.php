<?php

namespace Conjunction\Repository;

use Conjunction\Entity\SentencePair;

/**
 * Repository interface for sentence pairs
 * SOLID Principles:
 * - DIP: High-level code depends on this abstraction, not concrete implementation
 * - ISP: Interface segregation - only methods clients need
 */
interface SentencePairRepositoryInterface
{
    /**
     * Get a random sentence pair by difficulty level
     * @psalm-suppress PossiblyUnusedMethod
     */
    public function findRandomByDifficulty(int $difficulty): ?SentencePair;

    /**
     * Find by ID
     * @psalm-suppress PossiblyUnusedMethod
     */
    public function findById(int $id): ?SentencePair;
}
