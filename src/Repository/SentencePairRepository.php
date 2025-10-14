<?php

namespace Conjunction\Repository;

use Conjunction\Entity\SentencePair;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;

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
        $entityManager = $this->getEntityManager();
        $sql = 'SELECT id FROM sentence_pairs WHERE difficulty_level = :difficulty ORDER BY RAND() LIMIT 1';
        $connection = $entityManager->getConnection();
        $statement = $connection->prepare($sql);
        $statement->bindValue('difficulty', $difficulty);
        $result = $statement->executeQuery()->fetchOne();

        if ($result) {
            // Now, use the DQL/ORM's find method to fetch the actual entity by its ID
            return $this->find($result);
        }

        return null;
    }

    #[\Override]
    public function findById($id): ?SentencePair
    {
        return parent::find($id, null, null);
    }
}
