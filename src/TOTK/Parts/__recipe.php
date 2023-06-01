<?php

/*
 * This file is a part of the TOTK Recipe Calculator project.
 *
 * Copyright (c) 2023-present Valithor Obsidion <valzargaming@gmail.com>
 *
 * This file is subject to the MIT license that is bundled
 * with this source code in the LICENSE.md file.
 */

namespace TOTK\Parts;

//TODO: Potency should affect the efficiency of the effect type, but the data dump doesn't have that info?

class Recipe {
    private ?array $ingredients = []; //array of Ingredient objects (max of 5)
    private ?string $cookingMethod = ''; //Cooking Pot, Fire, Frozen, Hot Spring (Single Egg Only)
    private ?array $meal = [];
    
    private ?string $name = '';
    private ?string $classification = ''; //Food, Elixer, Dubious, or Rock Hard
    private ?int $sellingPrice = 0;
    private ?int $effectLevel = 0; //Potency, (Stamina Recovery = Potency * ~90)
    private ?string $effectType = ''; //Buff [LifeRecover (Critical only), LifeMaxUp, StaminaRecover, ExStaminaMaxUp, ResistHot, ResistCold, ResistElectric, AllSpeed, AttackUp, DefenseUp, QuietnessUp, ResistBurn,, TwiceJump, EmergencyAvoid, LifeRepair, LightEmission, NotSlippy, SwimSpeedUp, AttackUpCold,AttackUpHot, AttackUpThunderstorm, MiasmaGuard]
    private ?int $effectiveTime = 0; //Duration in microseconds (?) (900 = 30 seconds for 1 item? Seems wrong)
    private ?int $hitPointRecover = 0; //Quarters of a Heart

    private ?string $potency = ''; //Normal, Med, High
    private ?int $staminaRecover = 0;
    private ?int $exStaminaMaxUp = 0;
    private ?int $lifeMaxUp = 0;

    public function __construct(array $meal, array $ingredients = [], ?string $cookingMethod = '', ?string $name = '', ?string $classification = '', ?int $sellingPrice = 0, ?int $effectLevel = 0, ?string $effectType = '', ?int $effectiveTime = 0, ?int $hitPointRecover = 0) {
        $this->meal = $meal;
        $this->setIngredients($ingredients); //Allow passing NULL to skip an ingredient slot
        $cookingMethod ? ($this->cookingMethod = $cookingMethod) : ($this->cookingMethod = 'Cooking Pot');
        
        if ($name) $this->name = $name;
        if ($classification) $this->classification = $classification;
        if ($sellingPrice) $this->sellingPrice = $sellingPrice;
        
        $effectType ? $this->setEffectType($effectType) : $this->calcEffectType();
        $effectLevel ? $this->setEffectLevel($effectLevel) : $this->calcEffectLevel();
        $this->calcPotency();
        $effectiveTime ? $this->setEffectiveTime($effectiveTime) : $this->calcEffectiveTime();
        $hitPointRecover ? $this->sethitPointRecover($hitPointRecover) : $this->calchitPointRecover();

        //$this->calcClassification(); //This is wrong
    }

    public function getName() {
        return $this->name;
    }

    private function setName(string $name) {
        $this->name = $name;
    }

    public function getClassification() {
        return $this->classification;
    }

    private function setClassification(string $classification) {
        $this->classification = $classification;
    }

    public function getCookingMethod() {
        return $this->cookingMethod;
    }

    private function setCookingMethod(string $cookingMethod) {
        $this->cookingMethod = $cookingMethod;
    }

    public function getIngredients() {
        return $this->ingredients;
    }

    private function setIngredients(array $ingredients) {
        foreach ($ingredients as $ingredient) {
            if (!($ingredient instanceof Ingredient)) continue;
            $this->addIngredient($ingredient);
        }
    }

    public function addIngredient(Ingredient $ingredient) {
        if (count($this->ingredients) < 5) {
            array_push($this->ingredients, $ingredient);
        } else {
            throw new \Exception("Cannot add more than 5 ingredients.");
        }
    }

