<?php

namespace Conjunction\Tests\Unit;

use Conjunction\Service\FeedbackGenerator;
use Conjunction\Entity\SentencePair;
use Conjunction\Entity\Conjunction;
use Conjunction\Entity\Verdict;
use Conjunction\Entity\VerdictType;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for FeedbackGenerator
 *
 * These tests mock the Ollama API responses to avoid network calls.
 * For integration tests with real Ollama, see tests/Integration/
 */
class FeedbackGeneratorTest extends TestCase
{
    private string $ollamaHost = 'http://test.localhost:11434';
    private string $ollamaModel = 'llama3.2:3b';

    /**
     * Helper to create mock Ollama response
     */
    private function mockOllamaResponse(string $responseText): string
    {
        return json_encode([
            'model' => $this->ollamaModel,
            'response' => $responseText,
            'done' => true
        ]);
    }

    /**
     * @group work
     */
    public function testBuildPromptCreatesCorrectFormatForCorrectAnswer(): void
    {
        $generator = new FeedbackGenerator($this->ollamaHost, $this->ollamaModel);

        $pair = new SentencePair(
            'I was tired',
            'I went to bed',
            Conjunction::SO,
            1
        );

        // Use reflection to test private method
        $reflection = new \ReflectionClass($generator);
        $method = $reflection->getMethod('buildPrompt');

        $prompt = $method->invoke($generator, $pair, Conjunction::SO, true);

        $this->assertIsString($prompt);
        $this->assertStringContainsString('I was tired', $prompt);
        $this->assertStringContainsString('I went to bed', $prompt);
        $this->assertStringContainsString('so', strtolower($prompt));
        $this->assertStringContainsString('CORRECT', $prompt);
    }

    /**
     * @group work
     */
    public function testBuildPromptCreatesCorrectFormatForWrongAnswer(): void
    {
        $generator = new FeedbackGenerator($this->ollamaHost, $this->ollamaModel);

        $pair = new SentencePair(
            'I was tired',
            'I went to bed',
            Conjunction::SO,
            1
        );

        $reflection = new \ReflectionClass($generator);
        $method = $reflection->getMethod('buildPrompt');

        $prompt = $method->invoke($generator, $pair, Conjunction::AND, false);

        $this->assertIsString($prompt);
        $this->assertStringContainsString('WRONG', $prompt);
        $this->assertStringContainsString('and', strtolower($prompt));
    }

    /**
     * @group work
     */
    public function testParseOllamaResponseCreatesCorrectVerdict(): void
    {
        $generator = new FeedbackGenerator($this->ollamaHost, $this->ollamaModel);

        $mockResponse = $this->mockOllamaResponse(
            'Great job! So shows what happened because you were tired!'
        );

        $reflection = new \ReflectionClass($generator);
        $method = $reflection->getMethod('parseOllamaResponse');

        $verdict = $method->invoke($generator, $mockResponse, true);

        $this->assertInstanceOf(Verdict::class, $verdict);
        $this->assertEquals(VerdictType::CORRECT, $verdict->getType());
        $this->assertStringContainsString('Great job', $verdict->getExplanation());
    }

    /**
     * @group work
     */
    public function testParseOllamaResponseCreatesWrongVerdict(): void
    {
        $generator = new FeedbackGenerator($this->ollamaHost, $this->ollamaModel);

        $mockResponse = $this->mockOllamaResponse(
            'Not quite! And is for adding things together.'
        );

        $reflection = new \ReflectionClass($generator);
        $method = $reflection->getMethod('parseOllamaResponse');

        $verdict = $method->invoke($generator, $mockResponse, false);

        $this->assertInstanceOf(Verdict::class, $verdict);
        $this->assertEquals(VerdictType::WRONG, $verdict->getType());
        $this->assertStringContainsString('Not quite', $verdict->getExplanation());
    }

    /**
     * @group work
     */
    public function testParseOllamaResponseThrowsOnInvalidJson(): void
    {
        $generator = new FeedbackGenerator($this->ollamaHost, $this->ollamaModel);

        $invalidJson = 'not valid json{';

        $reflection = new \ReflectionClass($generator);
        $method = $reflection->getMethod('parseOllamaResponse');

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Failed to parse Ollama response');

        $method->invoke($generator, $invalidJson, true);
    }

    /**
     * @group work
     */
    public function testParseOllamaResponseThrowsOnMissingResponseField(): void
    {
        $generator = new FeedbackGenerator($this->ollamaHost, $this->ollamaModel);

        $invalidResponse = json_encode(['model' => 'llama3.2', 'done' => true]);

        $reflection = new \ReflectionClass($generator);
        $method = $reflection->getMethod('parseOllamaResponse');

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('missing "response" field');

        $method->invoke($generator, $invalidResponse, true);
    }

    /**
     * @group work
     */
    public function testParseOllamaResponseThrowsOnEmptyResponse(): void
    {
        $generator = new FeedbackGenerator($this->ollamaHost, $this->ollamaModel);

        $emptyResponse = json_encode(['response' => '   ', 'done' => true]);

        $reflection = new \ReflectionClass($generator);
        $method = $reflection->getMethod('parseOllamaResponse');

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('empty response');

        $method->invoke($generator, $emptyResponse, true);
    }

    /**
     * @group work
     */
    public function testParseOllamaResponseTrimsWhitespace(): void
    {
        $generator = new FeedbackGenerator($this->ollamaHost, $this->ollamaModel);

        $mockResponse = $this->mockOllamaResponse('  Great job!  ');

        $reflection = new \ReflectionClass($generator);
        $method = $reflection->getMethod('parseOllamaResponse');

        $verdict = $method->invoke($generator, $mockResponse, true);

        $this->assertEquals('Great job!', $verdict->getExplanation());
    }

    /**
     * @group work
     */
    public function testBuildPromptIncludesAllNecessaryContext(): void
    {
        $generator = new FeedbackGenerator($this->ollamaHost, $this->ollamaModel);

        $pair = new SentencePair(
            'He ran fast',
            'he came in second',
            Conjunction::BUT,
            2
        );

        $reflection = new \ReflectionClass($generator);
        $method = $reflection->getMethod('buildPrompt');

        $prompt = $method->invoke($generator, $pair, Conjunction::BUT, true);

        // Check all required elements are in prompt
        $this->assertStringContainsString('He ran fast', $prompt);
        $this->assertStringContainsString('he came in second', $prompt);
        $this->assertStringContainsString('but', strtolower($prompt));
        $this->assertStringContainsString('ages 7-10', strtolower($prompt));
    }

    /**
     * @group work
     */
    public function testBuildPromptForDifferentConjunctions(): void
    {
        $generator = new FeedbackGenerator($this->ollamaHost, $this->ollamaModel);

        $pair = new SentencePair(
            'I had a sandwich',
            'I had chips',
            Conjunction::AND,
            1
        );

        $reflection = new \ReflectionClass($generator);
        $method = $reflection->getMethod('buildPrompt');

        // Test with each conjunction
        $promptAnd = $method->invoke($generator, $pair, Conjunction::AND, true);
        $promptBut = $method->invoke($generator, $pair, Conjunction::BUT, false);
        $promptSo = $method->invoke($generator, $pair, Conjunction::SO, false);

        $this->assertStringContainsString('and', strtolower($promptAnd));
        $this->assertStringContainsString('but', strtolower($promptBut));
        $this->assertStringContainsString('so', strtolower($promptSo));
    }
}
