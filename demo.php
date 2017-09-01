<?php

use Librarian\Charging\Domain\Account;

require_once __DIR__ . '/vendor/autoload.php';

$id = \Ramsey\Uuid\Uuid::uuid4();
$currency = new \Money\Currency('PLN');
$account = Account::create($id, $currency);

$account->discharge(\Money\Money::PLN(150));
$account->charge(\Money\Money::PLN(200));
$account->cancelDebt();
$account->deactivate();
$account->activate();

dump($account);
dump($account->getBalance());