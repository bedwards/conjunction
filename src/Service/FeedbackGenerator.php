<?php

namespace ConjunctionJunction\Service;

use ConjunctionJunction\Entity\SentencePair;
use ConjunctionJunction\Entity\Conjunction;
use ConjunctionJunction\Entity\Verdict;
use ConjunctionJunction\Entity\VerdictType;

/**
 * Service for generating LLM-based feedback
 * SOLID Principles:
 * - SRP: Only responsible for Ollama communication and feedback generation
 * - DIP: Could inject HTTP client interface for testing
 */
class FeedbackGenerator
{
    public function __construct(
        private string $ollamaHost,
        private string $ollamaModel
    ) {
    }

    /**
     * Generate feedback using Ollama LLM
     */
    public function generate(
        SentencePair $pair,
        Conjunction $userChoice,
        bool $isCorrect
    ): Verdict {
        // TODO: Implement
        // 1. Build prompt using buildPrompt()
        // 2. Send to Ollama API
        // 3. Parse response using parseOllamaResponse()
        // 4. Return Verdict object
    }

    /**
     * Build the prompt for Ollama
     * SRP: Isolated prompt construction logic
     */
    private function buildPrompt(
        SentencePair $pair,
        Conjunction $userChoice,
        bool $isCorrect
    ): string {
        // TODO: Implement
        // Return formatted prompt with context and instructions
    }

    /**
     * Parse Ollama JSON response
     * SRP: Isolated parsing logic
     */
    private function parseOllamaResponse(string $jsonResponse): Verdict
    {
        // TODO: Implement
        // Parse JSON and return Verdict object
        // Handle errors gracefully
    }
}
