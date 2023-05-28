<?php
//require 'collection.php';
$csv = array_map('str_getcsv', file('materials.csv'));
$keys = array_shift($csv);
$keys[] = 'Classification';
$keys[] = 'RockHard';
$materials = array();
foreach ($csv as $row) {
    $row[] = '';
    $row[] = FALSE;
    $materials[] = array_combine($keys, $row);
}
$materials_collection = new Collection([], $keys[2]);
foreach ($materials as $array) $materials_collection->pushItem($array);