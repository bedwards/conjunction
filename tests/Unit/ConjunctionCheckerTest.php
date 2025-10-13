<?php

namespace Conjunction\Tests\Unit;

use Conjunction\Service\ConjunctionChecker;
use Conjunction\Service\FeedbackGenerator;
use Conjunction\Entity\SentencePair;
use Conjunction\Entity\Conjunction;
use Conjunction\Entity\Verdict;
use Conjunction\Entity\VerdictType;
use Conjunction\Repository\GameSessionRepositoryInterface;
use Conjunction\Strategy\Rule;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class ConjunctionCheckerTest extends TestCase
{
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

    public function testCorrectAnswerReturnsCorrectVerdict(): void
    {
        $pair = new SentencePair(
            'I was tired',
            'I went to bed',
            Conjunction::SO,
            1
        );

        $expectedVerdict = new Verdict(
            VerdictType::CORRECT,
            'Great job! So shows what happened because you were tired!'
        );

        $this->mockFeedbackGenerator
            ->expects($this->once())
            ->method('generate')
            ->with($pair, Conjunction::SO, true)
            ->willReturn($expectedVerdict);

        $this->mockSessionRepo
            ->expects($this->once())
            ->method('recordAnswer')
            ->with('test-token', $this->anything(), 'so', true, 1000);

        $result = $this->checker->check($pair, Conjunction::SO, 'test-token', 1000);

        $this->assertInstanceOf(Verdict::class, $result);
        $this->assertEquals(VerdictType::CORRECT, $result->getType());
    }

    public function testWrongAnswerReturnsWrongVerdict(): void
    {
        $pair = new SentencePair(
            'I was tired',
            'I went to bed',
            Conjunction::SO,
            1
        );

        $expectedVerdict = new Verdict(
            VerdictType::WRONG,
            'Not quite. And is for adding things, but here we need to show cause and effect.'
        );

        $this->mockFeedbackGenerator
            ->expects($this->once())
            ->method('generate')
            ->with($pair, Conjunction::AND, false)
            ->willReturn($expectedVerdict);

        $this->mockSessionRepo
            ->expects($this->once())
            ->method('recordAnswer')
            ->with('test-token', $this->anything(), 'and', false, 1500);

        $result = $this->checker->check($pair, Conjunction::AND, 'test-token', 1500);

        $this->assertInstanceOf(Verdict::class, $result);
        $this->assertEquals(VerdictType::WRONG, $result->getType());
    }

    public function testSessionIsUpdatedAfterCheck(): void
    {
        $pair = new SentencePair(
            'I was tired',
            'I went to bed',
            Conjunction::SO,
            1
        );

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
