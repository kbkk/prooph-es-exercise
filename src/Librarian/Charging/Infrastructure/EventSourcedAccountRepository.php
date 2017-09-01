<?php

namespace Librarian\Charging\Infrastructure;


use Librarian\Charging\Domain\Account;
use Librarian\Charging\Domain\AccountRepository;
use Prooph\EventSourcing\Aggregate\AggregateRepository;
use Ramsey\Uuid\UuidInterface;

class EventSourcedAccountRepository implements AccountRepository
{
    /**
     * @var AggregateRepository
     */
    private $aggregateRepository;

    /**
     * EventSourcedAccountRepository constructor.
     * @param AggregateRepository $aggregateRepository
     */
    public function __construct(AggregateRepository $aggregateRepository)
    {
        $this->aggregateRepository = $aggregateRepository;
    }

    public function get(UuidInterface $id): Account
    {
        return $this->aggregateRepository->getAggregateRoot($id->toString());
    }

    public function save(Account $account)
    {
        $this->aggregateRepository->saveAggregateRoot($account);
    }
}