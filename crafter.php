<?php

class Crafter {
    private Array $materials;
    private Collection $materials_collection;
    private Array $meals;
    private Collection $meals_collection;
    private Array $roast_chilled;
    private Collection $roast_chilled_collection;

    private ?Recipe $recipe = null;

    private array $foodMaterials = ['CookFruit', 'CookFish', 'CookFruit', 'CookInsect', 'CookMeat', 'CookMushroom', 'CookPlant', 'Material'];
    private array $dubiousMaterial = ['CookForeign', 'CookGolem', 'CookEnemy', 'CookInsect'];
    private array $oreMaterial = ['CookOre'];
    private array $elixirMaterial = ['CookEnemy', 'CookInsect'];
    private array $fairyMaterial = ['Fairy'];

    

    private ?string $classification = '';
    private ?string $modifier = '';

    public function __construct(Array $materials, Collection $materials_collection, Array $meals, Collection $meals_collection, Array $roast_chilled, Collection $roast_chilled_collection) {
        $this->materials = $materials;
        $this->materials_collection = $materials_collection;
        $this->meals = $meals;
        $this->meals_collection = $meals_collection;
        $this->roast_chilled = $roast_chilled;
        $this->roast_chilled_collection = $roast_chilled_collection;
    }

    public function process(Recipe $recipe): array|collection|null
    {
        $this->setRecipe($recipe);

        $flags = []; //This will be an array of arrays, where the key is the classification and the value is an array of ingredients that have that classification
        $components = [];
        $categories = [];
        $int = 0;
        foreach ($ingredients = $this->getRecipe()->getIngredients() as $ingredient) {
            $flags[$ingredient->getClassification()][]=$ingredient->getEuenName();
            $components[] = $ingredient->getEuenName();
            $categories[] = $ingredient->getClassification();
            $int++;
        }
        var_dump('[COMPONENTS]', $components);
        var_dump('[CATEGORIES]', $categories);
        var_dump('[FLAGS]', $flags);


        $possible_meals = [];
        foreach ($this->getMeals() as $meal) {
            $components_copy = $components;
            $categories_copy = $categories;
            var_dump('[MEAL]', $meal);
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
            var_dump('[PARSED RECIPE]', $parsedRecipe = $parsedRecipe($meal['Recipe']));

            //For each array of arrays inside of $reqs, check if at least one ingredient or category is in the recipe, and if it is then remove it from the classifications array and move on to the next array of arrays
            $valid = false;
            if ($parsedRecipe['required']) {
                $valid = false;
                foreach ($parsedRecipe['required'] as $req) {
                    if (in_array($req, $components_copy)) {
                        $valid = true;
                        $key = array_search($req, $components_copy);
                        var_dump('Found a component match!', $req);
                        unset($components_copy[$key]);
                        unset($categories_copy[$key]);
                    } else {
                        foreach ($categories_copy as $key => $category) {
                            if ($category == $req) {
                                $valid = true;
                                var_dump('Found a category match!', $req);
                                unset($components_copy[$key]);
                                unset($categories_copy[$key]);
                            }
                        }
                    }
                }
                if (!$valid) {
                    var_dump($meal['Euen name'] . ' is not a valid recipe! (Failed to find required)');
                    var_dump('[Remaining components]', $components_copy);
                    var_dump('[Remaining categories]', $categories_copy);
                    continue;
                }
            }
            if ($parsedRecipe['optional']) {
                $valid = false;
                foreach (array_values($parsedRecipe['optional']) as $req) {
                    if (in_array($req, $components_copy)) {
                        $valid = true;
                        $key = array_search($req, $components_copy);
                        var_dump('Found a component match!', $req);
                        unset($components_copy[$key]);
                        unset($categories_copy[$key]);
                    } else {
                        foreach ($categories_copy as $key => $category) {
                            if ($category == $req) {
                                $valid = true;
                                var_dump('Found a category match!', $req);
                                unset($components_copy[$key]);
                                unset($categories_copy[$key]);
                            }
                        }
                    }
                }
                if (!$valid) {
                    var_dump($meal['Euen name'] . ' is not a valid recipe! (Failed to find optional)');
                    var_dump('[Remaining components]', $components_copy);
                    var_dump('[Remaining categories]', $categories_copy);
                    continue;
                }
            }
            
            if ($valid) {
                var_dump($meal['Euen name'] . ' is a valid recipe!');
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
            foreach ($reqs as $req) {
                foreach ($req as $r) {
                    foreach ($flags as $key => $arr) {
                        if ($r == $key) {
                            $count++;
                            break;
                        } else foreach ($arr as $values) {
                            if ($r == $values) {
                                $count++;
                                break;
                            }
                        }
                    }
                }
            }
        }

        return $possible_meals;
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

    public function getRecipe(): Recipe {
        return $this->recipe;
    }

    public function setRecipe(Recipe $recipe): void {
        $this->recipe = $recipe;
    }
}