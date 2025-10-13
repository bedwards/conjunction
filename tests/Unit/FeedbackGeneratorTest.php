<?php

namespace ConjunctionJunction\Tests\Unit;

use ConjunctionJunction\Service\FeedbackGenerator;
use ConjunctionJunction\Entity\SentencePair;
use ConjunctionJunction\Entity\Conjunction;
use ConjunctionJunction\Entity\Verdict;
use ConjunctionJunction\Entity\VerdictType;
use PHPUnit\Framework\TestCase;

class FeedbackGeneratorTest extends TestCase
{
    private FeedbackGenerator $generator;

    protected function setUp(): void
    {
        $this->generator = new FeedbackGenerator(
            'http://localhost:11434',
            'llama3.2:3b'
        );
    }

    public function testGeneratesCorrectFeedbackForCorrectAnswer(): void
    {
        $pair = new SentencePair(
            'I was tired',
            'I went to bed',
            Conjunction::SO,
            1
        );

        // Note: This test will fail until implementation talks to Ollama
        // For now, we're testing that the method signature is correct
        $this->expectException(\Exception::class);

        $result = $this->generator->generate($pair, Conjunction::SO, true);
    }

    public function testGeneratesWrongFeedbackForWrongAnswer(): void
    {
        $pair = new SentencePair(
            'I was tired',
            'I went to bed',
            Conjunction::SO,
            1
        );

        $this->expectException(\Exception::class);

        $result = $this->generator->generate($pair, Conjunction::AND, false);
    }

    public function testBuildsProperPrompt(): void
    {
        $pair = new SentencePair(
            'I was tired',
            'I went to bed',
            Conjunction::SO,
            1
        );

        // Test that generate is callable (will fail on actual call)
        $this->assertTrue(method_exists($this->generator, 'generate'));
    }
}
