<?php

use DI\ContainerBuilder;
use Conjunction\Repository\SentencePairRepositoryInterface;
use Conjunction\Repository\SentencePairRepository;
use Conjunction\Repository\GameSessionRepositoryInterface;
use Conjunction\Repository\GameSessionRepository;
use Conjunction\Factory\SentencePairFactoryInterface;
use Conjunction\Factory\DatabasePairFactory;
use Conjunction\Service\FeedbackGenerator;
use Conjunction\Service\SessionManager;
use Conjunction\Service\ConjunctionChecker;
use Conjunction\Strategy\Rule;
use Doctrine\ORM\EntityManager;
use Dotenv\Dotenv;

use function DI\get;

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
        return $em->getRepository(\Conjunction\Entity\SentencePair::class);
    },

    GameSessionRepositoryInterface::class => DI\autowire(GameSessionRepository::class),

    // Factory interface binding
    SentencePairFactoryInterface::class => DI\autowire(DatabasePairFactory::class),

    // Services with dependency injection
    FeedbackGenerator::class => function () {
        return new FeedbackGenerator(
            $_ENV['OLLAMA_HOST'] ?? 'http://localhost:11434',
            $_ENV['OLLAMA_MODEL'] ?? 'llama3.3:70b'
        );
    },

    SessionManager::class => DI\autowire(SessionManager::class),

    // ConjunctionChecker with strategy pattern rules
    'rule.and' => fn () => new Rule(Conjunction::AND),
    'rule.but' => fn () => new Rule(Conjunction::BUT),
    'rule.so' => fn () => new Rule(Conjunction::SO),

    ConjunctionChecker::class => DI\autowire()
        ->constructorParameter('rules', [
            DI\get('rule.and'),
            DI\get('rule.but'),
            DI\get('rule.so'),
        ]),
]);

return $containerBuilder->build();
