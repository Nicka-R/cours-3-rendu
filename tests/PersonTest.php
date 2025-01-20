<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use App\Entity\Person;
use App\Entity\Product;
use Faker\Factory;

class PersonTest extends TestCase
{

    //before each test : on instancie un objet de la classe Person
    private $personA;
    private $personB;
    private $personC;
    private $personD;
    private $wallet;
    private $productA;
    private $productB;

    protected function setUp(): void
    {
        $faker = Factory::create();
        $walletCurrency = $faker->randomElement(['USD', 'EUR']);
        $this->personA = new Person($faker->name, 'EUR');
        $this->personB = new Person($faker->name, 'EUR');
        $this->personC = new Person($faker->name, 'EUR');
        $this->personD = new Person($faker->name, 'USD');
        $this->wallet = $this->personA->getWallet();

        $this->wallet->setBalance(100.);
        $this->personB->getWallet()->setBalance(90.);

        $this->productA = new Product('product', ['USD' => 10., 'EUR' => 9.], 'food');
        $this->productB = new Product('pizza', ['EUR' => 10.], 'food');



    }

    public function testCreatePersonWithFaker(): void
    {
        $faker = Factory::create();

        $name = $faker->name;
        $walletCurrency = $faker->randomElement(['USD', 'EUR']);

        $person = new Person($name, $walletCurrency);

        $this->assertInstanceOf(Person::class, $person);
        $this->assertEquals($name, $person->getName());
        $this->assertEquals($walletCurrency, $person->getWallet()->getCurrency());
    }

    public function test_hasFund(): void
    {
        $this->assertTrue($this->personA->hasFund());
    }

    public function test_hasFund_noFund(): void
    {
        $this->assertFalse($this->personC->hasFund());
    }

    public function test_transfertFund(): void
    {
        $this->personB->transfertFund(50., $this->personC);

        $this->assertEquals(50., $this->personC->getWallet()->getBalance());
        $this->assertEquals(40., $this->personB->getWallet()->getBalance());
    }

    public function test_transfertFund_invalidCurrency(): void
    {
        $this->expectException(\Exception::class);
        $this->personB->transfertFund(50., $this->personD);

        $this->assertEquals(0., $this->personD->getWallet()->getBalance());
        $this->assertEquals(0., $this->personB->getWallet()->getBalance());
    }

    public function test_divideWallet(): void
    {
        $this->personB->divideWallet([$this->personB, $this->personC, $this->personA]);

        $this->assertEquals(30., $this->personB->getWallet()->getBalance());
        $this->assertEquals(30., $this->personC->getWallet()->getBalance());
        $this->assertEquals(130., $this->personA->getWallet()->getBalance());
    }

    public function test_divideWallet_invalidCurrency(): void
    {
        $this->personB->divideWallet([$this->personB, $this->personC, $this->personD]);

        $this->assertEquals(45., $this->personB->getWallet()->getBalance());
        $this->assertEquals(45., $this->personC->getWallet()->getBalance());
        $this->assertEquals(0., $this->personD->getWallet()->getBalance());
    }

    public function test_buyProduct(): void
    {
        $this->personB->buyProduct($this->productA);

        $this->assertEquals(81., $this->personB->getWallet()->getBalance());
    }

    public function test_buyProduct_invalidCurrency(): void
    {
        $this->expectException(\Exception::class);
        $this->personD->buyProduct($this->productB);
    }

    //après chaque test : on remet la balance à 0
    protected function tearDown(): void
    {
        $this->wallet->setBalance(0);
        $this->personB->getWallet()->setBalance(0);
    }
}