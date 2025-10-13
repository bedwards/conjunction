<?php

namespace Conjunction\Factory;

use Conjunction\Entity\SentencePair;
use Conjunction\Repository\SentencePairRepositoryInterface;

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

    #[\Override]
    public function getRandomPair(int $difficulty): ?SentencePair
    {
        throw new \BadMethodCallException("Method " . __METHOD__ . " is not yet implemented.");
        // Delegate to repository
    }
}
