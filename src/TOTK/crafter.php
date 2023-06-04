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
    private string $cooking_method = 'Cooking Pot';
    private array $materials;
    private Collection $materials_collection;
    private array $meals;
    private Collection $meals_collection;
    private array $roast_chilled;
    private Collection $roast_chilled_collection;
    private array $status_effects;
    private Collection $status_effects_collection;

    private ?array $ingredients = [];
    private ?array $meal = [];

    private ?array $crit_materials = []; //These are the materials that ALWAYS crit, regardless of stats

    private array $foodMaterials = ['CookFruit', 'CookFish', 'CookFruit', 'CookInsect', 'CookMeat', 'CookMushroom', 'CookPlant', 'Material'];
    private array $dubiousMaterial = ['CookForeign', 'CookGolem', 'CookEnemy', 'CookInsect'];
    private array $oreMaterial = ['CookOre'];
    private array $elixirMaterial = ['CookEnemy', 'CookInsect'];
    private array $fairyMaterial = ['Fairy'];

    private ?string $classification = '';
    private ?string $modifier = '';

    public function __construct(?string $cooking_method = 'Cooking Pot', ?array $materials = [], ?Collection $materials_collection = null, ?array $meals = null, ?Collection $meals_collection = null, ?array $roast_chilled = null, ?Collection $roast_chilled_collection = null, ?array $status_effects = null, ?Collection $status_effects_collection = null) {
        $this->setCookingMethod($cooking_method);
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
        if ($status_effects) $this->setStatusEffects($status_effects);
        else {
            $csv = array_map('str_getcsv', file(__DIR__ . '\CSVs\status_effects.csv'));
            $keys = array_shift($csv);
            $keys[] = 'id';
            $status_effects = array();
            $id = 0;
            foreach ($csv as $row) {
                $row[] = $id++;
                $status_effects[] = array_combine($keys, $row);
            }
            $this->setStatusEffects($status_effects);
        }
        if ($status_effects_collection) $this->setStatusEffectsCollection($status_effects_collection);
        else {
            $status_effects_collection = new Collection([], 'EffectType');
            foreach ($status_effects as $array) $status_effects_collection->pushItem($array);
            $this->setStatusEffectsCollection($status_effects_collection);
        }
    }

    public function process(Array $ingredients): array|collection|null
    {
        $this->setIngredients($ingredients);

        $flags = []; //This will be an array of arrays, where the key is the classification and the value is an array of ingredients that have that classification
        $components = [];
        $insect_modifiers = [];
        $modifiers = [];
        $categories = [];
        $int = 0;
        foreach ($ingredients as $ingredient) if ($ingredient) {
            //var_dump('[INGREDIENT]', $ingredient);
            $flags[$ingredient->getClassification()][]=$ingredient->getEuenName();
            $components[] = $ingredient->getEuenName();
            $insect_modifiers[] = $ingredient->getInsectModifier();
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
            $insect_modifiers_copy = $insect_modifiers;
            $modifiers_copy = $modifiers;
            $categories_copy = $categories;
            //var_dump('[MEAL]', $meal);
            $parsedRecipe = function ($meal)
            {
                $required = [];
                $optional = [];
                $meal = str_replace('"','', $meal); // get rid of quotes
                // get the optionals first
                if (preg_match_all('/\(([^)]+)\)/', $meal, $matches)) {
                    foreach ($matches[0] as $match) {
                        $match = substr($match,1,-1); // remove the ()
                        $items = [];
                        foreach(explode('||', $match) as $item) if (strlen(trim($item))) $items[] = trim($item);
                        $optional[] = $items;
                    }
                    $meal = preg_replace('/\(([^)]+)\)/', '', $meal);
                }
                // Required should be remaining
                foreach(explode('&&', $meal) as $item) if (strlen(trim($item))) $required[] = trim($item);
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
                        unset($insect_modifiers_copy[$key]);
                        unset($modifiers_copy[$key]);
                        unset($categories_copy[$key]);
                    } elseif (in_array($req, $insect_modifiers_copy)) {
                        $valid = true;
                        $key = array_search($req, $insect_modifiers_copy);
                        //var_dump('Found an optional Modifier match!', $o);
                        unset($components_copy[$key]);
                        unset($insect_modifiers_copy[$key]);
                        unset($modifiers_copy[$key]);
                        unset($categories_copy[$key]);
                    } elseif(in_array($req, $modifiers_copy)) {
                        $valid = true;
                        $key = array_search($req, $modifiers_copy);
                        //var_dump('Found a Modifier match!', $req);
                        unset($components_copy[$key]);
                        unset($insect_modifiers_copy[$key]);
                        unset($modifiers_copy[$key]);
                        unset($categories_copy[$key]);
                    } elseif(in_array($req, $categories_copy)) {
                        $valid = true;
                        $key = array_search($req, $categories_copy);
                        //var_dump('Found a Category match!', $req);
                        unset($components_copy[$key]);
                        unset($insect_modifiers_copy[$key]);
                        unset($modifiers_copy[$key]);
                        unset($categories_copy[$key]);
                    }
                    if (! $valid) {
                        //var_dump($meal['Euen name'] . ' is not a valid recipe! (Failed to find required) ' . $req);
                        //var_dump('[Remaining components]', $components_copy);
                        //var_dump('[Remaining insects]', $insect_modifiers_copy);
                        //var_dump('[Remaining modifiers]', $modifiers_copy);
                        //var_dump('[Remaining categories]', $categories_copy);
                        continue 2;
                    }
                }
                if (!$valid) {
                    //var_dump($meal['Euen name'] . ' is not a valid recipe! (Failed to find required)');
                    //var_dump('[Remaining components]', $components_copy);
                    //var_dump('[Remaining insects]', $insect_modifiers_copy);
                    //var_dump('[Remaining modifiers]', $modifiers_copy);
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
                            unset($insect_modifiers_copy[$key]);
                            unset($modifiers_copy[$key]);
                            unset($categories_copy[$key]);
                            continue 2;
                        } elseif (in_array($o, $insect_modifiers_copy)) {
                            $valid = true;
                            $key = array_search($o, $insect_modifiers_copy);
                            //var_dump('Found an optional Modifier match!', $o);
                            unset($components_copy[$key]);
                            unset($insect_modifiers_copy[$key]);
                            unset($modifiers_copy[$key]);
                            unset($categories_copy[$key]);
                            continue 2;
                        } elseif (in_array($o, $modifiers_copy)) {
                            $valid = true;
                            $key = array_search($o, $modifiers_copy);
                            //var_dump('Found an optional Modifier match!', $o);
                            unset($components_copy[$key]);
                            unset($insect_modifiers_copy[$key]);
                            unset($modifiers_copy[$key]);
                            unset($categories_copy[$key]);
                            continue 2;
                        } elseif (in_array($o, $categories_copy)) {
                            $valid = true;
                            $key = array_search($o, $categories_copy);
                            //var_dump('Found an optional Category match!', $o);
                            unset($components_copy[$key]);
                            unset($insect_modifiers_copy[$key]);
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

        //Generic meals should never be preferred, so push them to the end of the list
        $meals = array_shift($ordered);
        $found = false;
        if ($meals) {
            foreach ($meals as $key => $meal) if (strpos($meal['Recipe'], 'Cook') !== false) {
                unset($meals[$key]);
                $meals[] = $meal;
            } else $found = true;
            if (!$found) $meal = array_shift($meals);
            else $meal = array_shift($meals);
        } else $meal = null;

        //Meals will have a modifier if an ingredient with one is used and no other conflicting modifiers are found in other ingredients
        $insect_modifier = [];
        $modifier = [];
        $effectType = [];
        foreach ($ingredients as $ingredient) if ($ingredient && $ingredient->getInsectModifier() && $search = $ingredient->getModifier()) if (!in_array($search, $insect_modifier)) $insect_modifier[] = $search;
        foreach ($ingredients as $ingredient) if ($ingredient && $ingredient->getModifier() && $search = $ingredient->getModifier()) if (!in_array($search, $modifier)) $modifier[] = $search;
        foreach ($ingredients as $ingredient) if ($ingredient && $ingredient->getEffectType() && $search = $ingredient->getEffectType()) if ($search != 'None' && !in_array($search, $effectType)) $effectType[] = $search;
        //Set Meal's Name
        if(isset($meal['Euen name']))
            if (!(str_contains($meal['Euen name'], 'Elixir')) && !(str_contains($meal['Euen name'], 'Tonic')))
                if (count($modifier) == 1)
                    $meal['Euen name'] = $modifier[0] . ' ' . $meal['Euen name'];
        $meal['effectType'] = 'None';
        //Set Meal's EffectType
        if(isset($meal['effectType'])) {
            if (count($effectType) == 1)
                $meal['effectType'] = $effectType[0];
            if (isset($meal['Euen name'])) {
                if (str_contains($meal['Euen name'], 'Elixir')) //Elixirs ALWAYS have an effect type
                    $meal['effectType'] = $this->getStatusEffectsCollection()->get('Modifier', explode(' ', $meal['Euen name'])[0])['EffectType'];
                if (str_contains($meal['Euen name'], 'Tonic')) //Fairy Tonics NEVER have an effect type
                    $meal['effectType'] = 'None';
            }
        }
        //var_dump('[MODIFIER]', $modifier);
        //var_dump('[EFFECTTYPE]', $effectType);

        //var_dump('[MEAL]', $meal);
        $this->setMeal($meal);

        //We should have the correct meal now! We just need to figure out the stats.
        $output = [];
        $output['Meal'] = $meal;
        foreach ($ingredients as $ingredient) if ($ingredient) $output['Ingredients'][] = $ingredient;
        
        /*
         *
         * Calculate the HitPointRecovery for the Meal (Cooking Pot)
         *
         */
        $hp = 0;
        $lifeMaxUp = 0;
        switch ($this->getCookingMethod()) {
            case 'Fire':
                $hp = intval(ceil(array_shift($ingredients)->getHitPointRecover() * 1.5));
                break;
            case 'Cooking Pot':
            default:
                foreach ($ingredients as $ingredient) if ($ingredient) $hp += $ingredient->getHitPointRecover();
                $hp = ($hp * 2);
                if (isset($meal['BonusHeart'])) if ($meal['BonusHeart']) $hp += $meal['BonusHeart'];
                break;
        }
        foreach ($ingredients as $ingredient) if ($ingredient) {
            if ($ingredient->getBoostHitPointRecover()) $hp += $ingredient->getBoostHitPointRecover();
            if ($ingredient->getBoostMaxHeartLevel()) $lifeMaxUp += $ingredient->getBoostMaxHeartLevel();
            //$exStamina += $ingredient->getBoostStaminaLevel(); //This value isn't used
            if ($ingredient->getEuenName() == 'Monster Extract') {
                //TODO: Add a note to the output that Monster Extract is adding random effects for the HP recovery
                if (rand(0, 1)) $hp = 1;
                else $hp += 12;
                break;
            }
        }
        if ($hp >= 120) $hp = 120;
        if ($meal['Euen name'] == 'Dubious Food') $hp = 4;

         /*
         *
         * Calculate Critical Chance
         * 
         */
        
        if (isset($meal['effectType'])) $effectType = $meal['effectType']; else $effectType = 'None';
        $tier = ''; //NYI
        $staminaRecover = 0;
        $confirmedTime = 0;
        $hprepair = 0; //Dunno how to calculate this yet, or if it's just a status effect
        $crit = 0;
        foreach ($ingredients as $ingredient) if ($ingredient) {
            if ($ingredient->getBoostSuccessRate()) $crit += $ingredient->getBoostSuccessRate();
            if ($ingredient->getAlwaysCrits()) $crit = 100;
        }
        if ($crit > 100) $crit = 100;
        /*
         *
         * Calculate tier
         * 
         */
        $effectLevel = 0;
        foreach ($ingredients as $ingredient) if ($ingredient)
            if ($ingredient->getEffectType() == $meal['effectType'])
                $effectLevel += $ingredient->getEffectLevel();

        //Takes potency, adds up all the ingredients, and then checks the thresholds
        if ($effectType) {
            $tier = 'Low';
            $status_effect = $this->getStatusEffectsCollection()->get('EffectType', $effectType);
            if (isset($status_effect['Med Potency Threshold']) && $status_effect['Med Potency Threshold'])
                if ($effectLevel >= $status_effect['Med Potency Threshold'])
                    $tier = 'Med';
            if (isset($status_effect['High Potency Threshold']) && $status_effect['High Potency Threshold'])
                if ($effectLevel >= $status_effect['High Potency Threshold'])
                    $tier = 'High';
            $output['Tier'] = $tier;
        }
        
        /*
         *
         * Calculate duration
         *
         */
        $ingredient_names = [];
        if ($effectTypeItem = $this->status_effects_collection->get('EffectType', $effectType)) foreach ($ingredients as $ingredient) if ($ingredient) {
            //var_dump('[CALCULATION] ', 'INGREDIENT NAME', $ingredient->getEuenName());
            //var_dump('[CALCULATION] ', 'ADDING SECONDS TO DURATION', 30);
            $confirmedTime += 30; //All ingredients add 30 seconds to the duration
            //var_dump('[EFFECTs] ', $ingredient->getEffectType(), $effectType);
            if ($ingredient->getEffectType() === $effectType) {
                //var_dump('[CALCULATION] ', 'ADDING BASETIME DURATION', intval($effectTypeItem['BaseTime']));
                $confirmedTime += $effectTypeItem['BaseTime']; //Effects have their own base time
            }
            if ($ingredient->getBoostEffectiveTime()) {
                if (! in_array($ingredient->getEuenName(), $ingredient_names)) {
                    $confirmedTime += $ingredient->getBoostEffectiveTime(); //Some materials boost the duration
                    //var_dump('[CALCULATION] ', 'ADDING BOOSTEFFEECTIVETIME TO DURATION',  intval($ingredient->getBoostEffectiveTime()));
                }
                //var_dump('[CALCULATION] ', 'TRACKING DUPLICATE INGREDIENTS',  $ingredient->getEuenName());
                $ingredient_names[] = $ingredient->getEuenName(); //Track duplicates
            }
        }
        /*
         *
         * Monster Extract
         * 
        */
        if ($confirmedTime) foreach ($ingredients as $ingredient) if ($ingredient) if ($ingredient->getEuenName() == 'Monster Extract') {
            //TODO: Add a note to the output that Monster Extract is setting random duration
            $rand_array = [60, 600, 1800];
            $confirmedTime = $rand_array[array_rand($rand_array)];
            break;
        }

        //var_dump('[CALCULATION RESULTS]', intval($confirmedTime));

        
        //$confirmedTime += 0; //This value depends on the effectType, so we need to caluclate that first and then add it from that csv
        if ($effectType == 'StaminaRecover') switch ($effectLevel) {
            case 1:
                $staminaRecover = 80;
                break;
            case 2:
                $staminaRecover = 160;
                break;
            case 3:
                $staminaRecover = 280;
                break;
            case 4:
                $staminaRecover = 360;
                break;
            case 5:
                $staminaRecover = 520;
                break;
            case 6:
                $staminaRecover = 560;
                break;
            case 7:
                $staminaRecover = 640;
                break;
            case 8:
                $staminaRecover = 800;
                break;
            case 9:
                $staminaRecover = 880;
                break;
            case 10:
                $staminaRecover = 1000;
                break;
            case ($effectLevel >= 11):
                $staminaRecover = 1080;
                break;
            case 0:
            default:
                $staminaRecover = 0;
                break;
        }

        $exStamina = 0;
        if ($effectType == 'ExStaminaMaxUp') switch ($effectLevel) {
            case 1:
            case 2:
            case 3:
                $exStamina = 80;
                break;
            case 4:
            case 5:
                $exStamina = 160;
                break;
            case 6:
            case 7:
                $exStamina = 200;
                break;
            case 8:
            case 9:
                $exStamina = 280;
                break;
            case 10:
            case 11:
                $exStamina = 360;
                break;
            case 12:
            case 13:
                $exStamina = 440;
                break;
            case 14:
            case 15:
                $exStamina = 520;
                break;
            case 16:
            case 17:
                $exStamina = 560;
                break;
            case 18:
            case 19:
                $exStamina = 640;
                break;
            case ($effectLevel >= 20):
                $exStamina = 720;
                break;
            case 0:
            default:
                $exStamina = 0;
                break;
        }
        
        /*
         *
         * EffectType-specific calculations
         * 
         */
        if (isset($meal['effectType'])) if ($meal['effectType']) if (in_array($meal['effectType'], ['None', 'ExStaminaMaxUp', 'StaminaRecover', 'LifeMaxUp', 'LifeRepair', 'LifeRecover'])) $confirmedTime = 0;
        if (isset($meal['effectType'])) if ($meal['effectType']) if ($meal['effectType'] === 'LifeMaxUp') $hp = 120;
        if (isset($meal['effectType'])) if ($meal['effectType']) if ($meal['effectType'] === 'ExStaminaMaxUp') $staminaRecover = 1080;

        if (isset($meal['Euen name'])) $output['Meal Name'] = $meal['Euen name']; else $output['Meal Name'] = '';
        if ($effectType) $output['EffectType'] = $effectType;
        if ($effectLevel) $output['EffectLevel'] = $effectLevel;
        if ($hprepair) $output['HitPointRepair'] = $hprepair;
        if ($confirmedTime) $output['ConfirmedTime'] = $confirmedTime; //Actual duration for effect types
        if ($hp) $output['HitPointRecover'] = $hp;
        if ($hp >= 120) $output['HitPointRecover'] = 'Full Restore';
        if ($lifeMaxUp) $output['LifeMaxUp'] = $lifeMaxUp;
        if ($staminaRecover) $output['StaminaRecover'] = $staminaRecover;
        if ($exStamina) $output['ExStamina'] = $exStamina;
        if ($crit) $output['CriticalChance'] = $crit;
        return $output;
    }

    public function getCookingMethod(): string {
        return $this->cooking_method;
    }

    public function setCookingMethod(string $cooking_method): void {
        $this->cooking_method = $cooking_method;
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

    public function getStatusEffects(): Array {
        return $this->status_effects;
    }

    public function setStatusEffects(Array $status_effects): void {
        $this->status_effects = $status_effects;
    }

    public function getStatusEffectsCollection(): Collection {
        return $this->status_effects_collection;
    }

    public function setStatusEffectsCollection(Collection $status_effects_collection): void {
        $this->status_effects_collection = $status_effects_collection;
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