<?php

namespace Conjunction\Tests\Unit;

use Conjunction\Strategy\Rule;
use Conjunction\Entity\SentencePair;
use Conjunction\Entity\Conjunction;
use PHPUnit\Framework\TestCase;

class AndRuleTest extends TestCase
{
    private Rule $rule;

    #[\Override]
    protected function setUp(): void
    {
        $this->rule = new Rule(Conjunction::AND);
    }

    /**
     * @group work
     */
    public function testAppliesReturnsTrueWhenCorrect(): void
    {
        $result = $this->rule->applies(Conjunction::AND);

        $this->assertTrue($result, 'Should return true when choice matches correct answer');
    }

    /**
     * @group work
     */
    public function testAppliesReturnsFalseWhenIncorrect(): void
    {
        $result = $this->rule->applies(Conjunction::SO);

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
    public function testGetConjunctionReturnsAnd(): void
    {
        $this->assertEquals(Conjunction::AND, $this->rule->getConjunction());
    }
}
