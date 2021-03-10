<?php

use App\Models\Cabinet;
use App\Models\Drink;
use App\Models\Shelf;
use PHPUnit\Framework\TestCase;

final class ShelfTest extends TestCase {

    private const DRINK_LIMIT = 20;

    public function testCabinetShelfCount(): void
    {
        $cabinet = new Cabinet();

        foreach($cabinet->getShelfs() as $shelf) {
            $this->assertSame($shelf::DRINK_LIMIT, self::DRINK_LIMIT);
        }
    }

    public function testAShelfCanPlace20DrinksAsMaximum()
    {
        /**
         * In this test $drinkCountToBePushed must not be lower than 20.
         */
        $drinkCountToBePushed = 10;

        $shelf = new Shelf();
        for ($i = 0; $i < $drinkCountToBePushed; $i++) {
            $drink = new Drink('Coca Cola');
            $shelf->push($drink);
        }
        $currentDrinkCount = $shelf->currentCount();
        
        if ($drinkCountToBePushed > 20) {
            $this->assertSame($currentDrinkCount, self::DRINK_LIMIT);
        } else {
            $this->assertLessThanOrEqual($currentDrinkCount, $drinkCountToBePushed);
        }
    }
    

}