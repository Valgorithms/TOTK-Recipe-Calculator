<?php
class Ingredient {
    private ?string $actorName;
    private ?string $euenName;
    private ?string $classification; //Food, Elixer
    private ?bool $rockHard; //Ingredients that make the recipe become rock hard food (e.g. Wood, Ruby, etc.)
    private ?int $buyingPrice;
    private ?int $sellingPrice;
    private ?int $effectLevel; //Potency
    private ?string $effectType; //Buff [LifeRecover, LifeMaxUp, StaminaRecover, ExStaminaMaxUp, ResistHot, ResistCold, ResistElectric, AllSpeed, AttackUp, DefenseUp, QuietnessUp, ResistBurn,, TwiceJump, EmergencyAvoid, LifeRepair, LightEmission, NotSlippy, SwimSpeedUp, AttackUpCold,AttackUpHot, AttackUpThunderstorm, MiasmaGuard]
    private ?int $effectiveTime; //Duration
    private ?int $hitPointRecovery; //Quarters of a Heart

    public function __construct(?string $actorName, ?string $euenName, ?string $classification, ?bool $rockHard, ?int $buyingPrice, ?int $sellingPrice, ?int $effectLevel, ?string $effectType, ?int $effectiveTime, ?int $hitPointRecovery) {
        $this->actorName = $actorName;
        $this->euenName = $euenName;
        $this->classification = $classification;
        $this->rockHard = $rockHard;
        $this->buyingPrice = $buyingPrice;
        $this->sellingPrice = $sellingPrice;
        $this->effectLevel = $effectLevel;
        $this->effectType = $effectType;
        $this->effectiveTime = $effectiveTime;
        $this->hitPointRecovery = $hitPointRecovery;
    }

    public function getactorName() {
        return $this->actorName;
    }

    public function setactorName(?string $actorName) {
        $this->actorName = $actorName;
    }

    public function geteuenName() {
        return $this->euenName;
    }

    public function seteuenName(?string $euenName) {
        $this->euenName = $euenName;
    }

    public function getclassification() {
        return $this->classification;
    }

    public function setclassification(?string $classification) {
        $this->classification = $classification;
    }

    public function getRockHard() {
        return $this->rockHard;
    }

    public function setRockhard(?bool $rockHard) {
        $this->rockHard = $rockHard;
    }

    public function getbuyingPrice() {
        return $this->buyingPrice;
    }

    public function setbuyingPrice(?int $buyingPrice) {
        $this->buyingPrice = $buyingPrice;
    }

    public function getsellingPrice() {
        return $this->sellingPrice;
    }

    public function setsellingPrice(?int $sellingPrice) {
        $this->sellingPrice = $sellingPrice;
    }

    public function geteffectLevel() {
        return $this->effectLevel;
    }

    public function seteffectLevel(?int $effectLevel) {
        $this->effectLevel = $effectLevel;
    }

    public function geteffectType() {
        return $this->effectType;
    }

    public function seteffectType(?string $effectType) {
        $this->effectType = $effectType;
    }

    public function geteffectiveTime() {
        return $this->effectiveTime;
    }

    public function seteffectiveTime(?int $effectiveTime) {
        $this->effectiveTime = $effectiveTime;
    }    

    public function getHitPointRecovery() {
        return $this->hitPointRecovery;
    }

    public function setHitPointRecovery(?int $hitPointRecovery) {
        $this->hitPointRecovery = $hitPointRecovery;
    }
}