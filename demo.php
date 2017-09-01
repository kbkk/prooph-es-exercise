<?php

use Librarian\Charging\Domain\Account;
use Ramsey\Uuid\Uuid;

include 'setup.php';

/*$id = \Ramsey\Uuid\Uuid::uuid4();
$currency = new \Money\Currency('PLN');
$account = Account::create($id, $currency);

$account->discharge(\Money\Money::PLN(150));
$account->charge(\Money\Money::PLN(200));
$account->cancelDebt();
$account->deactivate();
$account->activate();

$accountRepository->save($account);*/

$account = $accountRepository->get(Uuid::fromString('cc0d454a-df90-45c6-aa6b-dcb05341ec96'));

dump($account);
dump($account->getBalance());