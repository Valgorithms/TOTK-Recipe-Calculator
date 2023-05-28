<?php
//require 'collection.php';
$csv = array_map('str_getcsv', file('meals.csv'));
$keys = array_shift($csv);
$keys[] = 'id';
$meals = array();
$id = 0;
foreach ($csv as $row) {
    $row[] = $id;
    if (count($row) == count($keys)) $meals[] = array_combine($keys, $row);
    $id++;
}
$meals_collection = new Collection([], 'id');
foreach ($meals as $array) $meals_collection->pushItem($array);
/*
var_dump($meals_collection);
var_dump($meals_collection->filter(function ($m) { //Used to get all recipes for a meal
    if ($m['Euen name'] == 'Dubious Food') return $m;
}));
*/