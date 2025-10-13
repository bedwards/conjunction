#!/bin/bash

echo "🚀 Starting PHP development server..."
echo "📍 Server: http://localhost:1729"
echo "🎮 Game: http://localhost:1729/game.html"
echo ""
echo "Press Ctrl+C to stop"

cd public && php -S localhost:1729
