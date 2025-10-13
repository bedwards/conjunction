<?php

namespace Conjunction\Tests\Unit;

use Conjunction\Service\SessionManager;
use Conjunction\Entity\GameSession;
use Conjunction\Repository\GameSessionRepositoryInterface;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class SessionManagerTest extends TestCase
{
    private SessionManager $sessionManager;
    private GameSessionRepositoryInterface|MockObject $mockRepository;

    #[\Override]
    protected function setUp(): void
    {
        $this->mockRepository = $this->createMock(GameSessionRepositoryInterface::class);
        $this->sessionManager = new SessionManager($this->mockRepository);
    }

    public function testCreateSessionReturnsUniqueToken(): void
    {
        $mockSession = new GameSession(1, 'test-token-123');

        $this->mockRepository
            ->expects($this->once())
            ->method('create')
            ->with($this->isType('string'))
            ->willReturn($mockSession);

        $token = $this->sessionManager->createSession();

        $this->assertIsString($token);
        $this->assertNotEmpty($token);
        $this->assertEquals('test-token-123', $token);
    }

    public function testRecordAnswerUpdatesSession(): void
    {
        $sessionToken = 'test-token-456';
        $mockSession = new GameSession(1, $sessionToken);

        $this->mockRepository
            ->expects($this->once())
            ->method('findByToken')
            ->with($sessionToken)
            ->willReturn($mockSession);

        $this->mockRepository
            ->expects($this->once())
            ->method('update')
            ->with($this->isInstanceOf(GameSession::class));

        $this->mockRepository
            ->expects($this->once())
            ->method('recordAnswer')
            ->with(1, 5, 'so', true, 1500);

        $this->sessionManager->recordAnswer($sessionToken, 5, 'so', true, 1500);
    }

    public function testGetSessionReturnsCorrectSession(): void
    {
        $sessionToken = 'test-token-789';
        $mockSession = new GameSession(1, $sessionToken, 5, 3);

        $this->mockRepository
            ->expects($this->once())
            ->method('findByToken')
            ->with($sessionToken)
            ->willReturn($mockSession);

        $result = $this->sessionManager->getSession($sessionToken);

        $this->assertInstanceOf(GameSession::class, $result);
        $this->assertEquals($sessionToken, $result->getSessionToken());
    }

    public function testGetSessionReturnsNullWhenNotFound(): void
    {
        $this->mockRepository
            ->expects($this->once())
            ->method('findByToken')
            ->with('nonexistent-token')
            ->willReturn(null);

        $result = $this->sessionManager->getSession('nonexistent-token');

        $this->assertNull($result);
    }
}
