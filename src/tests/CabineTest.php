<?php

use App\Exceptions\DoorClosedException;
use App\Exceptions\InvalidDrinkTypeException;
use App\Models\Cabinet;
use App\Models\Drink;
use PHPUnit\Framework\TestCase;

final class CabineTest extends TestCase {

    private const SHELF_COUNT = 3;

    public function testCabinetShelfCount(): void
    {
        $cabinet = new Cabinet();
        $this->assertSame(self::SHELF_COUNT, count($cabinet->getShelfs()));
    }

    public function testCabinetDoorMustBeOpenedBeforeAddDrink()
    {      
        $this->expectException(DoorClosedException::class);
        $cabinet = new Cabinet();
        $drink = new Drink('Coca Cola');
        $cabinet->addDrink($drink);
    }

    public function testCabinetDoorMustBeOpenedBeforeGetDrink()
    {      
        $this->expectException(DoorClosedException::class);
        $cabinet = new Cabinet();
        $drink = new Drink('Coca Cola');
        $cabinet->setDoorStatus(true);
        $cabinet->addDrink($drink);
        $cabinet->setDoorStatus(false);
        $cabinet->getDrink();
    }

    public function testAddDrinkToCabinet()
    {
        $cabinet = new Cabinet();
        $drink = new Drink('Coca Cola');
        $drinkCount = 45;
        $cabinet->setDoorStatus(true);
        for($i = 0; $i < $drinkCount; $i++) {
            $cabinet->addDrink($drink);
        }

        $this->assertSame($drinkCount, $cabinet->getTotalDrinkCount());
    }


    public function testGetDrinkFromCabinet()
    {
        $cabinet = new Cabinet();
        $drink = new Drink('Coca Cola');
        $addDrinkCount = 45;
        $cabinet->setDoorStatus(true);
        
        for($i = 0; $i < $addDrinkCount; $i++) {
            $cabinet->addDrink($drink);
        }

        $getDrinkCount = 25;
        for($i = 0; $i < $getDrinkCount; $i++) {
            $cabinet->getDrink();
        }

        $this->assertSame($addDrinkCount - $getDrinkCount, $cabinet->getTotalDrinkCount());
    }

    public function testAddOnlyOneTypeDrinkToCabinet()
    {

        $this->expectException(InvalidDrinkTypeException::class);

        $cabinet = new Cabinet();
        $drink = new Drink('Coca Cola');
        $cabinet->setDoorStatus(true);
        for ($i = 0; $i < 20; $i++) {
            $cabinet->addDrink($drink);
        }

        $drink = new Drink('Sprite');
        $cabinet->addDrink($drink);

    }

}