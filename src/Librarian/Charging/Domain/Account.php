<?php

namespace Librarian\Charging\Domain;


use Librarian\Charging\Domain\Event\AccountActivated;
use Librarian\Charging\Domain\Event\AccountCharged;
use Librarian\Charging\Domain\Event\AccountCreated;
use Librarian\Charging\Domain\Event\AccountDeactivated;
use Librarian\Charging\Domain\Event\AccountDebtCanceled;
use Librarian\Charging\Domain\Event\AccountDischarged;
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

        if (!$this->state->equals(AccountState::ACTIVE())) {
            throw new \DomainException('Cannot charge an inactive account');
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

        if (!$this->state->equals(AccountState::ACTIVE())) {
            throw new \DomainException('Cannot discharge an inactive account');
        }

        $this->recordThat(
            AccountDischarged::create($this->id, $moneyToDischarge)
        );
    }

    public function cancelDebt()
    {
        if ($this->balance->isPositive()) {
            throw new \DomainException('Cannot cancel a debt when account balance is positive');
        }

        $this->recordThat(
            AccountDebtCanceled::create($this->id)
        );
    }

    public function activate()
    {
        $this->recordThat(
            AccountActivated::create($this->id)
        );
    }

    public function deactivate()
    {
        $this->recordThat(
            AccountDeactivated::create($this->id)
        );
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
        switch (get_class($event)) {
            case AccountActivated::class:
                $this->state = AccountState::ACTIVE();
                break;
            case AccountDeactivated::class:
                $this->state = AccountState::INACTIVE();
                break;
            case AccountCharged::class:
                /**
                 * @var $event AccountCharged
                 */
                $this->balance = $this->balance->subtract($event->money());
                break;

            case AccountDischarged::class:
                /**
                 * @var $event AccountDischarged
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

            case AccountDebtCanceled::class:
                /**
                 * @var $event AccountDebtCanceled
                 */
                $this->balance = new Money(0, $this->balance->getCurrency());
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
