<?php
//require 'collection.php';
$csv = array_map('str_getcsv', file('meals.csv'));
$keys = array_shift($csv);
$meals = array();
foreach ($csv as $row) if (count($row) == count($keys)) $meals[] = array_combine($keys, $row);
$meals_collection = new Collection([], $keys[2]);
foreach ($meals as $array) $meals_collection->pushItem($array);