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

class Ingredient {
    private ?string $actorName = '';
    private ?string $euenName = '';
    private ?string $classification = ''; //Cooking name [e.g. CookEnemy, CookInsect, etc.]
    private ?string $modifier = ''; //Recipe prefix derived from erffectType (e.g. Spicy, Electro, etc.)
    private ?string $insect_modifier = ''; //Insects specifically are used in Tonics, but can also substitute for any modifier
    private ?int $buyingPrice = 0;
    private ?int $sellingPrice = 0;
    private ?string $color = ''; //Color of the ingredient, used for Dyes (e.g. Red, Yellow, etc.)
    private ?int $additionalDamage = 0; //For weapon fusing mechanics
    private ?int $effectLevel = 0; //Potency
    private ?string $effectType = ''; //Buff [LifeRecover, LifeMaxUp, StaminaRecover, ExStaminaMaxUp, ResistHot, ResistCold, ResistElectric, AllSpeed, AttackUp, DefenseUp, QuietnessUp, ResistBurn,, TwiceJump, EmergencyAvoid, LifeRepair, LightEmission, NotSlippy, SwimSpeedUp, AttackUpCold,AttackUpHot, AttackUpThunderstorm, MiasmaGuard]
    
    private ?bool $seasoning = false;
    private ?int $seasoningBoost = 0;
    private ?bool $alwaysCrits = false;
    private ?int $confirmedTime = 0; //Seconds, some parts always boost duration by a flat amount
    
    private ?int $hitPointRecover = 0; //Quarters of a Heart
    private ?int $boostEffectiveTime = 0; //Crit chance
    private ?int $boostHitPointRecover = 0; //
    private ?int $boostMaxHeartLevel = 0; //
    private ?int $boostStaminaLevel = 0; //
    private ?int $boostSuccessRate = 0; //

    public function __construct(array|string $primary, ?string $euenName = '', ?string $classification = '', ?string $modifier = '', ?int $buyingPrice = 0, ?int $sellingPrice = 0, ?string $color = '', int|string|null $additionalDamage = 0, ?int $effectLevel = 0, ?string $effectType = '', ?bool $seasoning = false, ?int $seasoningBoost = 0, ?bool $alwaysCrits = false, ?int $confirmedTime = 0, ?int $hitPointRecover = 0, ?int $boostEffectiveTime = 0) {
        if (is_array($primary)) {
            $this->actorName = $primary['ActorName'];
            $this->euenName = $primary['Euen name'];
            $this->classification = $primary['Classification'];
            $this->modifier = $primary['Modifier'];
            if (str_contains($primary['Modifier'], 'CookInsect')) $this->insect_modifier = $primary['Modifier'];
            $this->buyingPrice = $primary['BuyingPrice'];
            $this->sellingPrice = $primary['SellingPrice'];
            $this->color = $primary['Color'];

            if (is_int($primary['AdditionalDamage'])) $this->additionalDamage = $primary['AdditionalDamage'];
            else $this->additionalDamage = 0;
            $this->effectLevel = $primary['EffectLevel'];
            $this->effectType = $primary['EffectType'];
            $this->seasoning = boolval($primary['Seasoning']);
            $this->seasoningBoost = boolval($primary['seasoningBoost']);
            $this->alwaysCrits = boolval($primary['AlwaysCrits']);
            $this->confirmedTime = intval($primary['ConfirmedTime']);
            $this->hitPointRecover = intval($primary['HitPointRecover']);
            $this->boostEffectiveTime = intval($primary['BoostEffectiveTime']);
        } else {
            $this->actorName = $primary;
            $this->euenName = $euenName;
            $this->classification = $classification;
            $this->modifier = $modifier;
            $this->buyingPrice = $buyingPrice;
            $this->sellingPrice = $sellingPrice;
            $this->color = $color;
            $this->additionalDamage = $additionalDamage;
            $this->effectLevel = $effectLevel;
            $this->effectType = $effectType;
            $this->seasoning = $seasoning;
            $this->seasoningBoost = $seasoningBoost;
            $this->alwaysCrits = $alwaysCrits;
            $this->confirmedTime = $confirmedTime;
            $this->hitPointRecover = $hitPointRecover;
            $this->boostEffectiveTime = $boostEffectiveTime;
        }
    }

