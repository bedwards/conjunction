<?php

namespace Conjunction\Entity;

use Doctrine\ORM\Mapping as ORM;
use Conjunction\Repository\SentencePairRepository;
use Conjunction\Entity\Conjunction; // Import the Enum defined later in this file

/**
 * Doctrine Entity for sentence pairs
 * * Maps to the 'sentence_pairs' table, resolving the snake_case column names
 * (first_part, second_part, difficulty_level) to the camelCase PHP properties.
 * * SOLID Principles:
 * - SRP: Only represents a sentence pair domain model
 * - OCP: Can extend with new properties without modifying existing code
 */
#[ORM\Entity(repositoryClass: SentencePairRepository::class)]
#[ORM\Table(name: 'sentence_pairs')]
class SentencePair
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    /**
     * Corrected: Explicitly maps to the database column 'first_part'.
     */
    #[ORM\Column(name: 'first_part', type: 'string', length: 255)]
    private string $firstPart;

    /**
     * Corrected: Explicitly maps to the database column 'second_part'.
     */
    #[ORM\Column(name: 'second_part', type: 'string', length: 255)]
    private string $secondPart;

    /**
     * Corrected: Explicitly maps to 'correct_answer' and uses the Conjunction enum.
     */
    #[ORM\Column(name: 'correct_answer', type: 'string', enumType: Conjunction::class)]
    private Conjunction $correctAnswer;

    /**
     * Corrected: Explicitly maps to the database column 'difficulty_level'.
     */
    #[ORM\Column(name: 'difficulty_level', type: 'integer')]
    private int $difficultyLevel;

    /**
     * Best Practice: Using DateTimeImmutable for consistency and preventing mutation.
     */
    #[ORM\Column(name: 'created_at', type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

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
        // Using DateTimeImmutable as per best practice
        $this->createdAt = new \DateTimeImmutable();
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

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * Check if the given choice is correct
     * SRP: Single method with single responsibility
     */
    public function isCorrectChoice(Conjunction $choice): bool
    {
        return $choice === $this->correctAnswer; // Using strict comparison
    }

    /**
     * Get the complete sentence with the given conjunction
     */
    public function getFullSentence(Conjunction $connector): string
    {
        return "$this->firstPart, $connector->value $this->secondPart";
    }
}
