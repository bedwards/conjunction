<?php

namespace Conjunction\Tests\Unit;

use Conjunction\Strategy\Rule;
use Conjunction\Entity\SentencePair;
use Conjunction\Entity\Conjunction;
use PHPUnit\Framework\TestCase;

class ButRuleTest extends TestCase
{
    private Rule $rule;

    #[\Override]
    protected function setUp(): void
    {
        $this->rule = new Rule(Conjunction::BUT);
    }

    /**
     * @group work
     */
    public function testAppliesReturnsTrueWhenCorrect(): void
    {
        $result = $this->rule->applies(Conjunction::BUT);

        $this->assertTrue($result);
    }

    /**
     * @group work
     */
    public function testAppliesReturnsFalseWhenIncorrect(): void
    {
        $result = $this->rule->applies(Conjunction::SO);

        $this->assertFalse($result);
    }

    /**
     * @group work
     */
    public function testGetExplanationReturnsString(): void
    {
        $explanation = $this->rule->getExplanation();

        $this->assertIsString($explanation);
        $this->assertNotEmpty($explanation);
        $this->assertStringContainsString('but', strtolower($explanation));
    }

    /**
     * @group work
     */
    public function testGetConjunctionReturnsBut(): void
    {
        $this->assertEquals(Conjunction::BUT, $this->rule->getConjunction());
    }
}
