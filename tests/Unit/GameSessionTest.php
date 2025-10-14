<?php

namespace Conjunction\Tests\Unit;

use Conjunction\Entity\GameSession;
use PHPUnit\Framework\TestCase;

class GameSessionTest extends TestCase
{
    /**
     * @group work
     */
    public function testRecordAnswerIncrementsQuestionsWhenCorrect(): void
    {
        $session = new GameSession(1, 'token123', 0, 0);

        $session->recordAnswer(true);

        $this->assertEquals(1, $session->getTotalQuestions());
        $this->assertEquals(1, $session->getCorrectAnswers());
    }

    /**
     * @group work
     */
    public function testRecordAnswerIncrementsQuestionsWhenWrong(): void
    {
        $session = new GameSession(1, 'token123', 0, 0);

        $session->recordAnswer(false);

        $this->assertEquals(1, $session->getTotalQuestions());
        $this->assertEquals(0, $session->getCorrectAnswers());
    }

    /**
     * @group work
     */
    public function testRecordAnswerTracksMultipleAnswers(): void
    {
        $session = new GameSession(1, 'token123', 0, 0);

        $session->recordAnswer(true);
        $session->recordAnswer(false);
        $session->recordAnswer(true);

        $this->assertEquals(3, $session->getTotalQuestions());
        $this->assertEquals(2, $session->getCorrectAnswers());
    }

    /**
     * @group work
     */
    public function testGetAccuracyReturnsZeroForNoQuestions(): void
    {
        $session = new GameSession(1, 'token123', 0, 0);

        $this->assertEquals(0.0, $session->getAccuracy());
    }

    /**
     * @group work
     */
    public function testGetAccuracyReturnsCorrectPercentage(): void
    {
        $session = new GameSession(1, 'token123', 4, 3);

        $this->assertEquals(0.75, $session->getAccuracy());
    }

    /**
     * @group work
     */
    public function testGetAccuracyReturnsOneForPerfectScore(): void
    {
        $session = new GameSession(1, 'token123', 5, 5);

        $this->assertEquals(1.0, $session->getAccuracy());
    }

    /**
     * @group work
     */
    public function testGetAccuracyReturnsZeroForAllWrong(): void
    {
        $session = new GameSession(1, 'token123', 5, 0);

        $this->assertEquals(0.0, $session->getAccuracy());
    }

    /**
     * @group work
     */
    public function testGetStartedAtReturnsDateTime(): void
    {
        $startedAt = new \DateTime('2024-01-01 12:00:00');
        $session = new GameSession(1, 'token123', 0, 0, $startedAt);

        $result = $session->getStartedAt();

        $this->assertInstanceOf(\DateTimeInterface::class, $result);
        $this->assertEquals('2024-01-01 12:00:00', $result->format('Y-m-d H:i:s'));
    }

    /**
     * @group work
     */
    public function testGetStartedAtDefaultsToNow(): void
    {
        $before = new \DateTime();
        $session = new GameSession(1, 'token123', 0, 0);
        $after = new \DateTime();

        $startedAt = $session->getStartedAt();

        $this->assertInstanceOf(\DateTimeInterface::class, $startedAt);
        $this->assertGreaterThanOrEqual($before->getTimestamp(), $startedAt->getTimestamp());
        $this->assertLessThanOrEqual($after->getTimestamp(), $startedAt->getTimestamp());
    }

    /**
     * @group work
     */
    public function testGetLastActivityReturnsDateTime(): void
    {
        $lastActivity = new \DateTime('2024-01-01 12:30:00');
        $session = new GameSession(1, 'token123', 0, 0, null, $lastActivity);

        $result = $session->getLastActivity();

        $this->assertInstanceOf(\DateTimeInterface::class, $result);
        $this->assertEquals('2024-01-01 12:30:00', $result->format('Y-m-d H:i:s'));
    }

    /**
     * @group work
     */
    public function testGetLastActivityDefaultsToNow(): void
    {
        $before = new \DateTime();
        $session = new GameSession(1, 'token123', 0, 0);
        $after = new \DateTime();

        $lastActivity = $session->getLastActivity();

        $this->assertInstanceOf(\DateTimeInterface::class, $lastActivity);
        $this->assertGreaterThanOrEqual($before->getTimestamp(), $lastActivity->getTimestamp());
        $this->assertLessThanOrEqual($after->getTimestamp(), $lastActivity->getTimestamp());
    }

    /**
     * @group work
     */
    public function testAccuracyUpdatesAfterRecordingAnswers(): void
    {
        $session = new GameSession(1, 'token123', 0, 0);

        $this->assertEquals(0.0, $session->getAccuracy());

        $session->recordAnswer(true);
        $this->assertEquals(1.0, $session->getAccuracy());

        $session->recordAnswer(false);
        $this->assertEquals(0.5, $session->getAccuracy());

        $session->recordAnswer(true);
        $this->assertEqualsWithDelta(0.6666, $session->getAccuracy(), 0.0001);
    }
}
