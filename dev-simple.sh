#!/bin/bash

echo "🚀 Starting PHP development server..."
echo "📍 Server: http://localhost:8000"
echo "🎮 Game: http://localhost:8000/game.html"
echo ""
echo "Press Ctrl+C to stop"

cd public && php -S localhost:8000
