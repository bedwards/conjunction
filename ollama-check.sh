#!/bin/bash

echo "🤖 Checking Ollama status..."

if curl -s http://localhost:11434/api/tags > /dev/null; then
    echo "✅ Ollama is running"
    echo ""
    echo "Installed models:"
    curl -s http://localhost:11434/api/tags | grep -o '"name":"[^"]*"' | cut -d'"' -f4
else
    echo "❌ Ollama is not running"
    echo ""
    echo "To start Ollama:"
    echo "1. Install from https://ollama.ai"
    echo "2. Run: ollama serve"
    echo "3. Pull model: ollama pull llama3.2:3b"
fi
