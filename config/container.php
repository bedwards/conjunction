<?php

use DI\ContainerBuilder;
use ConjunctionJunction\Repository\SentencePairRepositoryInterface;
use ConjunctionJunction\Repository\SentencePairRepository;
use ConjunctionJunction\Repository\GameSessionRepositoryInterface;
use ConjunctionJunction\Repository\GameSessionRepository;
use ConjunctionJunction\Factory\SentencePairFactoryInterface;
use ConjunctionJunction\Factory\DatabasePairFactory;
use ConjunctionJunction\Service\FeedbackGenerator;
use ConjunctionJunction\Service\SessionManager;
use ConjunctionJunction\Service\ConjunctionChecker;
use ConjunctionJunction\Strategy\AndRule;
use ConjunctionJunction\Strategy\ButRule;
use ConjunctionJunction\Strategy\SoRule;
use Doctrine\ORM\EntityManager;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$containerBuilder = new ContainerBuilder();

$containerBuilder->addDefinitions([

    // Doctrine EntityManager (Singleton - expensive to create)
    EntityManager::class => function () {
        return require __DIR__ . '/doctrine.php';
    },

    // PDO Connection (Singleton - single connection pool)
    PDO::class => function () {
        $dbConfig = require __DIR__ . '/database.php';

        $dsn = sprintf(
            'mysql:host=%s;port=%s;dbname=%s;charset=%s',
            $dbConfig['host'],
            $dbConfig['port'],
            $dbConfig['database'],
            $dbConfig['charset']
        );

        $pdo = new PDO($dsn, $dbConfig['username'], $dbConfig['password'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);

        return $pdo;
    },

    // Repository interfaces bound to implementations
    // DIP: High-level code depends on interfaces
    SentencePairRepositoryInterface::class => function (EntityManager $em) {
        return $em->getRepository(\ConjunctionJunction\Entity\SentencePair::class);
    },

    GameSessionRepositoryInterface::class => DI\autowire(GameSessionRepository::class),

    // Factory interface binding
    SentencePairFactoryInterface::class => DI\autowire(DatabasePairFactory::class),

    // Services with dependency injection
    FeedbackGenerator::class => function () {
        return new FeedbackGenerator(
            $_ENV['OLLAMA_HOST'] ?? 'http://localhost:11434',
            $_ENV['OLLAMA_MODEL'] ?? 'llama3.2:3b'
        );
    },

    SessionManager::class => DI\autowire(SessionManager::class),

    // ConjunctionChecker with strategy pattern rules
    ConjunctionChecker::class => function ($c) {
        return new ConjunctionChecker(
            $c->get(FeedbackGenerator::class),
            $c->get(SessionManager::class),
            [
                new AndRule(),
                new ButRule(),
                new SoRule()
            ]
        );
    },
]);

return $containerBuilder->build();
