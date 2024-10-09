<?php

declare(strict_types=1);

class VendingMachine
{
    const CURRENCY_POSITION_AFTER = 1;
    const CURRENCY_POSITION_BEFORE = 2;

    private array $currency;
    private array $drinks;

    public function __construct(array $currency, array $drinks) {
        $this->currency = $currency;
        $this->drinks = $drinks;
    }

    public function viewDrinks(): array {
        $drinks = array_map(function ($price, $name) {
            return [
                'price' => $this->formatCurrency($price),
                'name' => $name,
            ];
        }, $this->drinks, array_keys($this->drinks));
        return $drinks;
    }

    public function putCoin(): string {
        $amount = (float) ($_GET['amount'] ?? 0);
        $_SESSION['balance'] = ($_SESSION['balance'] ?? 0) + $amount;
        return $this->getBalance();
    }
    
    public function buyDrink(): array {
        $drinkName = $_GET['drink'] ?? '';
        $drinkPrice = $this->drinks[$drinkName];
        if ($drinkName && $drinkPrice) {
            $balance = $_SESSION['balance'] ?? 0;
            if ($balance >= $drinkPrice) {
                $_SESSION['balance'] = $balance - $drinkPrice;
                return [
                    'drink' => $drinkName,
                    'balance' => $this->getBalance(),
                ];
            } else {
                return [
                    'error' => 'Not enough money',
                ];
            }
        }
        return [
            'error' => 'Drink not found',
        ];
    }

    public function getCoins(): array {
        $before = $this->getBalance();
        $_SESSION['balance'] = 0;
        return [
            'before' => $before,
            'after' => $this->getBalance(),
        ];
    }

    public function viewAmount(): string {
        return $this->getBalance();
    }

    public function getBalance(): string {
        return $this->formatCurrency($_SESSION['balance'] ?? 0);
    }

    private function formatCurrency(float $amount): string {
        $currency = $this->currency;
        $amount = number_format($amount, 2, '.', '');

        if ($currency['position'] === self::CURRENCY_POSITION_AFTER) {
            return $amount . $currency['space'] . $currency['sign'];
        } else {
            return $currency['sign'] . $currency['space'] . $amount;
        }
    }
}