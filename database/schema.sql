-- Sentence pairs table (Doctrine ORM)
CREATE TABLE sentence_pairs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_part VARCHAR(255) NOT NULL,
    second_part VARCHAR(255) NOT NULL,
    correct_answer ENUM('and', 'but', 'so') NOT NULL,
    difficulty_level INT NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_difficulty (difficulty_level),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Game sessions table (Raw PDO - simple, fast)
CREATE TABLE game_sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    session_token VARCHAR(64) NOT NULL UNIQUE,
    total_questions INT NOT NULL DEFAULT 0,
    correct_answers INT NOT NULL DEFAULT 0,
    started_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_activity TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_token (session_token),
    INDEX idx_last_activity (last_activity)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Session answers table (Raw PDO - high write volume)
CREATE TABLE session_answers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    session_id INT NOT NULL,
    pair_id INT NOT NULL,
    user_choice ENUM('and', 'but', 'so') NOT NULL,
    was_correct BOOLEAN NOT NULL,
    response_time_ms INT,
    answered_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (session_id) REFERENCES game_sessions(id) ON DELETE CASCADE,
    FOREIGN KEY (pair_id) REFERENCES sentence_pairs(id) ON DELETE CASCADE,
    INDEX idx_session (session_id),
    INDEX idx_answered (answered_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
