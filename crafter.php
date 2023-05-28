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

    private ?bool $hasFoodMaterial = false;
    private ?bool $hasDubiousMaterial = false;
    private ?bool $hasOreMaterial = false;
    private ?bool $hasElixirMaterial = false;
    private ?bool $hasFairyMaterial = false;

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

    public function process(Recipe $recipe): array|collection {
        $this->setRecipe($recipe);
        foreach ($this->getRecipe()->getIngredients() as $ingredient) {
            if (in_array($ingredient->getClassification, $this->foodMaterials)) $this->hasFoodMaterial = true;
            if (in_array($ingredient->getClassification, $this->dubiousMaterial)) $this->hasDubiousMaterial = true;
            if (in_array($ingredient->getClassification, $this->oreMaterial)) $this->hasOreMaterial = true;
            if (in_array($ingredient->getClassification, $this->elixirMaterial)) $this->hasElixirMaterial = true;
            if (in_array($ingredient->getClassification, $this->fairyMaterial)) $this->hasFairyMaterial = true;
        }
        //TODO
        return []; //Placeholder
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