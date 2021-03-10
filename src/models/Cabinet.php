<?php

namespace App\Models;

use App\Actions\CabinetAction;
use App\Exceptions\DoorClosedException;
use App\Exceptions\InvalidDrinkTypeException;

class Cabinet {

    private const SHELF_COUNT = 3;

    private $shelfs;

    private $operationStatus;

    private $doorStatus;

    public function __construct()
    {
        $this->appendShelfs();
        $this->operation = false;
        $this->doorStatus = false;
    }

    public function getShelfs()
    {
        return $this->shelfs;
    }

    public function drinkType()
    {
        if (count($this->shelfs)) {
            $firstShelf = $this->shelfs[0];
            if ($firstShelf->hasDrink()) {
                $firstDrink = $firstShelf->getDrinks()[0];
                return $firstDrink->getTitle();
            }
        }
        return null;
    }

    public function hasOperation()
    {
        return $this->operationStatus;
    }

    public function setOperationStatus(bool $status)
    {
        $this->operationStatus = $status;
    }

    public function setDoorStatus(bool $status)
    {
        $this->doorStatus = $status;
    }

    public function getDoorStatus()
    {
        return $this->doorStatus;
    }

    public function getCapacity()
    {
        $shelf = $this->shelfs[0];
        return self::SHELF_COUNT * $shelf::DRINK_LIMIT;
    }

    public function getTotalDrinkCount(): int
    {
        $total = 0;
        foreach($this->shelfs as $shelf) {            
            $total += $shelf->currentCount();
        }
        return $total;
    }

    public function getFullnessStatus()
    {
        $totalDrinkCount = $this->getTotalDrinkCount();
        if ($totalDrinkCount === 0) {
            return 'Cabinet is empty.';
        } else if ($totalDrinkCount === $this->getCapacity()) {
            return 'Cabinet is full.';
        } else return 'Cabinet is partially full.';
    }

    private function appendShelfs()
    {        
        for ($i = 0; $i < self::SHELF_COUNT; $i++) {
            $this->shelfs[] = new Shelf();
        }
    }

    private function findAvailableShelf(): ?Shelf
    {
        foreach($this->shelfs as $shelf) {
            if ($shelf->hasPlace()) return $shelf;
        }
        return null;
    }

    public function addDrink(Drink $drink)
    {                   
        if ($this->getTotalDrinkCount() > 0) {
            if ($drink->getTitle() !== $this->drinkType()) {
                throw new InvalidDrinkTypeException("Invalid drink type.");
            }
        }

        if ($this->getDoorStatus()) {
            (new CabinetAction($this))->run(function() use ($drink) {
                $shelf = $this->findAvailableShelf();
                if ($shelf) $shelf->push($drink);
            }); 
        }  else throw new DoorClosedException("Door is closed. It must be open");
    }

    public function getDrink()
    {
        if ($this->getDoorStatus()) {
            (new CabinetAction($this))->run(function() {
                foreach($this->shelfs as $shelf) {
                    if ($shelf->hasDrink()) {
                        $shelf->pop();
                        return true;
                    }
                }
            }); 
        }  else throw new DoorClosedException("Door is closed. It must be open");
    }

}