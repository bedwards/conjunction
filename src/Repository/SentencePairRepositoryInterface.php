<?php

namespace ConjunctionJunction\Repository;

use ConjunctionJunction\Entity\SentencePair;

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
     */
    public function findRandomByDifficulty(int $difficulty): ?SentencePair;

    /**
     * Find by ID
     */
    public function find(int $id): ?SentencePair;

    /**
     * Get all sentence pairs
     * @return SentencePair[]
     */
    public function findAll(): array;
}
