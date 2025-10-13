#!/bin/bash

echo "🚂 Setting up Conjunction Junction..."

# Check if Docker is running
if ! docker info > /dev/null 2>&1; then
    echo "❌ Docker is not running. Please start Docker first."
    exit 1
fi

# Copy environment file
if [ ! -f .env ]; then
    cp .env.example .env
    echo "✓ Created .env file"
fi

# Install Composer dependencies
if [ ! -d vendor ]; then
    echo "📦 Installing PHP dependencies..."
    composer install
fi

# Start Docker services
echo "🐳 Starting MySQL..."
docker-compose up -d

# Wait for MySQL to be ready
echo "⏳ Waiting for MySQL to be ready..."
sleep 10

# Check if Ollama is running
echo "🤖 Checking Ollama..."
if curl -s http://localhost:11434/api/tags > /dev/null; then
    echo "✓ Ollama is running"
else
    echo "⚠️  Ollama is not running. Please start Ollama and run:"
    echo "   ollama pull llama3.2:3b"
fi

echo ""
echo "✅ Setup complete!"
echo ""
echo "Next steps:"
echo "1. Run tests: ./test.sh"
echo "2. Start dev server: ./dev-simple.sh"
echo "3. Open http://localhost:1729/game.html"
