<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use App\Entity\Product;
use Faker\Factory;

class ProductTest extends TestCase
{

    private $techProduct;
    private $foodProduct;
    private $alcoholProduct;
    private $otherProduct;

    public function setUp(): void
    {
        $this->techProduct = new Product('laptop', ['USD' => 1000], 'tech');
        $this->foodProduct = new Product('pizza', ['USD' => 10, 'EUR' => 9], 'food');
        $this->alcoholProduct = new Product('wine', ['EUR' => 18], 'alcohol');
        $this->otherProduct = new Product('book', ['EUR' => 13], 'other');
    }

    public function test_GetName(): void
    {
        $this->assertEquals('laptop', $this->techProduct->getName());
        $this->assertEquals('pizza', $this->foodProduct->getName());
        $this->assertEquals('wine', $this->alcoholProduct->getName());
        $this->assertEquals('book', $this->otherProduct->getName());
    }

    public function test_GetPrices(): void
    {
        $this->assertEquals(['USD' => 1000], $this->techProduct->getPrices());
        $this->assertEquals(['USD' => 10, 'EUR' => 9], $this->foodProduct->getPrices());
        $this->assertEquals(['EUR' => 18], $this->alcoholProduct->getPrices());
        $this->assertEquals(['EUR' => 13], $this->otherProduct->getPrices());
    }



    public function test_GetType(): void
    {
        $this->assertEquals('tech', $this->techProduct->getType());
        $this->assertEquals('food', $this->foodProduct->getType());
        $this->assertEquals('alcohol', $this->alcoholProduct->getType());
        $this->assertEquals('other', $this->otherProduct->getType());
    }

    public function test_setType(): void
    {
        $this->foodProduct->setType('tech');
        $this->assertEquals('tech', $this->foodProduct->getType());
    }

    public function test_setType_invalid(): void
    {
        $this->expectException(\Exception::class);
        $this->foodProduct->setType('invalid');
    }

    public function test_setPrices(): void
    {
        $this->alcoholProduct->setPrices(['USD' => 20, 'EUR' => 18]);
        $this->assertEquals(['USD' => 20, 'EUR' => 18], $this->alcoholProduct->getPrices());
    }

    public function test_setPrices_invalidCurrency(): void
    {
        $this->alcoholProduct->setPrices(['USD' => 20, 'EUR' => 18, 'CHF' => 15]);
        $this->assertEquals(['USD' => 20, 'EUR' => 18], $this->alcoholProduct->getPrices());
    }

    public function test_setPrices_invalidPrice(): void
    {
        $this->alcoholProduct->setPrices(['USD' => -20, 'EUR' => 18]);
        $this->assertEquals(['EUR' => 18], $this->alcoholProduct->getPrices());
    }

    public function test_setName(): void
    {
        $this->foodProduct->setName('burger');
        $this->assertEquals('burger', $this->foodProduct->getName());
    }

    public function test_getTVA(): void
    {
        $this->assertEquals(0.1, $this->foodProduct->getTVA());
        $this->assertEquals(0.2, $this->techProduct->getTVA());
        $this->assertEquals(0.2, $this->alcoholProduct->getTVA());
        $this->assertEquals(0.2, $this->otherProduct->getTVA());
    }

    public function test_listCurrencies(): void
    {
        var_dump($this->foodProduct->listCurrencies());
        $this->assertEquals([ 0 => 'USD', 1 => 'EUR'], $this->foodProduct->listCurrencies());
        $this->assertEquals([ 0 => 'USD'], $this->techProduct->listCurrencies());
        $this->assertEquals([ 0 => 'EUR'], $this->alcoholProduct->listCurrencies());
        $this->assertEquals([ 0 => 'EUR'], $this->otherProduct->listCurrencies());
    }

    public function test_getPrice_byCurrency(): void
    {
        $this->assertEquals(10, $this->foodProduct->getPrice('USD'));
        $this->assertEquals(9, $this->foodProduct->getPrice('EUR'));

    }

    public function test_getPrice_by_InvalidCurrency(): void
    {
        $this->expectException(\Exception::class);
        $this->foodProduct->getPrice('CHF');
    }

    public function test_getPrice_by_UnavailableCurrency(): void
    {
        $this->expectException(\Exception::class);
        $this->techProduct->getPrice('EUR');
    }

}
