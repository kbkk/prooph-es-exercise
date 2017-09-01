<?php

use Librarian\Charging\Domain\Account;
use Librarian\Charging\Infrastructure\EventSourcedAccountRepository;
use Prooph\Common\Messaging\FQCNMessageFactory;
use Prooph\EventSourcing\Aggregate\AggregateRepository;
use Prooph\EventSourcing\Aggregate\AggregateType;
use Prooph\EventSourcing\EventStoreIntegration\AggregateTranslator;
use Prooph\EventStore\Pdo\PersistenceStrategy\MySqlSingleStreamStrategy;
use Prooph\EventStore\Stream;
use Prooph\EventStore\StreamName;

require_once __DIR__ . '/vendor/autoload.php';

$config = new \Doctrine\DBAL\Configuration();

$params = [
    'dbname' => 'es',
    'user' => 'root',
    'password' => 'root',
    'host' => '127.0.0.1',
    'port' => '3306',
    'driver' => 'pdo_mysql',
];

$conn = \Doctrine\DBAL\DriverManager::getConnection($params, $config);

$eventStore = new \Prooph\EventStore\Pdo\MySqlEventStore(
    new FQCNMessageFactory(),
    $conn->getWrappedConnection(),
    new MySqlSingleStreamStrategy()
);

$streamName = new StreamName('event_stream');
$singleStream = new Stream($streamName, new ArrayIterator());

if (!$eventStore->hasStream($streamName))
    $eventStore->create($singleStream);

$aggregateRepository = new AggregateRepository(
    $eventStore,
    AggregateType::fromAggregateRootClass(Account::class),
    new AggregateTranslator()
);

$accountRepository = new EventSourcedAccountRepository($aggregateRepository);