<?php

namespace Conjunction\Strategy;

use Conjunction\Entity\SentencePair;
use Conjunction\Entity\Conjunction;

/**
 * Strategy interface for conjunction rules
 * SOLID Principles:
 * - OCP: Open for extension (new rules) without modifying existing code
 * - LSP: All implementations are substitutable
 * - ISP: Small, focused interface
 */
interface RuleInterface
{
    /**
     * Does choice match this rule's conjuction?
     */
    public function applies(Conjunction $choice): bool;

    /**
     * Get explanation for this conjunction type
     */
    public function getExplanation(): string;

    /**
     * Get the conjunction associated with this rule.
     */
    public function getConjunction(): Conjunction;
}
