<?php

namespace ConjunctionJunction\Tests\Unit;

use ConjunctionJunction\Strategy\ButRule;
use ConjunctionJunction\Entity\SentencePair;
use ConjunctionJunction\Entity\Conjunction;
use PHPUnit\Framework\TestCase;

class ButRuleTest extends TestCase
{
    private ButRule $rule;

    protected function setUp(): void
    {
        $this->rule = new ButRule();
    }

    public function testAppliesReturnsTrueWhenCorrect(): void
    {
        $pair = new SentencePair(
            'I like pizza',
            'I don\'t like olives',
            Conjunction::BUT,
            1
        );

        $result = $this->rule->applies($pair, Conjunction::BUT);

        $this->assertTrue($result);
    }

    public function testAppliesReturnsFalseWhenIncorrect(): void
    {
        $pair = new SentencePair(
            'I was tired',
            'I went to bed',
            Conjunction::SO,
            1
        );

        $result = $this->rule->applies($pair, Conjunction::BUT);

        $this->assertFalse($result);
    }

    public function testGetExplanationReturnsString(): void
    {
        $explanation = $this->rule->getExplanation();

        $this->assertIsString($explanation);
        $this->assertNotEmpty($explanation);
        $this->assertStringContainsString('but', strtolower($explanation));
    }

    public function testGetConjunctionTypeReturnsBut(): void
    {
        $this->assertEquals(Conjunction::BUT, $this->rule->getConjunctionType());
    }
}
