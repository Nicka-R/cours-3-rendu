<?php

namespace App\Entity;

class Person
{
    /** @var string $name The name of the person.  */
    public string $name;
    /** @var Wallet $wallet The wallet of the person.  */
    public Wallet $wallet;

    /**
     * Constructs a new instance of the class.
     *
     * @param string $name The name of the instance.
     * @param string $walletCurrency The currency of the instance's wallet.
     */
    public function __construct(string $name, string $walletCurrency)
    {
        $this->setName($name);
        $this->setWallet(new Wallet($walletCurrency));
    }


    /**
     * Retrieves the name of the person.
     *
     * @return string The name of the person.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Sets the name of the person.
     *
     * @param string $name The name of the person.
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Retrieves the wallet of the person.
     *
     * @return Wallet|null The wallet of the person, or null if the person has no wallet.
     */
    public function getWallet(): ?Wallet
    {
        return $this->wallet;
    }
    /**
     * Creates a new wallet for the person.
     *
     * @param Wallet $wallet The wallet to create for the person.
     */
    public function setWallet(Wallet $wallet): void
    {
        $this->wallet = $wallet;
    }
    /**
     * Checks if the person has fund in his wallet.
     *
     * @return bool True if the person has fund, false otherwise.
     */
    public function hasFund(): bool
    {
        return $this->wallet->getBalance() !== 0.0;
    }
    /**
     * Transfers funds from the person's wallet to another person's wallet.
     *
     * @param float $amount The amount of funds to transfer.
     * @param Person $person The person to transfer funds to.
     * @throws \Exception If this wallet currency is different from the person's wallet currency.
     */
    public function transfertFund(float $amount, Person $person): void
    {
        if ($person->wallet->getCurrency() !== $this->wallet->getCurrency()) {
            throw new \Exception('Can\'t give money with different currencies');
        }
        $this->wallet->removeFund($amount);
        $person->wallet->addFund($amount);
    }

    /**
     * Divides the balance of the person's wallet among a group of persons.
     *
     * @param array<Person> $persons The persons to divide the wallet balance among.
     */
    public function divideWallet(array $persons): void
    {
        $persons = array_values(array_filter($persons, fn($person) => $person->wallet->getCurrency() === $this->wallet->getCurrency()));
        $partPerPerson = round($this->wallet->getBalance() / count($persons), 2);
        $remainingPart = round($this->wallet->getBalance() - $partPerPerson * count($persons), 2);

        $this->transfertFund($remainingPart + $partPerPerson, $persons[0]);
        for ($index = 1; $index < count($persons); $index++) {
            $this->transfertFund($partPerPerson, $persons[$index]);
        }
    }
    /**
     * Buys a product using the person's wallet.
     *
     * @param Product $product The product to buy.
     * @throws \Exception If the product cannot be bought with the person's wallet currency.
     */
    public function buyProduct(Product $product): void
    {
        $walletCurrency = $this->wallet->getCurrency();
        $productAvailableCurrency = $product->listCurrencies();
        if (!in_array($walletCurrency, $productAvailableCurrency)) {
            throw new \Exception('Can\'t buy product with this wallet currency');
        }
        $this->wallet->removeFund($product->getPrice($walletCurrency));
    }
}
