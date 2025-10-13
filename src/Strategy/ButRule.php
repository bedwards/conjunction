<?php

namespace Conjunction\Strategy;

use Conjunction\Entity\SentencePair;
use Conjunction\Entity\Conjunction;

/**
 * Strategy for "but" conjunction (contrast, opposition)
 * SOLID Principles:
 * - SRP: Only handles "but" logic
 */
class ButRule implements ConjunctionRuleInterface
{
    public function applies(SentencePair $pair, Conjunction $choice): bool
    {
        // TODO: Implement
    }

    public function getExplanation(): string
    {
        // TODO: Implement
        // Example: "But shows when something is different or surprising!"
    }

    public function getConjunctionType(): Conjunction
    {
        // TODO: Implement
    }
}
