<?php

use Librarian\Charging\Domain\Account;

require_once __DIR__ . '/vendor/autoload.php';

$id = \Ramsey\Uuid\Uuid::uuid4();
$currency = new \Money\Currency('PLN');
$account = Account::create($id, $currency);

for ($i = 0; $i < 10; $i++)
{
    $account->charge(\Money\Money::PLN(10 * $i));
}

dump($account);