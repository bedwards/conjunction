<?php

namespace Conjunction\Tests\Unit;

use Conjunction\Strategy\Rule;
use Conjunction\Entity\SentencePair;
use Conjunction\Entity\Conjunction;
use PHPUnit\Framework\TestCase;

class SoRuleTest extends TestCase
{
    private Rule $rule;

    #[\Override]
    protected function setUp(): void
    {
        $this->rule = new Rule(Conjunction::SO);
    }

    public function testAppliesReturnsTrueWhenCorrect(): void
    {
        $result = $this->rule->applies(Conjunction::SO);

        $this->assertTrue($result);
    }

    public function testAppliesReturnsFalseWhenIncorrect(): void
    {
        $result = $this->rule->applies(Conjunction::BUT);

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
        $this->assertEquals(Conjunction::SO, $this->rule->getConjunction());
    }
}
