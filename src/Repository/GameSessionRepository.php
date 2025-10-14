<?php

namespace Conjunction\Repository;

use Conjunction\Entity\GameSession;
use PDO;

/**
 * Raw PDO implementation for game sessions (fast, simple operations)
 * SOLID Principles:
 * - SRP: Only responsible for session data access
 * - DIP: Implements interface, could swap for Redis/Memcached later
 *
 * @extends EntityRepository<GameSession>
 */
class GameSessionRepository implements GameSessionRepositoryInterface
{
    public function __construct(
        private PDO $pdo
    ) {
    }

    #[\Override]
    public function create(string $sessionToken): GameSession
    {
        $token = bin2hex(random_bytes(32));
        $sql = 'INSERT INTO game_sessions (session_token) VALUES (?)';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$token]);
        return new GameSession($this->pdo->lastInsertId(), $token);
    }

    #[\Override]
    public function findByToken(string $token): ?GameSession
    {
        $sql = 'SELECT * FROM game_sessions WHERE session_token = ?';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$token]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }
        return new GameSession(
            (int)$row['id'],
            $row['session_token'],
            (int)$row['total_questions'],
            (int)$row['correct_answers'],
            new \DateTime($row['started_at']),
            new \DateTime($row['last_activity'])
        );
    }

    #[\Override]
    public function recordAnswer(
        int $sessionId,
        int $pairId,
        string $userChoice,
        bool $wasCorrect,
        int $responseTimeMs
    ): void {

        $this->pdo->beginTransaction();

        try {
            // insert into session_answers
            $sql = 'INSERT INTO session_answers
                    (session_id, pair_id, user_choice, was_correct, response_time_ms)
                    VALUES (?, ?, ?, ?, ?)';
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$sessionId, $pairId, $userChoice, (int)$wasCorrect,
                            $responseTimeMs]);

            // update game_sessions
            $sql = 'UPDATE game_sessions
                    SET total_questions = total_questions + 1,
                        correct_answers = correct_answers + ?
                    WHERE id = ?';
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([(int)$wasCorrect, $sessionId]);
            $this->pdo->commit();

        } catch (\Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }
}
