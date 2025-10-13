<?php

namespace Conjunction\Factory;

use Conjunction\Entity\SentencePair;

/**
 * Factory interface for creating sentence pairs
 * SOLID Principles:
 * - DIP: Depend on abstraction, not concrete factory
 * - OCP: Can add new factory implementations (LLM-based)
 */
interface SentencePairFactoryInterface
{
    /**
     * Get a random sentence pair by difficulty
     */
    public function getRandomPair(int $difficulty): ?SentencePair;
}
