# 🚂 Conjunction Junction

[![Build Status](https://github.com/bedwards/conjunction/actions/workflows/check.yml/badge.svg?branch=main)](https://github.com/bedwards/conjunction/actions/workflows/check.yml?query=branch:main)
[![codecov](https://codecov.io/gh/bedwards/conjunction/branch/main/graph/badge.svg)](https://codecov.io/gh/bedwards/conjunction)

> *"And, But, So - what's your function?"*

An educational web game that teaches kids (ages 7-10) proper conjunction usage through interactive sentence completion with **real-time LLM feedback**.

<video src="https://github.com/user-attachments/assets/57f1960e-8104-4814-9287-697a67647763" width="600" controls></video>

## Run it

Set up ollama, and pull down `phi3:mini`

```bash
./scripts/setup.sh
docker-compose up -d
./scripts/dev-simple.sh
open http://localhost:1729/game.html
```

## Tech Stack

- **PHP 8.1+** with enums, attributes, constructor property promotion
- **Doctrine ORM** for sentence pairs
- **Raw PDO** for game sessions (demonstrates knowing when NOT to use ORM)
- **Ollama + Llama 3.2** for LLM feedback
- **MySQL 8.0** in Docker
- **PHP-DI** for dependency injection
- **PHPUnit** for testing

## Project Structure

```
.
├── src/              # Application source code
├── tests/            # PHPUnit tests
├── public/           # Web root (game.html, assets)
├── config/           # DI container, Doctrine config
├── database/         # SQL schema and seeds
├── scripts/          # Development and utility scripts
├── .github/          # GitHub Actions workflows
├── composer.json     # PHP dependencies
├── phpunit.xml       # PHPUnit configuration
├── psalm.xml         # Static analysis config
└── docker-compose.yml
```

## Development Scripts

```bash
# Setup project (first time)
./scripts/setup.sh

# Run tests
./scripts/test.sh

# Run all checks (tests + static analysis)
./scripts/check.sh

# Start development server
./scripts/dev-simple.sh

# Debug database connection
php scripts/db.php
```

## Architecture (The Real Goal Here)

This project demonstrates **professional OOP architecture** in PHP:

### 🎯 Strategy Pattern
`Rule` implementations for each conjunction (and, but, so) - each knows its explanation.

### 🏭 Factory Pattern
`DatabasePairFactory` creates sentence pairs. Could add `LLMPairFactory` later.

### 📦 Repository Pattern
- `SentencePairRepository` (Doctrine) - complex queries
- `GameSessionRepository` (PDO) - fast CRUD operations

### 🎨 Service Layer
- `ConjunctionChecker` - orchestrates game logic
- `FeedbackGenerator` - talks to Ollama LLM
- `SessionManager` - tracks player progress

### 💉 Dependency Injection
Everything gets dependencies via constructor. No `new` in business logic!

### SOLID Principles
Every class demonstrates specific SOLID principles (see comments in code).
