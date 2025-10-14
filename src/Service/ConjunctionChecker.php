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
    public function __construct(
        private FeedbackGenerator $feedbackGenerator,
        private GameSessionRepositoryInterface $sessionRepository
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
        $session = $this->sessionRepository->findByToken($sessionToken);
        $isCorrect = $pair->isCorrectChoice($userChoice);
        $verdict = $this->feedbackGenerator->generate($pair, $userChoice, $isCorrect);
        $this->sessionRepository->recordAnswer(
            $session->getId(),
            $pair->getId(),
            $userChoice->value,
            $isCorrect,
            $responseTimeMs
        );
        return $verdict;
    }
}
