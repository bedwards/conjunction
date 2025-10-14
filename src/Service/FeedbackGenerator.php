<?php

namespace Conjunction\Service;

use Conjunction\Entity\SentencePair;
use Conjunction\Entity\Conjunction;
use Conjunction\Entity\Verdict;
use Conjunction\Entity\VerdictType;

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
        // 1. Build prompt using buildPrompt()
        $prompt = $this->buildPrompt($pair, $userChoice, $isCorrect);

        // 2. Send to Ollama API (inline implementation)
        $ch = curl_init($this->ollamaHost . '/api/generate');

        // These are PHP constants from ext-curl (already defined by PHP):
        // CURLOPT_RETURNTRANSFER    = 19913 (return response as string instead of outputting)
        // CURLOPT_POST            = 47 (use POST method)
        // CURLOPT_HTTPHEADER        = 10023 (set HTTP headers)
        // CURLOPT_POSTFIELDS        = 10015 (set POST data)
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
            'model' => $this->ollamaModel,
            'prompt' => $prompt,
            'stream' => false
        ]));

        $jsonResponse = curl_exec($ch);

        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);
            throw new \RuntimeException('Ollama API error: ' . $error);
        }

        // Get the HTTP status code
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        // 💥 Robust Error Handling: Check HTTP code and extract error message from body
        if ($httpCode !== 200) {
            $errorMessage = 'Unknown API error.';

            if ($jsonResponse) {
                $data = json_decode($jsonResponse, true);

                // Ollama errors typically have an 'error' key in the JSON response
                if (isset($data['error'])) {
                    $errorMessage = $data['error'];
                } else {
                    // Fallback to the raw body if it's not JSON or doesn't have the 'error' key
                    $errorMessage = "Raw response: " . $jsonResponse;
                }
            }

            // Throw a rich exception including the HTTP code and the extracted message
            throw new \RuntimeException("Ollama API call failed. HTTP {$httpCode}: {$errorMessage}");
        }
        // 💥 End Robust Error Handling

        // 3. Parse response using parseOllamaResponse()
        $verdict = $this->parseOllamaResponse($jsonResponse, $isCorrect);

        // 4. Return Verdict object
        return $verdict;
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
        $correctAnswer = $pair->getCorrectAnswer()->value;
        $userChoiceValue = $userChoice->value;
        $status = $isCorrect ? "CORRECT" : "WRONG";

        $prompt = <<<PROMPT
You are a friendly teacher helping kids (ages 7-10) learn conjunctions (and, but, so).

Sentence: "{$pair->getFirstPart()}" ___ "{$pair->getSecondPart()}"
Correct answer: {$correctAnswer}
Student chose: {$userChoiceValue}
Result: {$status}

Give a brief, encouraging explanation (1-2 sentences) suitable for kids.
- If CORRECT: Praise them and explain why it works
- If WRONG: Gently correct and explain the difference

Response:
PROMPT;

        return $prompt;
    }

    /**
     * Parse Ollama JSON response
     * SRP: Isolated parsing logic
     */
    private function parseOllamaResponse(string $jsonResponse, bool $isCorrect): Verdict
    {
        $data = json_decode($jsonResponse, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException('Failed to parse Ollama response: ' . json_last_error_msg());
        }

        if (!isset($data['response'])) {
            throw new \RuntimeException('Ollama response missing "response" field');
        }

        $explanation = trim($data['response']);

        if (empty($explanation)) {
            throw new \RuntimeException('Ollama returned empty response');
        }

        $verdictType = $isCorrect ? VerdictType::CORRECT : VerdictType::WRONG;

        return new Verdict($verdictType, $explanation);
    }
}