    public function getActorName() {
        return $this->actorName;
    }

    public function setActorName(?string $actorName) {
        $this->actorName = $actorName;
    }

    public function getEuenName() {
        return $this->euenName;
    }

    public function setEuenName(?string $euenName) {
        $this->euenName = $euenName;
    }

    public function getClassification() {
        return $this->classification;
    }

    public function setClassification(?string $classification) {
        $this->classification = $classification;
    }

    public function getModifier() {
        return trim(str_replace(['CookInsect [', ']'], '', $this->modifier));
    }

    public function setModifier(?string $modifier) {
        $this->modifier = $modifier;
    }

    public function getInsectModifier() {
        return $this->insect_modifier;
    }

    public function setInsectModifier(?string $insect_modifier) {
        $this->insect_modifier = $insect_modifier;
    }

    public function getBuyingPrice() {
        return $this->buyingPrice;
    }

    public function setBuyingPrice(?int $buyingPrice) {
        $this->buyingPrice = $buyingPrice;
    }

    public function getSellingPrice() {
        return $this->sellingPrice;
    }

    public function setSellingPrice(?int $sellingPrice) {
        $this->sellingPrice = $sellingPrice;
    }

    public function getColor() {
        return $this->color;
    }

    public function setColor(?string $color) {
        $this->color = $color;
    }

    public function getAdditionalDamage() {
        return $this->additionalDamage;
    }

    public function setAdditionalDamage(?int $additionalDamage) {
        $this->additionalDamage = $additionalDamage;
    }

    public function getEffectLevel() {
        return $this->effectLevel;
    }

    public function setEffectLevel(?int $effectLevel) {
        $this->effectLevel = $effectLevel;
    }

    public function getEffectType() {
        return $this->effectType;
    }

    public function setEffectType(?string $effectType) {
        $this->effectType = $effectType;
    }

    public function getSeasoning() {
        return $this->seasoning;
    }

    public function setSeasoning(?bool $seasoning) {
        $this->seasoning = $seasoning;
    }

    public function getSeasoningBoost() {
        return $this->seasoningBoost;
    }

    public function setSeasoningBoost(?int $seasoningBoost) {
        $this->seasoningBoost = $seasoningBoost;
    }
    
    public function getAlwaysCrits() {
        return $this->alwaysCrits;
    }

    public function setAlwaysCrits(?bool $alwaysCrits) {
        $this->alwaysCrits = $alwaysCrits;
    }

    public function getConfirmedTime() {
        return $this->confirmedTime;
    }

    public function setConfirmedTime(?bool $confirmedTime) {
        $this->confirmedTime = $confirmedTime;
    }

    public function getHitPointRecover() {
        return $this->hitPointRecover;
    }

    public function setHitPointRecover(?int $hitPointRecover) {
        $this->hitPointRecover = $hitPointRecover;
    }

    public function getBoostEffectiveTime() {
        return $this->boostEffectiveTime;
    }

    public function setBoostEffectiveTime(?int $boostEffectiveTime) {
        $this->boostEffectiveTime = $boostEffectiveTime;
    }

    public function getBoostHitPointRecover() {
        return $this->boostHitPointRecover;
    }

    public function setBoostHitPointRecover(?int $boostHitPointRecover) {
        $this->boostHitPointRecover = $boostHitPointRecover;
    }
    
    public function getBoostMaxHeartLevel() {
        return $this->boostMaxHeartLevel;
    }

    public function setBoostMaxHeartLevel(?int $boostMaxHeartLevel) {
        $this->boostMaxHeartLevel = $boostMaxHeartLevel;
    }
    
    public function getBoostSuccessRate() {
        return $this->boostSuccessRate;
    }

    public function setBoostStaminaLevel(?int $boostStaminaLevel) {
        $this->boostStaminaLevel = $boostStaminaLevel;
    }
    
    public function getBoostStaminaLevel() {
        return $this->boostStaminaLevel;
    }

    public function setBoostSuccessRate(?int $boostSuccessRate) {
        $this->boostSuccessRate = $boostSuccessRate;
    }

    public function __toString() {
        return $this->getEuenName();
    }
}