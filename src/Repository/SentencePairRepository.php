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
        return $this->createQueryBuilder('sp')
            ->where('sp.difficultyLevel = :difficulty')
            ->setParameter('difficulty', $difficulty)
            ->orderBy('RAND()')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    #[\Override]
    public function find($id): ?SentencePair
    {
        return parent::find($id);
    }
}
