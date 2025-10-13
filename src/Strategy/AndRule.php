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
    /**
     * Return true if pair's correct answer is AND and choice is AND
     */
    public function applies(SentencePair $pair, Conjunction $choice): bool
    {
        return $pair->getCorrectAnswer() == $choice;
    }

    public function getExplanation(): string
    {
        return "And is for adding things together or showing what happened next!";
    }

    public function getConjunctionType(): Conjunction
    {
        return Conjunction::AND;
    }
}
