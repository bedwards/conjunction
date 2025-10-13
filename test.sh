#!/bin/bash

echo "🧪 Running PHPUnit tests..."
echo ""

./vendor/bin/phpunit --colors=always --testdox

echo ""
echo "Tests complete!"
