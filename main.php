<?php

//if (! $ingredients = file_get_contents('ingredients.json')) trigger_error('Fatal Error: Unable to read ingredients file', E_USER_ERROR);
//$ingredients = json_decode($ingredients, true);

//CLASSES
require 'ingredient.php';
require 'recipe.php';
require 'collection.php';
//DATA DUMP COLLECTIONS
require 'materials.php'; //$materials, $materials_collection
require 'roast_chilled.php'; //$roast_chilled, $roast_chilled_collection
require 'meals.php'; //$meals, $meals_collection
//Final Product Crafting
include 'crafter.php';


$crafter = new Crafter($materials, $materials_collection, $meals, $meals_collection, $roast_chilled, $roast_chilled_collection);

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

$ingredient1 = new Ingredient($materials_collection->get('Euen name', 'Hydromelon'));
$recipe = new Recipe([$ingredient1 ?? NULL, $ingredient2 ?? NULL, $ingredient3 ?? NULL, $ingredient4 ?? NULL, $ingredient5 ?? NULL]);
//var_dump($recipe);

$result = $crafter->process($recipe);
//var_dump($result);