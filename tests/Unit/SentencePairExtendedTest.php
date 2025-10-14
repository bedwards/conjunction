<?php

namespace Conjunction\Tests\Unit;

use Conjunction\Entity\SentencePair;
use Conjunction\Entity\Conjunction;
use PHPUnit\Framework\TestCase;

/**
 * Extended tests for SentencePair focusing on the createdAt property
 *
 * NOTE: These tests will fail until you add a getCreatedAt() method to SentencePair!
 */
class SentencePairExtendedTest extends TestCase
{
    public function testCreatedAtIsSetOnConstruction(): void
    {
        $before = new \DateTime();
        $pair = new SentencePair(
            'I was tired',
            'I went to bed',
            Conjunction::SO,
            1
        );
        $after = new \DateTime();

        $createdAt = $pair->getCreatedAt();

        $this->assertInstanceOf(\DateTimeInterface::class, $createdAt);
        $this->assertGreaterThanOrEqual($before->getTimestamp(), $createdAt->getTimestamp());
        $this->assertLessThanOrEqual($after->getTimestamp(), $createdAt->getTimestamp());
    }

    public function testCreatedAtPersistsAcrossMethodCalls(): void
    {
        $pair = new SentencePair(
            'I was tired',
            'I went to bed',
            Conjunction::SO,
            1
        );

        $firstCall = $pair->getCreatedAt();
        sleep(1);
        $secondCall = $pair->getCreatedAt();

        $this->assertEquals(
            $firstCall->getTimestamp(),
            $secondCall->getTimestamp(),
            'CreatedAt should not change between calls'
        );
    }

    public function testCreatedAtIsFormattable(): void
    {
        $pair = new SentencePair(
            'I was tired',
            'I went to bed',
            Conjunction::SO,
            1
        );

        $formatted = $pair->getCreatedAt()->format('Y-m-d H:i:s');

        $this->assertMatchesRegularExpression(
            '/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/',
            $formatted,
            'CreatedAt should be formattable as standard datetime'
        );
    }

    /**
     * Test that createdAt can be used for sorting pairs by creation time
     */
    public function testMultiplePairsHaveDistinctCreatedAtTimes(): void
    {
        $pair1 = new SentencePair('A', 'B', Conjunction::AND, 1);
        usleep(1000); // 1ms delay
        $pair2 = new SentencePair('C', 'D', Conjunction::BUT, 1);
        usleep(1000);
        $pair3 = new SentencePair('E', 'F', Conjunction::SO, 1);

        // These should be in chronological order
        $this->assertLessThanOrEqual(
            $pair2->getCreatedAt()->getTimestamp(),
            $pair1->getCreatedAt()->getTimestamp()
        );
        $this->assertLessThanOrEqual(
            $pair3->getCreatedAt()->getTimestamp(),
            $pair2->getCreatedAt()->getTimestamp()
        );
    }
}
