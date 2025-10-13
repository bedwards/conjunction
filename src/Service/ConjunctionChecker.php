<?php

namespace Conjunction\Service;

use Conjunction\Entity\SentencePair;
use Conjunction\Entity\Conjunction;
use Conjunction\Entity\Verdict;
use Conjunction\Entity\VerdictType;
use Conjunction\Repository\GameSessionRepositoryInterface;
use Conjunction\Strategy\RuleInterface;

/**
 * Main service for checking conjunction answers
 * SOLID Principles:
 * - SRP: Only responsible for coordinating answer checking
 * - DIP: Depends on interfaces (FeedbackGenerator, GameSessionRepositoryInterface, Rules)
 * - OCP: Can add new rules without modifying this class
 */
final class ConjunctionChecker
{
    /**
     * @param RuleInterface[] $rules
     */
    public function __construct(
        private FeedbackGenerator $feedbackGenerator,
        private GameSessionRepositoryInterface $sessionRepository,
        private array $rules
    ) {
    }

    /**
     * Check user's answer and return verdict
     */
    public function check(
        SentencePair $pair,
        Conjunction $userChoice,
        string $sessionToken,
        int $responseTimeMs
    ): Verdict {
        $isCorrect = $pair->isCorrectChoice($userChoice);
        $verdict = $this->feedbackGenerator->generate($pair, $userChoice, $isCorrect);
        $this->sessionRepository->recordAnswer(
            $sessionToken,
            $pair->getId(),
            $userChoice,
            $isCorrect,
            $responseTimeMs
        );
        return $verdict;
    }

    /**
     * Find the rule for a given conjunction
     * SRP: Isolated rule lookup logic
     */
    private function findRule(Conjunction $conjunction): ?RuleInterface
    {
        throw new \BadMethodCallException("Method " . __METHOD__ . " is not yet implemented.");
        // Find matching rule from $this->rules array
    }
}
