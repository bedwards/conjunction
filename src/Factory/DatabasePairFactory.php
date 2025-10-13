<?php

namespace ConjunctionJunction\Factory;

use ConjunctionJunction\Entity\SentencePair;
use ConjunctionJunction\Repository\SentencePairRepositoryInterface;

/**
 * Factory that creates sentence pairs from database
 * SOLID Principles:
 * - SRP: Only responsible for fetching pairs from DB
 * - DIP: Depends on repository interface
 */
class DatabasePairFactory implements SentencePairFactoryInterface
{
    public function __construct(
        private SentencePairRepositoryInterface $repository
    ) {
    }

    public function getRandomPair(int $difficulty): ?SentencePair
    {
        // TODO: Implement
        // Delegate to repository
    }
}
