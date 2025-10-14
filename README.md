# 🚂 Conjunction Junction


[![Build Status](https://github.com/bedwards/conjunction/actions/workflows/check.yml/badge.svg?branch=main)](https://github.com/bedwards/conjunction/actions/workflows/check.yml?query=branch:main)
[![codecov](https://codecov.io/gh/bedwards/conjunction/branch/main/graph/badge.svg)](https://codecov.io/gh/bedwards/conjunction)

> *"And, But, So - what's your function?"*

An educational web game that teaches kids (ages 7-10) proper conjunction usage through interactive sentence completion with **real-time LLM feedback**.

## Current Status: 🚧 In Development

This is a **partially implemented** project. The architecture is solid, but several key components need implementation to make it fully functional.

## What Works Right Now

```bash
./setup.sh
docker-compose up -d
./dev-simple.sh
open http://localhost:1729/game.html
```

## What You'll See

**Frontend (game.html)**: ✅ Fully implemented
- Beautiful gradient UI that changes color based on verdict
- Three conjunction buttons (and, but, so)
- Real-time feedback display
- Session statistics tracking

**API (api.php)**: ✅ Structure complete, awaits implementations
- Creates sessions via `POST /api.php?action=create_session`
- Fetches questions via `GET /api.php?action=next&token=...`
- Checks answers via `POST /api.php?action=check`

**Backend Services**: ⚠️ Partially implemented
- Entities: ✅ Complete (`SentencePair`, `GameSession`, `Conjunction`, `Verdict`)
- Repositories: ⚠️ Needs implementation
- Services: ⚠️ Needs implementation
- Strategy: ⚠️ Needs implementation

## Tech Stack

- **PHP 8.1+** with enums, attributes, constructor property promotion
- **Doctrine ORM** for sentence pairs
- **Raw PDO** for game sessions (demonstrates knowing when NOT to use ORM)
- **Ollama + Llama 3.2** for LLM feedback
- **MySQL 8.0** in Docker
- **PHP-DI** for dependency injection
- **PHPUnit** for testing

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
