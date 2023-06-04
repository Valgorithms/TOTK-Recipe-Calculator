<?php

/*
 * This file is a part of the TOTK Recipe Calculator project.
 *
 * Copyright (c) 2023-present Valithor Obsidion <valzargaming@gmail.com>
 *
 * This file is subject to the MIT license that is bundled
 * with this source code in the LICENSE.md file.
 */

use \TOTK\Crafter;
use \TOTK\Helpers\Collection;
use \TOTK\Parts\Ingredient;

ini_set('display_errors', 1);
error_reporting(E_ALL);
set_time_limit(0);
ignore_user_abort(1);
ini_set('max_execution_time', 0);
ini_set('memory_limit', '-1'); //Unlimited memory usage
if (! @include getcwd() . '/vendor/autoload.php') {
    include __DIR__ . '/src/TOTK/crafter.php';
    include __DIR__ . '/src/TOTK/Helpers/collection.php';
    include __DIR__ . '/src/TOTK/Parts/ingredient.php';
}


$crafter = new Crafter();

if (! $materials_file = @file(__DIR__ . '\vendor\vzgcoders\totk-recipe-calculator\src\TOTK\CSVs\materials.csv')) $materials_file = file(__DIR__ . '\src\TOTK\CSVs\materials.csv');
$csv = array_map('str_getcsv', $materials_file);
$keys = array_shift($csv);
$materials = array();
foreach ($csv as $row) $materials[] = array_combine($keys, $row);
$materials_collection = new Collection([], $keys[2]);
foreach ($materials as $array) $materials_collection->pushItem($array);

//var_dump($materials_collection);
//$ingredient1 = new Ingredient($materials_collection->get('Euen name', 'Hydromelon'));
/*
$ingredient2 = new Ingredient(
    'Item_Fruit_F', //Internal name
    'Hydromelon', //Actual name
    'Food', //Food, Elixer
    false, //Rock Hard?
    16, //Buying price
    4, //Selling price
    1, //Potency
    'ResistHot',  //Buff [LifeRecover, LifeMaxUp, StaminaRecover, ExStaminaMaxUp, ResistHot, ResistCold, ResistElectric, AllSpeed, AttackUp, DefenseUp, QuietnessUp, ResistBurn,, TwiceJump, EmergencyAvoid, LifeRepair, LightEmission, NotSlippy, SwimSpeedUp, AttackUpCold,AttackUpHot, AttackUpThunderstorm, MiasmaGuard]
    900, //Duration
    2 //Hitpoint Recovery (Quarters of a Heart)
);
$ingredient3 = new Ingredient(
    'Item_Fruit_F', //Internal name
    'Hydromelon', //Actual name
    'Food', //Food, Elixer
    false, //Rock Hard?
    16, //Buying price
    4, //Selling price
    1, //Potency
    'ResistHot',  //Buff [LifeRecover, LifeMaxUp, StaminaRecover, ExStaminaMaxUp, ResistHot, ResistCold, ResistElectric, AllSpeed, AttackUp, DefenseUp, QuietnessUp, ResistBurn,, TwiceJump, EmergencyAvoid, LifeRepair, LightEmission, NotSlippy, SwimSpeedUp, AttackUpCold,AttackUpHot, AttackUpThunderstorm, MiasmaGuard]
    900, //Duration
    2 //Hitpoint Recovery (Quarters of a Heart)
);
$ingredient4 = new Ingredient(
    'Item_Fruit_F', //Internal name
    'Hydromelon', //Actual name
    'Food', //Food, Elixer
    false, //Rock Hard?
    16, //Buying price
    4, //Selling price
    1, //Potency
    'ResistHot',  //Buff [LifeRecover, LifeMaxUp, StaminaRecover, ExStaminaMaxUp, ResistHot, ResistCold, ResistElectric, AllSpeed, AttackUp, DefenseUp, QuietnessUp, ResistBurn,, TwiceJump, EmergencyAvoid, LifeRepair, LightEmission, NotSlippy, SwimSpeedUp, AttackUpCold,AttackUpHot, AttackUpThunderstorm, MiasmaGuard]
    900, //Duration
    2 //Hitpoint Recovery (Quarters of a Heart)
);
$ingredient5 = new Ingredient(
    'Item_Fruit_F', //Internal name
    'Hydromelon', //Actual name
    'Food', //Food, Elixer
    false, //Rock Hard?
    16, //Buying price
    4, //Selling price
    1, //Potency
    'ResistHot',  //Buff [LifeRecover, LifeMaxUp, StaminaRecover, ExStaminaMaxUp, ResistHot, ResistCold, ResistElectric, AllSpeed, AttackUp, DefenseUp, QuietnessUp, ResistBurn,, TwiceJump, EmergencyAvoid, LifeRepair, LightEmission, NotSlippy, SwimSpeedUp, AttackUpCold,AttackUpHot, AttackUpThunderstorm, MiasmaGuard]
    900, //Duration
    2 //Hitpoint Recovery (Quarters of a Heart)
);*/

