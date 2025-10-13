<?php

namespace Conjunction\Tests\Unit;

use Conjunction\Service\ConjunctionChecker;
use Conjunction\Service\FeedbackGenerator;
use Conjunction\Entity\Conjunction;
use Conjunction\Entity\GameSession;
use Conjunction\Entity\SentencePair;
use Conjunction\Entity\Verdict;
use Conjunction\Entity\VerdictType;
use Conjunction\Repository\GameSessionRepositoryInterface;
use Conjunction\Strategy\Rule;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class ConjunctionCheckerTest extends TestCase
{
    private static int $pairId = 123;
    private ConjunctionChecker $checker;
    private FeedbackGenerator|MockObject $mockFeedbackGenerator;
    private GameSessionRepositoryInterface|MockObject $mockSessionRepo;
    private array $rules;

    #[\Override]
    protected function setUp(): void
    {
        $this->mockFeedbackGenerator = $this->createMock(FeedbackGenerator::class);
        $this->mockSessionRepo = $this->createMock(GameSessionRepositoryInterface::class);

        $this->rules = [
            new Rule(Conjunction::AND),
            new Rule(Conjunction::BUT),
            new Rule(Conjunction::SO),
        ];

        $this->checker = new ConjunctionChecker(
            $this->mockFeedbackGenerator,
            $this->mockSessionRepo,
            $this->rules
        );
    }

    private function setPairId(SentencePair $pair): void
    {
        $reflection = new \ReflectionClass($pair);
        $idProperty = $reflection->getProperty('id');
        $idProperty->setValue($pair, self::$pairId);
    }

    /**
     * @group work
     */
    public function testCorrectAnswerReturnsCorrectVerdict(): void
    {
        $pair = new SentencePair(
            'I was tired',
            'I went to bed',
            Conjunction::SO,
            1
        );
        $this->setPairId($pair);

        $sessionId = 1;
        $mockSession = new GameSession($sessionId, 'test-token', 0, 0);

        $this->mockSessionRepo
                ->expects($this->once())
                ->method('findByToken')
                ->with('test-token')
                ->willReturn($mockSession);

        $expectedVerdict = new Verdict(
            VerdictType::CORRECT,
            'Great job! So shows what happened because you were tired!'
        );

        $this->mockFeedbackGenerator
            ->expects($this->once())
            ->method('generate')
            ->with($pair, Conjunction::SO, true)
            ->willReturn($expectedVerdict);

        $responseTimeMs = 1000;
        $this->mockSessionRepo
            ->expects($this->once())
            ->method('recordAnswer')
            ->with($sessionId, self::$pairId, 'so', true, $responseTimeMs);

        $result = $this->checker->check($pair, Conjunction::SO, 'test-token', $responseTimeMs);

        $this->assertInstanceOf(Verdict::class, $result);
        $this->assertEquals(VerdictType::CORRECT, $result->getType());
    }

    /**
     * @group work
     */
    public function testWrongAnswerReturnsWrongVerdict(): void
    {
        $pair = new SentencePair(
            'I was tired',
            'I went to bed',
            Conjunction::SO,  // SO this the correct answer, verify with AND below.
            1
        );
        $this->setPairId($pair);

        $sessionId = 1;
        $mockSession = new GameSession($sessionId, 'test-token', 0, 0);


        $this->mockSessionRepo
                ->expects($this->once())
                ->method('findByToken')
                ->with('test-token')
                ->willReturn($mockSession);

        $expectedVerdict = new Verdict(
            VerdictType::WRONG,
            'Not quite. And is for adding things, but here we need to show cause and effect.'
        );

        $this->mockFeedbackGenerator
            ->expects($this->once())
            ->method('generate')
            ->with($pair, Conjunction::AND, false)
            ->willReturn($expectedVerdict);

        $responseTimeMs = 1000;
        $this->mockSessionRepo
            ->expects($this->once())
            ->method('recordAnswer')
            ->with($sessionId, self::$pairId, 'and', false, $responseTimeMs);

        $result = $this->checker->check($pair, Conjunction::AND, 'test-token', $responseTimeMs);

        $this->assertInstanceOf(Verdict::class, $result);
        $this->assertEquals(VerdictType::WRONG, $result->getType());
    }

    /**
     * @group work
     */
    public function testSessionIsUpdatedAfterCheck(): void
    {
        $pair = new SentencePair(
            'I was tired',
            'I went to bed',
            Conjunction::SO,
            1
        );
        $this->setPairId($pair);

        $mockSession = new GameSession(1, 'test-token', 0, 0);

        $this->mockSessionRepo
                ->expects($this->once())
                ->method('findByToken')
                ->with('test-token')
                ->willReturn($mockSession);

        $verdict = new Verdict(VerdictType::CORRECT, 'Good job!');

        $this->mockFeedbackGenerator
            ->method('generate')
            ->willReturn($verdict);

        $this->mockSessionRepo
            ->expects($this->once())
            ->method('recordAnswer');

        $this->checker->check($pair, Conjunction::SO, 'test-token', 2000);
    }
}
