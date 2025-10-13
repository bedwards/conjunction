<?php

namespace Conjunction\Tests\Unit;

use Conjunction\Entity\SentencePair;
use Conjunction\Entity\Conjunction;
use PHPUnit\Framework\TestCase;

class SentencePairTest extends TestCase
{
    private SentencePair $pair;

    protected function setUp(): void
    {
        $this->pair = new SentencePair(
            'I was tired',
            'I went to bed',
            Conjunction::SO,
            1
        );
    }

    /**
     * @group work
     */
    public function testIsCorrectChoiceReturnsTrue(): void
    {
        $result = $this->pair->isCorrectChoice(Conjunction::SO);

        $this->assertTrue($result, 'Should return true for correct conjunction');
    }

    /**
     * @group work
     */
    public function testIsCorrectChoiceReturnsFalse(): void
    {
        $result = $this->pair->isCorrectChoice(Conjunction::AND);

        $this->assertFalse($result, 'Should return false for incorrect conjunction');
    }

    /**
     * @group work
     */
    public function testGetFullSentenceFormatsCorrectly(): void
    {
        $result = $this->pair->getFullSentence(Conjunction::SO);

        $this->assertStringContainsString('I was tired', $result);
        $this->assertStringContainsString('so', $result);
        $this->assertStringContainsString('I went to bed', $result);
    }

    /**
     * @group work
     */
    public function testGettersReturnCorrectValues(): void
    {
        $this->assertEquals('I was tired', $this->pair->getFirstPart());
        $this->assertEquals('I went to bed', $this->pair->getSecondPart());
        $this->assertEquals(Conjunction::SO, $this->pair->getCorrectAnswer());
        $this->assertEquals(1, $this->pair->getDifficultyLevel());
    }
}
