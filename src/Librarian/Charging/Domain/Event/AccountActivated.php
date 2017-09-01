<?php

namespace Librarian\Charging\Domain\Event;


use Money\Currency;
use Money\Money;
use Prooph\EventSourcing\AggregateChanged;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class AccountActivated extends AggregateChanged
{
    public static function create(UuidInterface $id)
    {
        return static::occur(
            (string)$id,
            []
        );
    }
}