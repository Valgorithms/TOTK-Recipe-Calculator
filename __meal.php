<?php

class Meal {
    private ?string $actorName = '';
    private ?string $euenName = '';
    private ?int $recipen° = 0;
    private ?array $recipe = []; //Array of Euen Names, Classifications, and Modifiers
    private ?int $bonusHeart = 0; //Milk, Fruitcake, and Fairy Tonic only
    private ?int $bonusLevel = 0; //Nothing uses this
    private ?int $bonusTime = 0; //Nothing uses this

    private ?string $cookingMethod = 'Cooking Pot'; //Cooking Pot, Fire, Frozen, Hot Spring (Single Egg Only)

    public function __construct(?string $actorName = '', ?string $euenName = '', ?int $recipen° = 0, ?array $recipe = [], ?int $bonusHeart = 0, ?int $bonusLevel = 0, ?int $bonusTime = 0, ?string $cookingMethod = 'Cooking Pot') {
        $this->actorName = $actorName;
        $this->euenName = $euenName;
        $this->recipen° = $recipen°;
        $this->recipe = $recipe;
        $this->bonusHeart = $bonusHeart;
        $this->bonusLevel = $bonusLevel;
        $this->bonusTime = $bonusTime;
        $this->cookingMethod = $cookingMethod;
    }

    public function getActorName(): ?string {
        return $this->actorName;
    }

    public function setActorName(?string $actorName): void {
        $this->actorName = $actorName;
    }

    public function getEuenName(): ?string {
        return $this->euenName;
    }

    public function setEuenName(?string $euenName): void {
        $this->euenName = $euenName;
    }

    public function getRecipen°(): ?int {
        return $this->recipen°;
    }

    public function setRecipen°(?int $recipen°): void {
        $this->recipen° = $recipen°;
    }

    public function getRecipe(): ?array {
        return $this->recipe;
    }

    public function setRecipe(?array $recipe): void {
        $this->recipe = $recipe;
    }

    public function getBonusHeart(): ?int {
        return $this->bonusHeart;
    }

    public function setBonusHeart(?int $bonusHeart): void {
        $this->bonusHeart = $bonusHeart;
    }

    public function getBonusLevel(): ?int {
        return $this->bonusLevel;
    }

    public function setBonusLevel(?int $bonusLevel): void {
        $this->bonusLevel = $bonusLevel;
    }

    public function getBonusTime(): ?int {
        return $this->bonusTime;
    }

    public function setBonusTime(?int $bonusTime): void {
        $this->bonusTime = $bonusTime;
    }

    public function getCookingMethod(): ?string {
        return $this->cookingMethod;
    }

    public function setCookingMethod(?string $cookingMethod): void {
        $this->cookingMethod = $cookingMethod;
    }
}