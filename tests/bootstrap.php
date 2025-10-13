<?php

require_once __DIR__ . '/../vendor/autoload.php';

// Load test environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->safeLoad();
