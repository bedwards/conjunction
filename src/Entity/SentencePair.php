<?php

namespace Conjunction\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Doctrine Entity for sentence pairs
 * SOLID Principles:
 * - SRP: Only represents a sentence pair domain model
 * - OCP: Can extend with new properties without modifying existing code
 */
#[ORM\Entity]
#[ORM\Table(name: 'sentence_pairs')]
class SentencePair
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    private string $firstPart;

    #[ORM\Column(type: 'string', length: 255)]
    private string $secondPart;

    #[ORM\Column(type: 'string', enumType: Conjunction::class)]
    private Conjunction $correctAnswer;

    #[ORM\Column(type: 'integer')]
    private int $difficultyLevel;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $createdAt;

    public function __construct(
        string $firstPart,
        string $secondPart,
        Conjunction $correctAnswer,
        int $difficultyLevel
    ) {
        $this->firstPart = $firstPart;
        $this->secondPart = $secondPart;
        $this->correctAnswer = $correctAnswer;
        $this->difficultyLevel = $difficultyLevel;
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstPart(): string
    {
        return $this->firstPart;
    }

    public function getSecondPart(): string
    {
        return $this->secondPart;
    }

    public function getCorrectAnswer(): Conjunction
    {
        return $this->correctAnswer;
    }

    public function getDifficultyLevel(): int
    {
        return $this->difficultyLevel;
    }

    /**
     * Check if the given choice is correct
     * SRP: Single method with single responsibility
     */
    public function isCorrectChoice(Conjunction $choice): bool
    {
        return $choice == $this->correctAnswer;
    }

    /**
     * Get the complete sentence with the given conjunction
     */
    public function getFullSentence(Conjunction $connector): string
    {
        return "$this->firstPart, $connector->value $this->secondPart";
    }
}
