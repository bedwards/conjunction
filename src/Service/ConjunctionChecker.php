<?php

namespace Conjunction\Service;

use Conjunction\Entity\SentencePair;
use Conjunction\Entity\Conjunction;
use Conjunction\Entity\Verdict;
use Conjunction\Entity\VerdictType;
use Conjunction\Strategy\ConjunctionRuleInterface;

/**
 * Main service for checking conjunction answers
 * SOLID Principles:
 * - SRP: Only responsible for coordinating answer checking
 * - DIP: Depends on interfaces (FeedbackGenerator, SessionManager, Rules)
 * - OCP: Can add new rules without modifying this class
 */
class ConjunctionChecker
{
    /**
     * @param ConjunctionRuleInterface[] $rules
     */
    public function __construct(
        private FeedbackGenerator $feedbackGenerator,
        private SessionManager $sessionManager,
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
        // TODO: Implement
        // 1. Determine if correct using pair->isCorrectChoice()
        // 2. Generate feedback using feedbackGenerator
        // 3. Record answer using sessionManager
        // 4. Return Verdict
    }

    /**
     * Find the rule for a given conjunction
     * SRP: Isolated rule lookup logic
     */
    private function findRule(Conjunction $conjunction): ?ConjunctionRuleInterface
    {
        // TODO: Implement
        // Find matching rule from $this->rules array
    }
}
