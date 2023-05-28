<?php
class Ingredient {
    private ?string $actorName = '';
    private ?string $euenName = '';
    private ?string $classification = ''; //Cooking name [e.g. CookEnemy, CookInsect, etc.]
    private ?string $modifier = ''; //Recipe prefix derived from erffectType (e.g. Spicy, Electro, etc.)
    private ?bool $rockHard = false; //Ingredients that make the recipe become rock hard food (e.g. Wood, Ruby, etc.)
    private ?int $buyingPrice = 0;
    private ?int $sellingPrice = 0;
    private ?string $color = ''; //Color of the ingredient, used for Dyes (e.g. Red, Yellow, etc.)
    private ?int $additionalDamage = 0; //For weapon fusing mechanics
    private ?int $effectLevel = 0; //Potency
    private ?string $effectType = ''; //Buff [LifeRecover, LifeMaxUp, StaminaRecover, ExStaminaMaxUp, ResistHot, ResistCold, ResistElectric, AllSpeed, AttackUp, DefenseUp, QuietnessUp, ResistBurn,, TwiceJump, EmergencyAvoid, LifeRepair, LightEmission, NotSlippy, SwimSpeedUp, AttackUpCold,AttackUpHot, AttackUpThunderstorm, MiasmaGuard]
    private ?int $effectiveTime = 0; //Duration
    private ?int $confirmedTime = 0; //Confirmed Time in seconds
    private ?int $hitPointRecover = 0; //Quarters of a Heart

    public function __construct(array|string $primary, ?string $euenName = '', ?string $classification = '', ?string $modifier = '', ?bool $rockHard = false, ?int $buyingPrice = 0, ?int $sellingPrice = 0, ?string $color = '', ?int $additionalDamage = 0, ?int $effectLevel = 0, ?string $effectType = '', ?int $effectiveTime = 0, ?int $confirmedTime = 0, ?int $hitPointRecover = 0) {
        if (is_array($primary)) {
            $this->actorName = $primary['ActorName'];
            $this->euenName = $primary['Euen name'];
            $this->classification = $primary['Classification'];
            $this->modifier = $primary['Modifier'];
            $this->rockHard = $primary['RockHard'];
            $this->buyingPrice = $primary['BuyingPrice'];
            $this->sellingPrice = $primary['SellingPrice'];
            $this->color = $primary['Color'];
            $this->additionalDamage = $primary['AdditionalDamage'];
            $this->effectLevel = $primary['EffectLevel'];
            $this->effectType = $primary['EffectType'];
            $this->effectiveTime = $primary['EffectiveTime'];
            $this->confirmedTime = $primary['ConfirmedTime'];
            $this->hitPointRecover = $primary['HitPointRecover'];
        } else {
            $this->actorName = $primary;
            $this->euenName = $euenName;
            $this->classification = $classification;
            $this->modifier = $modifier;
            $this->rockHard = $rockHard;
            $this->buyingPrice = $buyingPrice;
            $this->sellingPrice = $sellingPrice;
            $this->color = $color;
            $this->additionalDamage = $additionalDamage;
            $this->effectLevel = $effectLevel;
            $this->effectType = $effectType;
            $this->effectiveTime = $effectiveTime;
            $this->confirmedTime = $confirmedTime;
            $this->hitPointRecover = $hitPointRecover;
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
        return $this->modifier;
    }

    public function setModifier(?string $modifier) {
        $this->modifier = $modifier;
    }

    public function getRockHard() {
        return $this->rockHard;
    }

    public function setRockhard(?bool $rockHard) {
        $this->rockHard = $rockHard;
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

    public function getEffectiveTime() {
        return $this->effectiveTime;
    }

    public function setEffectiveTime(?int $effectiveTime) {
        $this->effectiveTime = $effectiveTime;
    }

    public function getConfirmedTime() {
        return $this->confirmedTime;
    }

    public function setConfirmedTime(?int $confirmedTime) {
        $this->confirmedTime = $confirmedTime;
    }    

    public function getHitPointRecover() {
        return $this->hitPointRecover;
    }

    public function setHitPointRecover(?int $hitPointRecover) {
        $this->hitPointRecover = $hitPointRecover;
    }
}