    public function removeIngredient(Ingredient $ingredient) {
        $index = array_search($ingredient, $this->ingredients);
        if ($index !== false) {
            array_splice($this->ingredients, $index, 1);
        }
    }

    public function getSellingPrice() {
        return $this->sellingPrice;
    }

    private function setSellingPrice(int $sellingPrice) {
        $this->sellingPrice = $sellingPrice;
    }

    public function getEffectLevel() {
        return $this->effectLevel;
    }

    private function setEffectLevel(int $effectLevel) {
        $this->effectLevel = $effectLevel;
    }

    public function getEffectType() {
        return $this->effectType;
    }

    private function setEffectType(string $effectType) {
        $this->effectType = $effectType;
    }

    public function getEffectiveTime() {
        return $this->effectiveTime;
    }

    private function setEffectiveTime(int $effectiveTime) {
        $this->effectiveTime = $effectiveTime;
    }

    public function gethitPointRecover() {
        return $this->hitPointRecover;
    }

    private function sethitPointRecover(int $hitPointRecover) {
        $this->hitPointRecover = $hitPointRecover;
    }

    public function getPotency() {
        return $this->potency;
    }

    private function setPotency(string $potency) {
        $this->potency = $potency;
    }

    public function getLifeMaxUp() {
        return $this->lifeMaxUp;
    }

    private function setLifeMaxUp(null|int $lifeMaxUp) {
        $this->lifeMaxUp = $lifeMaxUp;
    }

    public function getStaminaRecover() {
        return $this->staminaRecover;
    }

    private function setStaminaRecover(null|int $staminaRecover) {
        $this->staminaRecover = $staminaRecover;
    }

    public function getExStaminaMaxUp() {
        return $this->exStaminaMaxUp;
    }

    private function setExStaminaMaxUp(null|int $exStaminaMaxUp) {
        $this->exStaminaMaxUp = $exStaminaMaxUp;
    }

    private function calcEffectiveTime(): void
    {
        $dur = 0;
        foreach ($this->getIngredients() as $ingredient) $dur += $ingredient->getEffectiveTime()+300; //Add 30 seconds for each ingredient in the dish (?)
        if ($dur > 1800) $dur = 1800; //Max duration is 30 minutes
        $this->setEffectiveTime($dur);
    }
    
    private function calcEffectType(): void
    {
        $effects = [];
        foreach ($this->getIngredients() as $ingredient) if (! in_array($effectType = $ingredient->getEffectType(), $effects)) $effects[] = $effectType;
        if (count($effects) !== 1) $this->setEffectType($effect = 'None');
        else $this->setEffectType($effect = $effects[0]);

        switch ($effect) {
            case 'LifeMaxUp': //Not sure if there's a max
                $this->setLifeMaxUp($this->getEffectLevel());
                break;
            case 'StaminaRecover':
                switch ($effectLevel = $this->getEffectLevel()) {
                    case 1:
                        $this->setStaminaRecover(80);
                        break;
                    case 2:
                        $this->setStaminaRecover(160);
                        break;
                    case 3:
                        $this->setStaminaRecover(280);
                        break;
                    case 4:
                        $this->setStaminaRecover(360);
                        break;
                    case 5:
                        $this->setStaminaRecover(520);
                        break;
                    case 6:
                        $this->setStaminaRecover(560);
                        break;
                    case 7:
                        $this->setStaminaRecover(640);
                        break;
                    case 8:
                        $this->setStaminaRecover(800);
                        break;
                    case 9:
                        $this->setStaminaRecover(880);
                        break;
                    case 10:
                        $this->setStaminaRecover(1000);
                        break;
                    case ($effectLevel >= 11):
                        $this->setStaminaRecover(1080);
                        break;
                    case 0:
                    default:
                        $this->setStaminaRecover(0);
                        break;
                }
                break;
            case 'ExStaminaMaxUp':
                switch ($effectLevel = $this->getEffectLevel()) {
                    case 1:
                    case 2:
                    case 3:
                        $this->setExStaminaMaxUp(280);
                        break;
                    case 4:
                    case 5:
                        $this->setExStaminaMaxUp(520);
                        break;
                    case 6:
                    case 7:
                        $this->setExStaminaMaxUp(640);
                        break;
                    case 8:
                    case 9:
                        $this->setExStaminaMaxUp(880);
                        break;
                    case 10:
                    case 11:
                        $this->setExStaminaMaxUp(1080);
                        break;
                    case 12:
                    case 13:
                        $this->setExStaminaMaxUp(1080);
                        break;
                    case 14:
                    case 15:
                            $this->setExStaminaMaxUp(1080);
                            break;
                    case 16:
                    case 17:
                        $this->setExStaminaMaxUp(1080);
                        break;
                    case 18:
                    case 19:
                        $this->setExStaminaMaxUp(1080);
                        break;
                    case ($effectLevel >= 20):
                        $this->setExStaminaMaxUp(1080);
                        break;
                    case 0:
                    default:
                        $this->setExStaminaMaxUp(0);
                        break;
                }
            default:
                //
                break;
        }
    }

