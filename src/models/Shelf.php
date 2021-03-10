<?php

namespace App\Models;

use App\Models\Drink;

class Shelf {
    
    public const DRINK_LIMIT = 20;

    private $drinks = [];

    public function currentCount(): int
    {
        return count($this->drinks);
    }

    public function getDrinks()
    {
        return $this->drinks;
    }

    public function hasPlace(): bool
    {
        return self::DRINK_LIMIT > $this->currentCount();
    }

    public function hasDrink(): bool
    {
        return count($this->drinks) > 0;
    }

    public function push(Drink $drink): bool
    {
        if ($this->hasPlace()) {
            $this->drinks[] = $drink;
            return true;
        }
        return false;
    }

    public function pop(): bool
    {
        if ($this->hasDrink()) {
            array_pop($this->drinks);
            var_dump(count($this->drinks));
            return true;
        }
        return false;
    }


}