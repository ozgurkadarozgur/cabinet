<?php

use App\Models\Cabinet;
use App\Models\Drink;

require __DIR__ . '/vendor/autoload.php';

$cabinet = new Cabinet();

$drink = new Drink("Coca Cola");

$cabinet->setDoorStatus(true);

for($i = 0; $i < 45; $i++) {
    $cabinet->addDrink(new Drink('Coca Cola'));
}

echo sprintf("%s\n", $cabinet->getFullnessStatus());