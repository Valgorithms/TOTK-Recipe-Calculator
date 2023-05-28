<?php
//require 'collection.php';
$csv = array_map('str_getcsv', file('materials.csv'));
$keys = array_shift($csv);
//$keys[] = 'Classification';
//$keys[] = 'Subclassification';
//$keys[] = 'RockHard';
//$keys[] = 'ConfirmedTime';
$materials = array();
foreach ($csv as $row) {
    $lower = strtolower($row[1]); //Actor name sometimes contains classification
    $classification = 'Material';
    $subclassification = '';
    $rock_hard = TRUE;
    switch (TRUE) {
        case str_contains($lower, 'ore'):
            $classification = 'CookOre';
            break;
        case str_contains($lower, 'insect'):
            $classification = 'CookInsect';
            $rock_hard = FALSE;
            break;
        case str_contains($lower, 'fruit'):
            $classification = 'CookFruit';
            $rock_hard = FALSE;
            break;
        case str_contains($lower, 'fish'):
            $classification = 'CookFish';
            $rock_hard = FALSE;
            break;
        case str_contains($lower, 'meat'):
            $classification = 'Raw Meat';
            $rock_hard = FALSE;
            break;
        case str_contains($lower, 'mushroom'):
            $classification = 'CookMushroom';
            $rock_hard = FALSE;
            break;
        case str_contains($lower, 'plant'):
            $classification = 'CookPlant';
            $rock_hard = FALSE;
            break;
        case str_contains($lower, 'enemy'):
            $classification = 'Monster Extract';
            $rock_hard = FALSE;
            break;
    }
    $lower2 = strtolower($row[2]); //Euen name can sometimes be used to extrapolate classification, or have other exceptions
    switch (true) {
        case str_contains($lower2, 'seed'):
            $classification = 'CookFruit';
            break;
        case str_contains($lower2, 'star'):
        case str_contains($lower2, 'dragon'): //Light Dragon adds 30 minutes to duration
            $classification = 'ExtendTime';
            break;
    }
    $row[] = $classification;
    $row[] = $rock_hard;
    $materials[] = array_combine($keys, $row);
}
$materials_collection = new Collection([], $keys[2]);
foreach ($materials as $array) $materials_collection->pushItem($array);