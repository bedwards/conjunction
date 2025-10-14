<?php

namespace Conjunction\Tests\Unit;

use Conjunction\Entity\Verdict;
use Conjunction\Entity\VerdictType;
use PHPUnit\Framework\TestCase;

class VerdictTest extends TestCase
{
    /**
     * @group work
     */
    public function testIsCorrectReturnsTrueForCorrectVerdict(): void
    {
        $verdict = new Verdict(VerdictType::CORRECT, 'Great job!');

        $this->assertTrue($verdict->isCorrect());
    }

    /**
     * @group work
     */
    public function testIsCorrectReturnsFalseForWrongVerdict(): void
    {
        $verdict = new Verdict(VerdictType::WRONG, 'Not quite.');

        $this->assertFalse($verdict->isCorrect());
    }

    /**
     * @group work
     */
    public function testIsCorrectReturnsFalseForOkayVerdict(): void
    {
        $verdict = new Verdict(VerdictType::OKAY, 'That works too.');

        $this->assertFalse($verdict->isCorrect());
    }

    /**
     * @group work
     */
    public function testGetColorClassReturnsCorrectClassForCorrect(): void
    {
        $verdict = new Verdict(VerdictType::CORRECT, 'Great!');

        $this->assertEquals('verdict-correct', $verdict->getColorClass());
    }

    /**
     * @group work
     */
    public function testGetColorClassReturnsWrongClassForWrong(): void
    {
        $verdict = new Verdict(VerdictType::WRONG, 'Nope.');

        $this->assertEquals('verdict-wrong', $verdict->getColorClass());
    }

    /**
     * @group work
     */
    public function testGetColorClassReturnsOkayClassForOkay(): void
    {
        $verdict = new Verdict(VerdictType::OKAY, 'Close.');

        $this->assertEquals('verdict-okay', $verdict->getColorClass());
    }

    /**
     * @group work
     */
    public function testToArrayIncludesAllFields(): void
    {
        $verdict = new Verdict(VerdictType::CORRECT, 'Excellent work!');

        $array = $verdict->toArray();

        $this->assertIsArray($array);
        $this->assertArrayHasKey('type', $array);
        $this->assertArrayHasKey('explanation', $array);
        $this->assertArrayHasKey('color_class', $array);
        $this->assertArrayHasKey('is_correct', $array);
    }

    /**
     * @group work
     */
    public function testToArrayHasCorrectValuesForCorrectVerdict(): void
    {
        $verdict = new Verdict(VerdictType::CORRECT, 'Nice!');

        $array = $verdict->toArray();

        $this->assertEquals('correct', $array['type']);
        $this->assertEquals('Nice!', $array['explanation']);
        $this->assertEquals('verdict-correct', $array['color_class']);
        $this->assertTrue($array['is_correct']);
    }

    /**
     * @group work
     */
    public function testToArrayHasCorrectValuesForWrongVerdict(): void
    {
        $verdict = new Verdict(VerdictType::WRONG, 'Try again.');

        $array = $verdict->toArray();

        $this->assertEquals('wrong', $array['type']);
        $this->assertEquals('Try again.', $array['explanation']);
        $this->assertEquals('verdict-wrong', $array['color_class']);
        $this->assertFalse($array['is_correct']);
    }
}
