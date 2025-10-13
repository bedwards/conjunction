<?php

namespace Conjunction\Repository;

use Conjunction\Entity\SentencePair;
use Doctrine\ORM\EntityRepository;

/**
 * Doctrine ORM implementation of SentencePairRepository
 * SOLID Principles:
 * - SRP: Only responsible for data access of sentence pairs
 * - DIP: Implements interface, allowing easy substitution
 *
 * @extends EntityRepository<SentencePair>
 */
class SentencePairRepository extends EntityRepository implements SentencePairRepositoryInterface
{
    #[\Override]
    public function findRandomByDifficulty(int $difficulty): ?SentencePair
    {
        throw new \BadMethodCallException("Method " . __METHOD__ . " is not yet implemented.");
        // using DQL
        // SELECT random pair WHERE difficulty_level = :difficulty
        // Use RAND() or similar
    }

    #[\Override]
    public function find($id): ?SentencePair
    {
        throw new \BadMethodCallException("Method " . __METHOD__ . " is not yet implemented.");
        // using parent::find()
    }

    #[\Override]
    public function findAll(): array
    {
        throw new \BadMethodCallException("Method " . __METHOD__ . " is not yet implemented.");
        // using parent::findAll()
    }
}
