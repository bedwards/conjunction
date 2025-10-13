<?php

namespace ConjunctionJunction\Repository;

use ConjunctionJunction\Entity\SentencePair;
use Doctrine\ORM\EntityRepository;

/**
 * Doctrine ORM implementation of SentencePairRepository
 * SOLID Principles:
 * - SRP: Only responsible for data access of sentence pairs
 * - DIP: Implements interface, allowing easy substitution
 */
class SentencePairRepository extends EntityRepository implements SentencePairRepositoryInterface
{
    public function findRandomByDifficulty(int $difficulty): ?SentencePair
    {
        // TODO: Implement using DQL
        // SELECT random pair WHERE difficulty_level = :difficulty
        // Use RAND() or similar
    }

    public function find($id): ?SentencePair
    {
        // TODO: Implement using parent::find()
    }

    public function findAll(): array
    {
        // TODO: Implement using parent::findAll()
    }
}
