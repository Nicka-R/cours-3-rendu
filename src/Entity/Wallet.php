<?php

namespace App\Entity;

class Wallet
{
    /**
     * @var float $balance The balance of the wallet.
     */
    private float $balance;

    /**
     * @var string $currency The currency of the wallet.
     */
    private string $currency;

    /**
     * @var array<string> AVAILABLE_CURRENCY The available currency options.
     */
    public const AVAILABLE_CURRENCY = ['USD', 'EUR'];

    /**
     * Constructor for the Wallet class.
     *
     * @param string $currency The currency for the wallet.
     */
    public function __construct(string $currency)
    {
        $this->setBalance(0);
        $this->setCurrency($currency);
    }

    /**
     * Retrieves the balance of the wallet.
     *
     * @return float The balance of the wallet.
     */
    public function getBalance(): float
    {
        return $this->balance;
    }

    /**
     * Retrieves the currency of the wallet.
     *
     * @return string The currency of the wallet.
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * Sets the balance of the wallet.
     *
     * @param float $balance The new balance for the wallet.
     * @throws \Exception If the balance is invalid.
     */
    public function setBalance(float $balance): void
    {
        if ($balance < 0) {
            throw new \Exception('Invalid balance');
        }
        $this->balance = $balance;
    }

    /**
     * Sets the currency of the wallet.
     *
     * @param string $currency The new currency for the wallet.
     * @throws \Exception If the currency is invalid.
     */
    public function setCurrency(string $currency): void
    {
        if (!in_array($currency, self::AVAILABLE_CURRENCY)) {
            throw new \Exception('Invalid currency');
        } else {
            $this->currency = $currency;
        }
    }

    /**
     * Removes funds from the wallet.
     *
     * @param float $amount The amount to be removed from the wallet.
     * @throws \Exception If the amount is invalid or insufficient funds.
     */
    public function removeFund(float $amount): void
    {
        if ($amount < 0) {
            throw new \Exception('Invalid amount');
        }
        if ($this->balance - $amount < 0) {
            throw new \Exception('Insufficient funds');
        }

        $this->balance -= $amount;
    }

    /**
     * Adds funds to the wallet.
     *
     * @param float $amount The amount to be added to the wallet.
     * @throws \Exception If the amount is invalid.
     */
    public function addFund(float $amount): void
    {
        if ($amount < 0) {
            throw new \Exception('Invalid amount');
        }
        $this->balance += $amount;
    }
}
