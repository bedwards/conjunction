<?php

namespace Conjunction\Tests\Unit;

use Conjunction\Strategy\SoRule;
use Conjunction\Entity\SentencePair;
use Conjunction\Entity\Conjunction;
use PHPUnit\Framework\TestCase;

class SoRuleTest extends TestCase
{
    private SoRule $rule;

    protected function setUp(): void
    {
        $this->rule = new SoRule();
    }

    public function testAppliesReturnsTrueWhenCorrect(): void
    {
        $pair = new SentencePair(
            'I was tired',
            'I went to bed',
            Conjunction::SO,
            1
        );

        $result = $this->rule->applies($pair, Conjunction::SO);

        $this->assertTrue($result);
    }

    public function testAppliesReturnsFalseWhenIncorrect(): void
    {
        $pair = new SentencePair(
            'I like pizza',
            'I don\'t like olives',
            Conjunction::BUT,
            1
        );

        $result = $this->rule->applies($pair, Conjunction::SO);

        $this->assertFalse($result);
    }

    public function testGetExplanationReturnsString(): void
    {
        $explanation = $this->rule->getExplanation();

        $this->assertIsString($explanation);
        $this->assertNotEmpty($explanation);
        $this->assertStringContainsString('so', strtolower($explanation));
    }

    public function testGetConjunctionTypeReturnsSo(): void
    {
        $this->assertEquals(Conjunction::SO, $this->rule->getConjunctionType());
    }
}
