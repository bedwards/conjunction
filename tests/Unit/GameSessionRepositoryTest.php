<?php

namespace Conjunction\Tests\Unit;

use Conjunction\Repository\GameSessionRepository;
use Conjunction\Entity\GameSession;
use PHPUnit\Framework\TestCase;
use PDO;
use PDOStatement;

class GameSessionRepositoryTest extends TestCase
{
    private PDO $mockPdo;
    private GameSessionRepository $repository;

    #[\Override]
    protected function setUp(): void
    {
        $this->mockPdo = $this->createMock(PDO::class);
        $this->repository = new GameSessionRepository($this->mockPdo);
    }

    /**
     * @group work
     */
    public function testFindByTokenReturnsSessionWhenFound(): void
    {
        $mockStmt = $this->createMock(PDOStatement::class);

        $this->mockPdo
            ->expects($this->once())
            ->method('prepare')
            ->with($this->stringContains('SELECT'))
            ->willReturn($mockStmt);

        $mockStmt
            ->expects($this->once())
            ->method('execute')
            ->with(['test-token'])
            ->willReturn(true);

        $mockStmt
            ->expects($this->once())
            ->method('fetch')
            ->willReturn([
                'id' => 1,
                'session_token' => 'test-token',
                'total_questions' => 5,
                'correct_answers' => 3,
                'started_at' => '2024-01-01 12:00:00',
                'last_activity' => '2024-01-01 12:05:00'
            ]);

        $session = $this->repository->findByToken('test-token');

        $this->assertInstanceOf(GameSession::class, $session);
        $this->assertEquals(1, $session->getId());
        $this->assertEquals('test-token', $session->getSessionToken());
        $this->assertEquals(5, $session->getTotalQuestions());
        $this->assertEquals(3, $session->getCorrectAnswers());
    }

    /**
     * @group work
     */
    public function testFindByTokenReturnsNullWhenNotFound(): void
    {
        $mockStmt = $this->createMock(PDOStatement::class);

        $this->mockPdo
            ->expects($this->once())
            ->method('prepare')
            ->willReturn($mockStmt);

        $mockStmt
            ->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $mockStmt
            ->expects($this->once())
            ->method('fetch')
            ->willReturn(false);

        $session = $this->repository->findByToken('nonexistent');

        $this->assertNull($session);
    }

    /**
     * @group work
     */
    public function testRecordAnswerUpdatesSessionStats(): void
    {
        $mockInsertStmt = $this->createMock(PDOStatement::class);
        $mockUpdateStmt = $this->createMock(PDOStatement::class);

        $this->mockPdo
            ->expects($this->exactly(2))
            ->method('prepare')
            ->willReturnOnConsecutiveCalls($mockInsertStmt, $mockUpdateStmt);

        $mockInsertStmt
            ->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $mockUpdateStmt
            ->expects($this->once())
            ->method('execute')
            ->with($this->callback(function ($params) {
                // Verify session stats are being updated
                return is_array($params) && count($params) > 0;
            }))
            ->willReturn(true);

        $this->repository->recordAnswer(1, 42, 'so', true, 1500);
    }

    /**
     * @group work
     */
    public function testCreateReturnsSessionWithId(): void
    {
        // This test verifies the existing create() method works correctly
        $this->mockPdo
            ->expects($this->once())
            ->method('prepare')
            ->willReturn($mockStmt = $this->createMock(PDOStatement::class));

        $mockStmt
            ->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->mockPdo
            ->expects($this->once())
            ->method('lastInsertId')
            ->willReturn('123');

        $session = $this->repository->create('ignored-token');

        $this->assertInstanceOf(GameSession::class, $session);
        $this->assertEquals(123, $session->getId());
    }
}
