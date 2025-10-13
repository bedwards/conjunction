<?php

namespace ConjunctionJunction\Strategy;

use ConjunctionJunction\Entity\SentencePair;
use ConjunctionJunction\Entity\Conjunction;

/**
 * Strategy interface for conjunction rules
 * SOLID Principles:
 * - OCP: Open for extension (new rules) without modifying existing code
 * - LSP: All implementations are substitutable
 * - ISP: Small, focused interface
 */
interface ConjunctionRuleInterface
{
    /**
     * Check if this rule applies to the given pair and choice
     */
    public function applies(SentencePair $pair, Conjunction $choice): bool;

    /**
     * Get explanation for this conjunction type
     */
    public function getExplanation(): string;

    /**
     * Get the conjunction type this rule handles
     */
    public function getConjunctionType(): Conjunction;
}
