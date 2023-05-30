<?php
//require 'collection.php';
$csv = array_map('str_getcsv', file('roast_chilled.csv'));
$keys = array_shift($csv);
$roast_chilled = array();
foreach ($csv as $row) if (count($row) == count($keys)) $roast_chilled[] = array_combine($keys, $row);
$roast_chilled_collection = new Collection([], $keys[2]);
foreach ($roast_chilled as $array) $roast_chilled_collection->pushItem($array);