<?php

namespace Conjunction\Entity;

class Verdict
{
    public function __construct(
        private VerdictType $type,
        private string $explanation
    ) {
    }

    public function getType(): VerdictType
    {
        return $this->type;
    }

    public function getExplanation(): string
    {
        return $this->explanation;
    }

    /**
     * Check if verdict is correct
     */
    public function isCorrect(): bool
    {
        throw new \BadMethodCallException("Method " . __METHOD__ . " is not yet implemented.");
    }

    /**
     * Get CSS class for color coding
     * SRP: Presentation logic isolated here
     */
    public function getColorClass(): string
    {
        throw new \BadMethodCallException("Method " . __METHOD__ . " is not yet implemented.");
        // 'verdict-correct' => green
        // 'verdict-wrong' => red
        // 'verdict-okay' => yellow
    }

    /**
     * Convert to array for JSON response
     */
    public function toArray(): array
    {
        throw new \BadMethodCallException("Method " . __METHOD__ . " is not yet implemented.");
    }
}
