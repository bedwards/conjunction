[![codecov](https://codecov.io/gh/your-username/conjunction/branch/main/graph/badge.svg?token=YOUR_CODECOV_TOKEN)](https://codecov.io/gh/your-username/conjunction)

# 🚂 Conjunction Junction

> *"And, But, So - what's your function?"*

An educational web game that teaches kids (ages 7-10) proper conjunction usage through interactive sentence completion with **real-time LLM feedback**. Also happens to be a love letter to SOLID principles and clean PHP architecture.

## What's This About?

Kids see sentence fragments like:

```
"I was tired ___ I went to bed"
[and] [but] [so]
```

They click their choice, and a local LLM (Ollama) instantly tells them:
- ✅ "Great job! So shows what happened because you were tired!" (green screen)
- ❌ "Not quite. And is for adding things, but here we need cause and effect." (red screen)
- 🟡 "That works, but 'so' would be even better!" (yellow screen)

## Tech Stack

- **PHP 8.1+** with all the modern goodies (enums, attributes, constructor property promotion)
- **Doctrine ORM** for sentence pairs (because relationships matter)
- **Raw PDO** for game sessions (because sometimes you just need SPEED)
- **Ollama + Llama 3.2** for that sweet, sweet LLM feedback
- **MySQL 8.0** in Docker (because who wants to install MySQL?)
- **PHP-DI** for dependency injection (no `new` in business logic!)
- **PHPUnit** for TDD (RED → GREEN → REFACTOR, baby)

## OOP Patterns (The Real Star of the Show)

This isn't just a game - it's a **portfolio piece** demonstrating:

### 🎯 Strategy Pattern
Three `Rule` instances (And, But, So) - each knows its conjunction and explanation. Swap 'em out, extend 'em, no problem.

### 🏭 Factory Pattern
`DatabasePairFactory` creates sentence pairs. Want to add an `LLMPairFactory` that generates new sentences? Just implement the interface.

### 📦 Repository Pattern
- `SentencePairRepository` (Doctrine) - complex queries, relationships
- `GameSessionRepository` (PDO) - fast reads/writes, no ORM overhead

### 🎨 Service Layer
- `ConjunctionChecker` - orchestrates the game logic
- `FeedbackGenerator` - talks to Ollama
- `SessionManager` - tracks player progress

### 💉 Dependency Injection
Everything gets its dependencies via constructor. Testable, swappable, chef's kiss.

### SOLID Compliance
Every class has comments showing which principle it demonstrates. This is pedagogical code!

## Quick Start

```bash
# 1. Make scripts executable and setup everything
chmod +x *.sh
./setup.sh

# 2. Pull the Ollama model (do this while waiting)
ollama pull llama3.2:3b

# 3. Run tests (they should all pass if you implemented everything!)
./test.sh

# 4. Fire up the dev server
./dev-simple.sh

# 5. Play the game!
open http://localhost:8000/game.html
```

## TDD Workflow

This project was built **test-first**. Here's the vibe:

```bash
# Run all tests
./test.sh

# Run specific test file
./test.sh --filter SentencePairTest

# Run single test method
./test.sh --filter testIsCorrectChoiceReturnsTrue

# Watch mode (if you have fswatch)
fswatch -o src/ | xargs -n1 -I{} ./test.sh
```

### Implementation Order (Suggested)

1. **Entities** - `Conjunction` enum, `SentencePair`, `GameSession`, `Verdict`
2. **Strategy** - `Rule` class with three singletons
3. **Repositories** - PDO first (simpler), then Doctrine
4. **Services** - `SessionManager`, then `FeedbackGenerator`, then `ConjunctionChecker`
5. **API** - Wire it all up in `public/api.php`

Each step: RED (test fails) → GREEN (make it pass) → REFACTOR (make it pretty)

## Project Structure

```
conjunction/
├── config/          # DI container, Doctrine, DB config
├── database/        # Schema + seeds (20 starter sentences)
├── docker/          # MySQL initialization
├── public/          # Web entry point + game UI
├── src/
│   ├── Entity/      # Domain models
│   ├── Repository/  # Data access layer
│   ├── Service/     # Business logic
│   ├── Strategy/    # Conjunction rules
│   └── Factory/     # Object creation
└── tests/           # PHPUnit tests (fully implemented!)
```

## Architecture Decisions

### Why Doctrine AND PDO?

**Doctrine** for `sentence_pairs`:
- Complex queries by difficulty level
- Future relationships (if we add categories, authors, etc.)
- Demonstrates ORM proficiency

**PDO** for `game_sessions` and `session_answers`:
- High write volume (every answer recorded)
- Simple CRUD operations
- Shows you know when NOT to use an ORM

### Why Singleton Rules?

Three `Rule` instances created once in the DI container:
- No state, so no reason to recreate
- Same instance reused everywhere
- Demonstrates proper singleton scope in DI

### Why Local LLM?

- **Fast**: Llama 3.2 3B responds in < 2 seconds
- **Free**: No API costs
- **Private**: Kids' data never leaves your machine
- **Cool**: Shows you can integrate AI without vendor lock-in

## The Seed Data

20 pre-written sentence pairs across 3 difficulty levels:

**Level 1 (Easy)**: "I was tired ___ I went to bed" (so)
**Level 2 (Medium)**: "She studied hard ___ she got an A" (so)
**Level 3 (Hard)**: "The movie was long ___ it was interesting" (but)

Future enhancement: Use Ollama to generate infinite new pairs!

## API Endpoints

```bash
POST /api.php?action=create_session
# Returns: {session_token: "abc123..."}

GET /api.php?action=next&token=abc123
# Returns: {pair: {...}, session: {...}}

POST /api.php?action=check
# Body: {session_token, pair_id, choice, response_time_ms}
# Returns: {verdict: {...}, session: {...}}
```

## Environment Variables

Copy `.env.example` to `.env`:

```env
DB_HOST=127.0.0.1
DB_PORT=3307
DB_NAME=conjunction_db
DB_USER=conjuser
DB_PASS=conjpass

OLLAMA_HOST=http://localhost:11434
OLLAMA_MODEL=llama3.2:3b
```

## Troubleshooting

### "Connection refused" to MySQL
```bash
docker-compose ps  # Check if running
docker-compose up -d  # Start it
```

### "Ollama not responding"
```bash
./ollama-check.sh  # Verify status
ollama serve  # Start Ollama
ollama pull llama3.2:3b  # Download model
```

### "Class not found" errors
```bash
composer dump-autoload  # Regenerate autoloader
```

### Tests failing?
Good! That means you haven't implemented the code yet. That's TDD, baby. Read the test, understand what it expects, then make it pass.

## What's Missing (Future Features)

- [ ] Progressive difficulty (generate harder sentences after 5 correct)
- [ ] LLMPairFactory (AI-generated sentences)
- [ ] Leaderboard (best accuracy)
- [ ] Sound effects (kids love beeps and boops)
- [ ] More conjunctions (or, nor, for, yet)
- [ ] Admin panel to review generated sentences
- [ ] Export session data as CSV

## License

MIT - Do whatever you want with it! Teach kids. Show employers. Remix it. Just have fun.

## Credits

Built with ☕ and 🎵 (probably Schoolhouse Rock on repeat)

Demonstrates that you can write clean, professional OOP architecture while building something actually useful and fun.

---

**Made with**: PHP 8.1, Doctrine ORM, MySQL, Ollama, and an unhealthy obsession with SOLID principles.

**Built for**: Job applications, teaching kids grammar, and proving that PHP can be elegant AF.

🚂 *Conjunction Junction, what's your function?*