/* Fruity Tomato Soup
$ingredient1 = new Ingredient($materials_collection->get('Euen name', 'Hylian Tomato'));
$ingredient2 = new Ingredient($materials_collection->get('Euen name', 'Fresh Milk'));
$ingredient3 = new Ingredient($materials_collection->get('Euen name', 'Rock Salt'));
*/

/* Monster Curry
$ingredient1 = new Ingredient($materials_collection->get('Euen name', 'Hylian Rice'));
$ingredient2 = new Ingredient($materials_collection->get('Euen name', 'Goron Spice'));
$ingredient3 = new Ingredient($materials_collection->get('Euen name', 'Monster Extract'));
*/

/* Carrot Stew
$ingredient1 = new Ingredient($materials_collection->get('Euen name', 'Endura Carrot'));
$ingredient2 = new Ingredient($materials_collection->get('Euen name', 'Goat Butter'));
$ingredient3 = new Ingredient($materials_collection->get('Euen name', 'Fresh Milk'));
$ingredient4 = new Ingredient($materials_collection->get('Euen name', 'Tabantha Wheat'));
$ingredient5 = new Ingredient($materials_collection->get('Euen name', 'Fairy'));
*/


/* Salmon Meunière
$ingredient1 = new Ingredient($materials_collection->get('Euen name', 'Hearty Salmon'));
$ingredient2 = new Ingredient($materials_collection->get('Euen name', 'Goat Butter'));
$ingredient3 = new Ingredient($materials_collection->get('Euen name', 'Tabantha Wheat'));
$ingredient4 = new Ingredient($materials_collection->get('Euen name', 'Fairy'));
$ingredient5 = new Ingredient($materials_collection->get('Euen name', 'Endura Carrot'));
*/


/* Salmon Risotto
$ingredient1 = new Ingredient($materials_collection->get('Euen name', 'Razorclaw Crab'));
$ingredient2 = new Ingredient($materials_collection->get('Euen name', 'Hylian Rice'));
$ingredient3 = new Ingredient($materials_collection->get('Euen name', 'Rock Salt'));
$ingredient4 = new Ingredient($materials_collection->get('Euen name', 'Goat Butter'));
$ingredient5 = new Ingredient($materials_collection->get('Euen name', 'Hearty Salmon'));
*/

/* Crab Risotto
$ingredient2 = new Ingredient($materials_collection->get('Euen name', 'Hyrule Bass'));
$ingredient2 = new Ingredient($materials_collection->get('Euen name', 'Bright-Eyed Crab'));
$ingredient3 = new Ingredient($materials_collection->get('Euen name', 'Rock Salt'));
$ingredient4 = new Ingredient($materials_collection->get('Euen name', 'Hylian Rice'));
$ingredient5 = new Ingredient($materials_collection->get('Euen name', 'Goat Butter'));
*/

/* Seafood Paella
$ingredient1 = new Ingredient($materials_collection->get('Euen name', 'Armored Porgy'));
$ingredient2 = new Ingredient($materials_collection->get('Euen name', 'Razorclaw Crab'));
$ingredient3 = new Ingredient($materials_collection->get('Euen name', 'Hylian Rice'));
$ingredient4 = new Ingredient($materials_collection->get('Euen name', 'Rock Salt'));
$ingredient5 = new Ingredient($materials_collection->get('Euen name', 'Goat Butter'));
*/

/* Seafood Rice Balls (Test failed, last in list) (Patched by reversing array if no recipe containing generic categories were found in the highest ordered recipes)
$ingredient1 = new Ingredient($materials_collection->get('Euen name', 'Hyrule Bass'));
$ingredient2 = new Ingredient($materials_collection->get('Euen name', 'Sneaky River Snail'));
$ingredient3 = new Ingredient($materials_collection->get('Euen name', 'Rock Salt'));
$ingredient4 = new Ingredient($materials_collection->get('Euen name', 'Hylian Rice'));
$ingredient5 = new Ingredient($materials_collection->get('Euen name', 'Goat Butter'));
*/

