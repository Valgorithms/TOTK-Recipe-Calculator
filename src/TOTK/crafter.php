<?php

/*
 * This file is a part of the TOTK Recipe Calculator project.
 *
 * Copyright (c) 2023-present Valithor Obsidion <valzargaming@gmail.com>
 *
 * This file is subject to the MIT license that is bundled
 * with this source code in the LICENSE.md file.
 */

namespace TOTK;

use TOTK\Helpers\Collection;

class Crafter {
    private array $materials;
    private Collection $materials_collection;
    private array $meals;
    private Collection $meals_collection;
    private array $roast_chilled;
    private Collection $roast_chilled_collection;

    private ?array $ingredients = [];
    private ?array $meal = [];

    private array $foodMaterials = ['CookFruit', 'CookFish', 'CookFruit', 'CookInsect', 'CookMeat', 'CookMushroom', 'CookPlant', 'Material'];
    private array $dubiousMaterial = ['CookForeign', 'CookGolem', 'CookEnemy', 'CookInsect'];
    private array $oreMaterial = ['CookOre'];
    private array $elixirMaterial = ['CookEnemy', 'CookInsect'];
    private array $fairyMaterial = ['Fairy'];

    private ?string $classification = '';
    private ?string $modifier = '';

    public function __construct(?array $materials = [], ?Collection $materials_collection = null, ?array $meals = null, ?Collection $meals_collection = null, ?array $roast_chilled = null, ?Collection $roast_chilled_collection = null) {
        if ($materials) $this->setMaterials($materials);
        else {
            $csv = array_map('str_getcsv', file(__DIR__ . '\CSVs\materials.csv'));
            $keys = array_shift($csv);
            $materials = array();
            foreach ($csv as $row) $materials[] = array_combine($keys, $row);
            $this->setMaterials($materials);
        }
        if ($materials_collection) $this->setMaterials($materials);
        else {
            $materials_collection = new Collection([], $keys[2]);
            foreach ($materials as $array) $materials_collection->pushItem($array);
            $this->setMaterialsCollection($materials_collection);
        } 
        if ($meals) $this->setMeals($meals);
        else {
            $csv = array_map('str_getcsv', file(__DIR__ . '\CSVs\meals.csv'));
            $keys = array_shift($csv);
            $keys[] = 'id';
            $meals = array();
            $id = 0;
            foreach ($csv as $row) {
                $row[] = $id++;
                $meals[] = array_combine($keys, $row);
            }
            $this->setMeals($meals);
        }
        if ($meals_collection) $this->setMealsCollection($meals_collection);
        else {
            $meals_collection = new Collection([], 'id');
            foreach ($meals as $array) $meals_collection->pushItem($array);
            $this->setMealsCollection($meals_collection);
        }
        if ($roast_chilled) $this->setRoastChilled($roast_chilled);
        else {
            $csv = array_map('str_getcsv', file(__DIR__ . '\CSVs\roast_chilled.csv'));
            $keys = array_shift($csv);
            $keys[] = 'id';
            $roast_chilled = array();
            $id = 0;
            foreach ($csv as $row) {
                $row[] = $id++;
                $roast_chilled[] = array_combine($keys, $row);
            }
            $this->setRoastChilled($roast_chilled);
        }
        if ($roast_chilled_collection) $this->setRoastChilledCollection($roast_chilled_collection);
        else {
            $roast_chilled_collection = new Collection([], 'id');
            foreach ($roast_chilled as $array) $roast_chilled_collection->pushItem($array);
            $this->setRoastChilledCollection($roast_chilled_collection);
        }
    }

