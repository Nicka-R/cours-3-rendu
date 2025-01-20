<?php

namespace App\Entity;

class Product
{
    private const FOOD_PRODUCT = 'food';
    private const AVAILABLE_TYPES = ['food', 'tech','alcohol','other'];

    /** @var string $name The name of the product */
    private string $name;
    /** @var array<string, float> $prices The prices of the product in different currencies*/
    private array $prices;
    /** @var string $name The type of the product */
    private string $type;

    /**
     * Constructor method.
     *
     * @param string $name The name of the product
     * @param array<string, float> $prices The prices of the product in different currencies
     * @param string $type The type of the product
     */
    public function __construct(string $name, array $prices, string $type)
    {
        $this->setName($name);
        $this->setPrices($prices);
        $this->setType($type);
    }

    /**
     * Get the name of the product.
     *
     * @return string The name of the product
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get the prices of the product in different currencies.
     *
     * @return array<string, float> The prices of the product
     */
    public function getPrices(): array
    {
        return $this->prices;
    }

    /**
     * Get the type of the product.
     *
     * @return string The type of the product
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Set the type of the product.
     *
     * @param string $type The type of the product
     * @throws \Exception If the type is invalid
     */
    public function setType(string $type): void
    {
        if (!in_array($type, self::AVAILABLE_TYPES)) {
            throw new \Exception('Invalid type');
        } else {
            $this->type = $type;
        }
    }

    /**
     * Set the prices of the product in different currencies.
     *
     * @param array<string, float> $prices The prices of the product
     */
    public function setPrices(array $prices): void
    {
        foreach ($prices as $currency => $price) {
            if (!in_array($currency, Wallet::AVAILABLE_CURRENCY)) {
                continue;
            }

            if ($price < 0) {
                continue;
            }
            $this->prices[$currency] = $price;
        }
    }

    /**
     * Set the name of the product.
     *
     * @param string $name The name of the product
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Get the TVA (Taxe sur la Valeur Ajoutée) for the product.
     *
     * @return float The TVA for the product
     */
    public function getTVA(): float
    {
        return $this->type === self::FOOD_PRODUCT ? 0.1 : 0.2;
    }

    /**
     * Get the list of currencies in which the product is priced.
     *
     * @return array<string> The list of currencies
     */
    public function listCurrencies(): array
    {
        $currencies = [];

        foreach ($this->prices as $currency => $price) {
            $currencies[] = $currency;
        }
        return $currencies;
    }

    /**
     * Get the price of the product in the specified currency.
     *
     * @param string $currency The currency
     * @return float The price of the product in the specified currency
     * @throws \Exception If the currency is invalid or not available for this product
     */
    public function getPrice(string $currency): float
    {
        if (!in_array($currency, Wallet::AVAILABLE_CURRENCY)) {
            throw new \Exception('Invalid currency');
        }
        if (array_key_exists($currency, $this->prices)) {
            return $this->prices[$currency];
        } else {
            throw new \Exception('Currency not available for this product');
        }
    }
}