/* Fruitcake (Test failed, Array to string conversion) (Patched by adding 'continue 2' inside of 'foreach ($opt as $o)' for parsed['optional']
$ingredient1 = new Ingredient($materials_collection->get('Euen name', 'Apple'));
$ingredient2 = new Ingredient($materials_collection->get('Euen name', 'Wildberry'));
$ingredient3 = new Ingredient($materials_collection->get('Euen name', 'Cane Sugar'));
$ingredient4 = new Ingredient($materials_collection->get('Euen name', 'Tabantha Wheat'));
*/

/* Tough Veggie Cream Soup
$ingredient1 = new Ingredient($materials_collection->get('Euen name', 'Fortified Pumpkin'));
$ingredient2 = new Ingredient($materials_collection->get('Euen name', 'Rock Salt'));
$ingredient3 = new Ingredient($materials_collection->get('Euen name', 'Fresh Milk'));
$ingredient4 = new Ingredient($materials_collection->get('Euen name', 'Fresh Milk'));
$ingredient5 = new Ingredient($materials_collection->get('Euen name', 'Raw Bird Thigh'));
*/

/* Energizing Elixir
$ingredient1 = new Ingredient($materials_collection->get('Euen name', 'Bokoblin Fang'));
$ingredient2 = new Ingredient($materials_collection->get('Euen name', 'Energetic Rhino Beetle'));
var_dump('[INGREDIENT 2]', $ingredient2);
*/

/* Warding Simmered Fruits (Test failed, results in High tier but is actually Low tier) (Patched by only adding potency if the ingredient shares an effectType with the meal)
$ingredient1 = new Ingredient($materials_collection->get('Euen name', 'Dazzlefruit'));
$ingredient2 = new Ingredient($materials_collection->get('Euen name', 'Dazzlefruit'));
$ingredient3 = new Ingredient($materials_collection->get('Euen name', 'Dark Clump'));
*/

/*
$ingredient1 = new Ingredient($materials_collection->get('Euen name', 'Mighty Bananas'));
$ingredient2 = new Ingredient($materials_collection->get('Euen name', 'Rock Salt'));
$ingredient3 = new Ingredient($materials_collection->get('Euen name', 'Fresh Milk'));
$ingredient4 = new Ingredient($materials_collection->get('Euen name', 'Fresh Milk'));
$ingredient5 = new Ingredient($materials_collection->get('Euen name', 'Raw Bird Thigh'));
*/

//$ingredient1 = new Ingredient($materials_collection->get('Euen name', 'Sanke Carp'));
//$ingredient2 = new Ingredient($materials_collection->get('Euen name', 'Raw Meat'));
//$ingredient3 = new Ingredient($materials_collection->get('Euen name', 'Monster Extract'));



//$ingredient1 = new Ingredient($materials_collection->get('Euen name', 'Hearty Salmon'));
//$ingredient2 = new Ingredient($materials_collection->get('Euen name', 'Monster Extract'));


//$ingredient1 = new Ingredient($materials_collection->get('Euen name', 'Mighty Porgy'));
//$ingredient2 = new Ingredient($materials_collection->get('Euen name', "Shard of Farosh's Fang"));

if (! $meals_csv = @file(__DIR__ . '\vendor\vzgcoders\totk-recipe-calculator\src\TOTK\CSVs\meals.csv')) $meals_csv = file(__DIR__ . '\src\TOTK\CSVs\meals.csv');
$csv = array_map('str_getcsv', $meals_csv);
$keys = array_shift($csv);
$keys[] = 'id';
$meals = array();
$id = 0;
foreach ($csv as $row) {
    $row[] = $id++;
    $meals[] = array_combine($keys, $row);
}
$meals_collection = new Collection([], 'id');
foreach ($meals as $array) $meals_collection->pushItem($array);

//var_dump('[MEAL]', $meals_collection->get('Euen name', 'Energizing Elixir'));

//var_dump('[INGREDIENTS]', $ingredients = [$ingredient1 ?? NULL, $ingredient2 ?? NULL, $ingredient3 ?? NULL, $ingredient4 ?? NULL, $ingredient5 ?? NULL]);
//var_dump('[INGREDIENTS]', $ingredients);

//var_dump('[OUTPUT]', $output = $crafter->process($ingredients));