    public function process(Array $ingredients): array|collection|null
    {
        $this->setIngredients($ingredients);

        $flags = []; //This will be an array of arrays, where the key is the classification and the value is an array of ingredients that have that classification
        $components = [];
        $modifiers = [];
        $categories = [];
        $int = 0;
        foreach ($ingredients as $ingredient) if ($ingredient) {
            //var_dump('[INGREDIENT]', $ingredient);
            $flags[$ingredient->getClassification()][]=$ingredient->getEuenName();
            $components[] = $ingredient->getEuenName();
            $modifiers[] = $ingredient->getModifier();
            $categories[] = $ingredient->getClassification();
            $int++;
        }
        //var_dump('[COMPONENTS]', $components);
        //var_dump('[CATEGORIES]', $categories);
        //var_dump('[FLAGS]', $flags);


        $possible_meals = [];
        foreach ($this->getMeals() as $meal) {
            $components_copy = $components;
            $modifiers_copy = $modifiers;
            $categories_copy = $categories;
            //var_dump('[MEAL]', $meal);
            $parsedRecipe = function ($meal)
            {
                $required = [];
                $optional = [];
        
                $meal = str_replace('"','', $meal); // get rid of quotes
        
                // get the optionals first
                if (preg_match_all('/\([^\)]+\)/', $meal, $matches))
                {
                    foreach ($matches[0] as $match)
                    {
                        $match = substr($match,1,-1); // remove the ()
                        $items = [];
                        foreach(explode('||', $match) as $item)
                        {
                            if (strlen(trim($item)))
                                $items[] = trim($item);
                        }
                        $optional[] = $items;
                    }
                    
                    $meal = preg_replace('/\([^\)]+\)/', '', $meal);
                }
                
                // Required should be remaining
                foreach(explode('&&', $meal) as $item)
                {
                    if (strlen(trim($item)))
                        $required[] = trim($item);
                }
        
                return ['required' => $required, 'optional' => $optional];
            };
            $parsed = $parsedRecipe($meal['Recipe']);
            //var_dump('[PARSED RECIPE]', $parsed);

            //For each recipe requirement, array of arrays inside of $reqs, check if at least one ingredient or category is in the recipe, and if it is then remove it from the classifications array and move on to the next array of arrays
            $valid = false;
            if ($parsed['required']) {
                $valid = false;
                foreach ($parsed['required'] as $req) {
                    $valid = false;
                    if (in_array($req, $components_copy)) {
                        $valid = true;
                        $key = array_search($req, $components_copy);
                        //var_dump('Found a Component match!', $req);
                        unset($components_copy[$key]);
                        unset($modifiers_copy[$key]);
                        unset($categories_copy[$key]);
                    } elseif(in_array($req, $modifiers_copy)) {
                        $valid = true;
                        $key = array_search($req, $modifiers_copy);
                        //var_dump('Found a Modifier match!', $req);
                        unset($components_copy[$key]);
                        unset($modifiers_copy[$key]);
                        unset($categories_copy[$key]);
                    } elseif(in_array($req, $categories_copy)) {
                        $valid = true;
                        $key = array_search($req, $categories_copy);
                        //var_dump('Found a Category match!', $req);
                        unset($components_copy[$key]);
                        unset($modifiers_copy[$key]);
                        unset($categories_copy[$key]);
                    }
                    if (! $valid) {
                        //var_dump($meal['Euen name'] . ' is not a valid recipe! (Failed to find required) ' . $req);
                        //var_dump('[Remaining components]', $components_copy);
                        //var_dump('[Remaining categories]', $categories_copy);
                        continue 2;
                    }
                }
                if (!$valid) {
                    //var_dump($meal['Euen name'] . ' is not a valid recipe! (Failed to find required)');
                    //var_dump('[Remaining components]', $components_copy);
                    //var_dump('[Remaining categories]', $categories_copy);
                    continue;
                }
            }
            if ($parsed['optional']) {
                $valid = false;
                foreach (array_values($parsed['optional']) as $opt) {
                    $valid = false;
                    foreach ($opt as $o) {
                        //echo '[o] ' . $o . PHP_EOL;
                        if (in_array($o, $components_copy)) {
                            $valid = true;
                            $key = array_search($o, $components_copy);
                            //var_dump('Found an optional Component match!', $o);
                            unset($components_copy[$key]);
                            unset($modifiers_copy[$key]);
                            unset($categories_copy[$key]);
                            continue 2;
                        } elseif (in_array($o, $modifiers_copy)) {
                            $valid = true;
                            $key = array_search($o, $modifiers_copy);
                            //var_dump('Found an optional Modifier match!', $o);
                            unset($components_copy[$key]);
                            unset($modifiers_copy[$key]);
                            unset($categories_copy[$key]);
                            continue 2;
                        } elseif (in_array($o, $categories_copy)) {
                            $valid = true;
                            $key = array_search($o, $categories_copy);
                            //var_dump('Found an optional Category match!', $o);
                            unset($components_copy[$key]);
                            unset($modifiers_copy[$key]);
                            unset($categories_copy[$key]);
                            continue 2;
                        }
                    }
                    if (! $valid) {
                        //var_dump('[OPTIONAL]', $parsed['optional']);
                        //var_dump($meal['Euen name'] . ' is not a valid recipe! (Failed to find optional) ' . $o);
                        //var_dump('[Remaining components]', $components_copy);
                        //var_dump('[Remaining categories]', $categories_copy);
                        continue 2;
                    }
                }
                if (!$valid) {
                    //var_dump($meal['Euen name'] . ' is not a valid recipe! (Failed to find optional)');
                    //var_dump('[Remaining components]', $components_copy);
                    //var_dump('[Remaining categories]', $categories_copy);
                    continue;
                }
            }
            
            if ($valid) {
                //var_dump($meal['Euen name'] . ' is a valid recipe!');
                $possible_meals[] = $meal;
            }
        }

        //We got a good list to work from! Now we need to figure out which one is the correct one.
        foreach ($possible_meals as $meal) {
            //Count the number of strings that appear inside of the array of arrays inside of $flags
            $string = str_replace(['(', ')'], '', $meal['Recipe']);
            $sections = explode('&&', $string);
            $reqs = [];
            foreach ($sections as $section) {
                preg_match_all('/"([^"]+)"/', $section, $matches);
                $reqs[] = $matches[1];
            }
            $count = 0;
            foreach ($reqs as $req) foreach ($req as $r) foreach ($flags as $key => $arr) if ($r == $key) {
                $count++;
                break;
            } else foreach ($arr as $values) if ($r == $values) {
                $count++;
                break;
            }
        }

        //return $possible_meals;
        //Let's try to find the best match first!
        
        $ordered = [];
        foreach ($possible_meals as $meal)
        {
            $parsed = $parsedRecipe($meal['Recipe']);
            $count = count($parsed['required']) + count($parsed['optional']);
            $ordered[$count][] = $meal;
        }
        krsort($ordered);
        //var_dump('[REORDERED]', $ordered); //Sort $ordered by descending key

        //Generic meals should never be preferred, so push them to the end
        $meals = array_shift($ordered);
        $found = false;
        foreach ($meals as $key => $meal) {
            if (strpos($meal['Recipe'], 'Cook') !== false) {
                unset($meals[$key]);
                $meals[] = $meal;
            } else $found = true;
        }
        if (!$found) $meal = array_shift($meals);
        else $meal = array_shift($meals);

        //Meals will have a modifier if an ingredient with one is used and no other conflicting modifiers are found in other ingredients
        $modifier = [];
        $effectType = [];
        foreach ($ingredients as $ingredient) if ($ingredient && $ingredient->getModifier() && $search = str_replace(['CookInsect (', ')'], '', $ingredient->getModifier())) if (!in_array($search, $modifier)) $modifier[] = $search;
        foreach ($ingredients as $ingredient) if ($ingredient && $ingredient->getEffectType() && $search = $ingredient->getEffectType()) if ($search != 'None' && !in_array($search, $effectType)) $effectType[] = $search;
        if (count($modifier) == 1) $meal['Euen name'] = $modifier[0] . " " . $meal['Euen name'];
        if (count($effectType) == 1) $meal['effectType'] = $effectType[0];
        else $meal['effectType'] = 'None';
        //var_dump('[MODIFIER]', $modifier);
        //var_dump('[EFFECTTYPE]', $effectType);

        $this->setMeal($meal);

        //We should have the correct meal now! We just need to figure out the stats.

        //

        return $meal;
    }

