<?php

namespace Conjunction\Strategy;

use Conjunction\Entity\SentencePair;
use Conjunction\Entity\Conjunction;

/**
 * Strategy for "so" conjunction (cause/effect)
 * SOLID Principles:
 * - SRP: Only handles "so" logic
 */
class SoRule implements ConjunctionRuleInterface
{
    public function applies(SentencePair $pair, Conjunction $choice): bool
    {
        // TODO: Implement
    }

    public function getExplanation(): string
    {
        // TODO: Implement
        // Example: "So shows what happened because of something!"
    }

    public function getConjunctionType(): Conjunction
    {
        // TODO: Implement
    }
}
