<?php

namespace ConjunctionJunction\Entity;

/**
 * Value Object representing the verdict of a choice
 * SOLID Principles:
 * - SRP: Only represents verdict data and computes color
 * - Immutability: Value object pattern - no setters
 */
enum VerdictType: string
{
    case CORRECT = 'correct';
    case WRONG = 'wrong';
    case OKAY = 'okay';
}

class Verdict
{
    public function __construct(
        private VerdictType $type,
        private string $explanation
    ) {
    }

    public function getType(): VerdictType
    {
        // TODO: Implement
    }

    public function getExplanation(): string
    {
        // TODO: Implement
    }

    /**
     * Check if verdict is correct
     */
    public function isCorrect(): bool
    {
        // TODO: Implement
    }

    /**
     * Get CSS class for color coding
     * SRP: Presentation logic isolated here
     */
    public function getColorClass(): string
    {
        // TODO: Implement
        // 'verdict-correct' => green
        // 'verdict-wrong' => red
        // 'verdict-okay' => yellow
    }

    /**
     * Convert to array for JSON response
     */
    public function toArray(): array
    {
        // TODO: Implement
    }
}
