```
User Answer 
  ↓
API (api.php)
  ↓
ConjunctionChecker::check()
  ↓
SessionManager::recordAnswer()
  ↓
├─→ GameSessionRepository::findByToken()  // Load from DB
│   ↓
├─→ GameSession::recordAnswer()           // Update in-memory
│   ↓
├─→ GameSessionRepository::update()       // Persist back to DB
│   ↓
└─→ GameSessionRepository::recordAnswer() // Log individual answer
```
