<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use App\Entity\Person;
use App\Entity\Wallet;
use Faker\Factory;

class WalletTest extends TestCase
{
    //before each test : on instancie un objet de la classe Person
    private $person;
    private $wallet;

    protected function setUp(): void
    {
        $faker = Factory::create();
        $walletCurrency = $faker->randomElement(['USD', 'EUR']);
        $this->person = new Person($faker->name, $walletCurrency);
        $this->wallet = $this->person->getWallet();
    }

    public function test_GetBalance(): void
    {
        $this->assertEquals(0, $this->wallet->getBalance());
    }

    public function test_SetBalance(): void
    {
        $this->wallet->setBalance(100);
        $this->assertEquals(100, $this->wallet->getBalance());
    }

    public function test_SetBalance_WithNegativeValue(): void
    {
        $this->expectException(\Exception::class);
        $this->wallet->setBalance(-100);
    }

    public function test_GetCurrency(): void
    {
        $this->assertContains($this->wallet->getCurrency(), Wallet::AVAILABLE_CURRENCY);
    }

    public function test_setCurrency(): void
    {
        $this->wallet->setCurrency('EUR');
        $this->assertEquals('EUR', $this->wallet->getCurrency());
    }

    public function test_setCurrency_invalid(): void
    {
        $this->expectException(\Exception::class);
        $this->wallet->setCurrency('CHF');
    }


    public function test_addFund(): void
    {
        $this->wallet->addFund(65.0);
        $this->assertEquals(65.0, $this->wallet->getBalance());
    }

    public function test_addFund_negativeValue(): void
    {
        $this->expectException(\Exception::class);
        $this->wallet->addFund(-65.0);
    }

    public function test_removeFund(): void
    {
        $this->wallet->setBalance(100);
        $this->wallet->removeFund(60.0);
        $this->assertEquals(40.0, $this->wallet->getBalance());
    }

    public function test_removeFund_invalidBalance(): void
    {
        $this->wallet->setBalance(10);
        $this->expectException(\Exception::class);
        $this->wallet->removeFund(50.0);
        $this->assertEquals(10.0, $this->wallet->getBalance());
    }

    public function test_removeFund_invalidFund(): void
    {
        $this->wallet->setBalance(10);
        $this->expectException(\Exception::class);
        $this->wallet->removeFund(-50.0);
        $this->assertEquals(10.0, $this->wallet->getBalance());
    }

    //après chaque test : on remet balance à 0
    protected function tearDown(): void
    {
        $this->wallet->setBalance(0);
    }
    

}
