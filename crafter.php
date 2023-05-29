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

        $classifications = [];
        foreach ($this->getRecipe()->getIngredients() as $ingredient) {
            $classifications[] = $ingredient->getEuenName();
            $classifications[] = $ingredient->getClassification();
        }
        //var_dump('CLASSIFICATIONS', $classifications);

        
        $checkArrayValues = function ($firstArray, $conditionString) {
            // Remove extra quotation marks from the first array
            $cleanFirstArray = array_map(function ($value) {
                return trim($value, "\"'");
            }, $firstArray);

            // Remove excess quotation marks and whitespace from the condition string
            $cleanConditionString = preg_replace('/"([^"]+)"/', '$1', $conditionString);
            $cleanConditionString = trim($cleanConditionString);

            $conditions = explode("&&", $cleanConditionString);
            $requiredValues = [];
            foreach ($conditions as $condition) {
                $conditionValues = explode("&&", $condition);
                $conditionValues = array_map(function ($value) {
                    return trim($value, "\"'");
                }, $conditionValues);
                $requiredValues[] = $conditionValues;
            }

            //echo "First Array: ";
            //print_r($cleanFirstArray);

            echo "Required Values: ";
            $cleanRequiredValues = array_map(function ($values) {
                return array_map('trim', $values);
            }, $requiredValues);
            var_export($cleanRequiredValues);

            foreach ($requiredValues as $values) {
                $found = false;
                foreach ($values as $value) {
                    if (in_array($value, $cleanFirstArray)) {
                        $found = true;
                        break;
                    }
                }
                if (!$found) {
                    return false;
                }
            }

            return true;
        };

        var_dump('[CLASSIFICATIONS]', $classifications);
        $possible_meals = [];
        foreach ($this->getMeals() as $meal) {
            $expression = $meal['Recipe'];
            if ($checkArrayValues($classifications, $expression)) {
                $possible_meals[] = $meal;
            }
        }

        var_dump('POSSIBLE MEALS', $possible_meals);

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