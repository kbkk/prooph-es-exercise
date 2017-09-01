<?php

use Prooph\Common\Messaging\FQCNMessageFactory;
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

if(!$eventStore->hasStream($streamName))
    $eventStore->create($singleStream);

