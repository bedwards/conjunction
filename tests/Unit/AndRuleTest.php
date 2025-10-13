<?php

namespace Conjunction\Tests\Unit;

use Conjunction\Strategy\AndRule;
use Conjunction\Entity\SentencePair;
use Conjunction\Entity\Conjunction;
use PHPUnit\Framework\TestCase;

class AndRuleTest extends TestCase
{
    private AndRule $rule;

    protected function setUp(): void
    {
        $this->rule = new AndRule();
    }

    /**
     * @group work
     */
    public function testAppliesReturnsTrueWhenCorrect(): void
    {
        $pair = new SentencePair(
            'I had a sandwich',
            'I had chips',
            Conjunction::AND,
            1
        );

        $result = $this->rule->applies($pair, Conjunction::AND);

        $this->assertTrue($result, 'Should return true when choice matches correct answer');
    }

    /**
     * @group work
     */
    public function testAppliesReturnsFalseWhenIncorrect(): void
    {
        $pair = new SentencePair(
            'I was tired',
            'I went to bed',
            Conjunction::SO,
            1
        );

        $result = $this->rule->applies($pair, Conjunction::AND);

        $this->assertFalse($result, 'Should return false when choice does not match');
    }

    /**
     * @group work
     */
    public function testGetExplanationReturnsString(): void
    {
        $explanation = $this->rule->getExplanation();

        $this->assertIsString($explanation);
        $this->assertNotEmpty($explanation);
        $this->assertStringContainsString('and', strtolower($explanation));
    }

    /**
     * @group work
     */
    public function testGetConjunctionTypeReturnsAnd(): void
    {
        $this->assertEquals(Conjunction::AND, $this->rule->getConjunctionType());
    }
}
