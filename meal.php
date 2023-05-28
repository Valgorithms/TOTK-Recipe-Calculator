<?php

//TODO: Potency should affect the efficiency of the effect type, but the data dump doesn't have that info?

class Meal {
    private ?array $ingredients = []; //array of Ingredient objects (max of 5)
    private ?string $cookingMethod = ''; //Cooking Pot, Fire, Frozen, Hot Spring (Single Egg Only)
    
    private ?string $actorName = '';
    private ?string $euenName = '';
    private ?int $recipen° = 0;
    private ?array $recipe = [];
    private ?int $bonusHeart = 0; //Milk, Fruitcake, and Fairy Tonic only
    private ?int $bonusLevel = 0; //Nothing uses this
    private ?int $bonusTime = 0; //Nothing uses this

    public function __construct(array $ingredients, ?string $cookingMethod = '', ?string $actorName = '', ?string $euenName = '', ?int $recipen° = 0, ?array $recipe = [], ?int $bonusHeart = 0, ?int $bonusLevel = 0, ?int $bonusTime = 0) {
        //
    }
}