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
        return $this->type == VerdictType::CORRECT;
    }

    /**
     * Get CSS class for color coding
     * SRP: Presentation logic isolated here
     */
    public function getColorClass(): string
    {
        // public/game.html
        return match($this->type) {
            VerdictType::CORRECT => 'verdict-correct',
            VerdictType::OKAY => 'verdict-okay',
            VerdictType::WRONG => 'verdict-wrong',
        };
    }

    /**
     * Convert to array for JSON response
     */
    public function toArray(): array
    {
        return array(
            "type" => $this->type->value,
            "explanation" => $this->explanation,
            "color_class" => $this->getColorClass(),
            "is_correct" => $this->isCorrect(),
        );
    }
}
