<?php

namespace Librarian\Charging\Domain;


use Ramsey\Uuid\UuidInterface;

interface AccountRepository
{
    public function get(UuidInterface $id): Account;
    public function save(Account $account);
}