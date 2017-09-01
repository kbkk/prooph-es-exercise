<?php

namespace Librarian\Charging\Domain;


use Librarian\Charging\Domain\Event\AccountCharged;
use Librarian\Charging\Domain\Event\AccountCreated;
use Money\Currency;
use Money\Money;
use Prooph\EventSourcing\AggregateChanged;
use Prooph\EventSourcing\AggregateRoot;
use Ramsey\Uuid\UuidInterface;

class Account extends AggregateRoot
{
    /**
     * @var UuidInterface
     */
    private $id;

    /**
     * @var Money
     */
    private $balance;

    /**
     * @return Money
     */

    /**
     * @var AccountState
     */
    private $state;

    /**
     * Account constructor.
     * @param UuidInterface $id
     */
    public static function create(UuidInterface $id, Currency $currency)
    {
        $account = new self();

        $account->recordThat(
          AccountCreated::create($id, $currency)
        );

        return $account;
    }

    public function getBalance(): Money
    {
        return $this->balance;
    }

    public function charge(Money $moneyToCharge)
    {
        if (!$this->canBeCharged($moneyToCharge)) {
            throw new \DomainException();
        }

        $this->recordThat(
            AccountCharged::create($this->id, $moneyToCharge)
        );
    }

    public function discharge(Money $moneyToDischarge)
    {
        if (!$this->canBeDischarged($moneyToDischarge)) {
            throw new \DomainException();
        }
    }

    public function cancelDebt()
    {

    }

    public function activate()
    {

    }

    public function deactivate()
    {

    }

    protected function aggregateId(): string
    {
        return $this->id->toString();
    }

    /**
     * Apply given event
     * @param AggregateChanged $event
     */
    protected function apply(AggregateChanged $event): void
    {
        switch(get_class($event)) {
            case AccountCharged::class:
                /**
                 * @var $event AccountCharged
                 */
                $this->balance = $this->balance->add($event->money());
                break;

            case AccountCreated::class:
                /**
                 * @var $event AccountCreated
                 */
                $this->balance = new Money(0, $event->currency());
                $this->state = AccountState::ACTIVE();
                $this->id = $event->id();

                break;
            default:
                throw new \LogicException();
        }
    }

    private function canBeDischarged(Money $money)
    {
        return true;
    }

    private function canBeCharged(Money $money)
    {
        return true;
    }
}