    public function getMaterials(): Array {
        return $this->materials;
    }

    public function setMaterials(Array $materials): void {
        $this->materials = $materials;
    }

    public function getMaterialsCollection(): Collection {
        return $this->materials_collection;
    }

    public function setMaterialsCollection(Collection $materials_collection): void {
        $this->materials_collection = $materials_collection;
    }

    public function getMeals(): Array {
        return $this->meals;
    }

    public function setMeals(Array $meals): void {
        $this->meals = $meals;
    }

    public function getMealsCollection(): Collection {
        return $this->meals_collection;
    }

    public function setMealsCollection(Collection $meals_collection): void {
        $this->meals_collection = $meals_collection;
    }

    public function getRoastChilled(): Array {
        return $this->roast_chilled;
    }

    public function setRoastChilled(Array $roast_chilled): void {
        $this->roast_chilled = $roast_chilled;
    }

    public function getRoastChilledCollection(): Collection {
        return $this->roast_chilled_collection;
    }

    public function setRoastChilledCollection(Collection $roast_chilled_collection): void {
        $this->roast_chilled_collection = $roast_chilled_collection;
    }

    public function getIngredients(): array {
        return $this->ingredients;
    }

    public function setIngredients(array $ingredients): void {
        $this->ingredients = $ingredients;
    }

    public function setMeal(): array {
        return $this->meal;
    }

    public function getMeal(array $meal): void {
        $this->meal = $meal;
    }
}