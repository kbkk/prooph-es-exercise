<?php

namespace Librarian\Charging\Domain\Event;


use Money\Currency;
use Money\Money;
use Prooph\EventSourcing\AggregateChanged;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class AccountCreated extends AggregateChanged
{
    public static function create(UuidInterface $id, Currency $currency)
    {
        return static::occur(
            (string)$id,
            [
                'currency' => $currency->getCode(),
            ]
        );
    }

    public function currency(): Currency
    {
        return new Currency($this->payload['currency']);
    }

    public function id()
    {
        return Uuid::fromString($this->aggregateId());
    }
}