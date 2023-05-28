<?php
class Ingredient {
    private ?string $actorName = '';
    private ?string $euenName = '';
    private ?string $classification = ''; //Food, Elixer
    private ?bool $rockHard = false; //Ingredients that make the recipe become rock hard food (e.g. Wood, Ruby, etc.)
    private ?int $buyingPrice = 0;
    private ?int $sellingPrice = 0;
    private ?int $effectLevel = 0; //Potency
    private ?string $effectType = ''; //Buff [LifeRecover, LifeMaxUp, StaminaRecover, ExStaminaMaxUp, ResistHot, ResistCold, ResistElectric, AllSpeed, AttackUp, DefenseUp, QuietnessUp, ResistBurn,, TwiceJump, EmergencyAvoid, LifeRepair, LightEmission, NotSlippy, SwimSpeedUp, AttackUpCold,AttackUpHot, AttackUpThunderstorm, MiasmaGuard]
    private ?int $effectiveTime = 0; //Duration
    private ?int $hitPointRecover = 0; //Quarters of a Heart

    public function __construct(array|string $primary, ?string $euenName = '', ?string $classification = '', ?bool $rockHard = false, ?int $buyingPrice = 0, ?int $sellingPrice = 0, ?int $effectLevel = 0, ?string $effectType = '', ?int $effectiveTime = 0, ?int $hitPointRecover = 0) {
        if (is_array($primary)) {
            var_dump($primary);
            $this->actorName = $primary['ActorName'];
            $this->euenName = $primary['Euen name'];
            $this->classification = $primary['Classification'];
            $this->rockHard = $primary['RockHard'];
            $this->buyingPrice = $primary['BuyingPrice'];
            $this->sellingPrice = $primary['SellingPrice'];
            $this->effectLevel = $primary['EffectLevel'];
            $this->effectType = $primary['EffectType'];
            $this->effectiveTime = $primary['EffectiveTime'];
            $this->hitPointRecover = $primary['HitPointRecover'];
        } else {
            $this->actorName = $primary;
            $this->euenName = $euenName;
            $this->classification = $classification;
            $this->rockHard = $rockHard;
            $this->buyingPrice = $buyingPrice;
            $this->sellingPrice = $sellingPrice;
            $this->effectLevel = $effectLevel;
            $this->effectType = $effectType;
            $this->effectiveTime = $effectiveTime;
            $this->hitPointRecover = $hitPointRecover;
        }
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

    public function gethitPointRecover() {
        return $this->hitPointRecover;
    }

    public function sethitPointRecover(?int $hitPointRecover) {
        $this->hitPointRecover = $hitPointRecover;
    }
}