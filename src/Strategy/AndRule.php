<?php

namespace Conjunction\Strategy;

use Conjunction\Entity\SentencePair;
use Conjunction\Entity\Conjunction;

/**
 * Strategy for "and" conjunction (addition, sequence)
 * SOLID Principles:
 * - SRP: Only handles "and" logic
 * - OCP: Can add new conjunction rules without changing this
 */
class AndRule implements ConjunctionRuleInterface
{
    public function applies(SentencePair $pair, Conjunction $choice): bool
    {
        // TODO: Implement
        // Return true if choice is AND and pair's correct answer is AND
    }

    public function getExplanation(): string
    {
        // TODO: Implement
        // Return child-friendly explanation of "and"
        // Example: "And is for adding things together or showing what happened next!"
    }

    public function getConjunctionType(): Conjunction
    {
        // TODO: Implement
    }
}