    private function calcEffectLevel(): void
    {
        $level = 0;
        foreach ($this->getIngredients() as $ingredient) $level += $ingredient->getEffectLevel();
        $this->setEffectLevel($level);
    }

    private function calcPotency(): void
    {
        $potency = $this->getEffectLevel();
        if ($potency >= 45) $this->setPotency('Strong');
        elseif ($potency >= 30) $this->setPotency('Med');
        else $this->setPotency('Normal');
    }

    private function calchitPointRecover(): void
    {
        $hitPointRecover = 0;
        foreach ($this->getIngredients() as $ingredient) $hitPointRecover += $ingredient->gethitPointRecover();

        switch ($this->getCookingMethod()) {
            case 'Fire':
                $this->sethitPointRecover(intval(ceil($hitPointRecover*1.5)));
                break;
            case 'Frozen':
                $this->sethitPointRecover($hitPointRecover);
                break;
            case 'Cooking Pot':
            default:
                $hitPointRecover *= 2;
                if ($hitPointRecover >= 30) {
                    $this->sethitPointRecover(30);
                    //If >=30, full restore instead
                }
                else $this->sethitPointRecover($hitPointRecover);
                break;
        }
    }

    private function calcMeal(): void
    {
        //
    }
    
    //This needs to be reworked to follow the the Meals csv
    /*private function calcClassification(): void
    {
        $classifications = [];
        foreach ($this->getIngredients() as $ingredient) if (! in_array($classification = $ingredient->getClassification(), $classifications)) $classifications[] = $classification;
        $this->setClassification($classification = $classifications[0]);
        if (count($classifications) !== 1) $this->setClassification($classification = 'Dubious');
        foreach ($this->getIngredients() as $ingredient) if ($ingredient->getRockHard()) { $this->setClassification($classification = 'Rock Hard'); break;}

        switch ($this->getClassification()) {
            case 'Dubious':
                $this->setEffectLevel(0);
                $this->setEffectType('None');
                $this->setEffectiveTime(0);
                $this->sethitPointRecover(4);
                $this->setPotency('Normal');
                $this->setStaminaRecover(null);
                $this->setExStaminaMaxUp(null);
                $this->setLifeMaxUp(null);
                break;
            case 'Rock Hard':
                $this->setEffectLevel(0);
                $this->setEffectType('None');
                $this->setEffectiveTime(0);
                $this->sethitPointRecover(1);
                $this->setPotency('Normal'); //Normal, Med, High
                $this->setStaminaRecover(null);
                $this->setExStaminaMaxUp(null);
                $this->setLifeMaxUp(null);
                break;
        }
    }*/

    public function __toString() {
        return $this->getName();
    }
}
