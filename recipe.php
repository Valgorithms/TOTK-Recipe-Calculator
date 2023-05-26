<?php
class Recipe {
    private ?array $ingredients; //array of Ingredient objects (max of 5)
    private ?string $cookingMethod; //Cooking Pot, Fire, Frozen, Hot Spring (Single Egg Only)
    
    private ?string $name;
    private ?string $classification; //Food, Elixer
    private ?int $sellingPrice;
    private ?int $effectLevel; //Potency, (Stamina Recovery = Potency * ~90)
    private ?string $effectType; //Buff [LifeRecover (Critical only), LifeMaxUp, StaminaRecover, ExStaminaMaxUp, ResistHot, ResistCold, ResistElectric, AllSpeed, AttackUp, DefenseUp, QuietnessUp, ResistBurn,, TwiceJump, EmergencyAvoid, LifeRepair, LightEmission, NotSlippy, SwimSpeedUp, AttackUpCold,AttackUpHot, AttackUpThunderstorm, MiasmaGuard]
    private ?int $effectiveTime; //Duration in microseconds (?) (900 = 30 seconds for 1 item? Seems wrong)
    private ?int $hitPointRecovery; //Quarters of a Heart

    private ?string $potency; //Normal, Med, High
    private ?int $staminaRecover = null;
    private ?int $exStaminaMaxUp = null;
    private ?int $lifeMaxUp = null;

    public function __construct(array $ingredients, ?string $cookingMethod = '', ?string $name = '', ?string $classification = '', ?int $sellingPrice = 0, ?int $effectLevel = 0, ?string $effectType = '', ?int $effectiveTime = 0, ?int $hitPointRecovery = 0) {
        $this->ingredients = $ingredients;
        if ($cookingMethod) $this->cookingMethod = $cookingMethod;
        
        if ($name) $this->name = $name;
        if ($classification) $this->classification = $classification;
        if ($sellingPrice) $this->sellingPrice = $sellingPrice;
        
        $effectType ? $this->setEffectType($effectType) : $this->calcEffectType();
        $effectLevel ? $this->setEffectLevel($effectLevel) : $this->calcEffectLevel();
        $this->calcPotency();
        $effectiveTime ? $this->setEffectiveTime($effectiveTime) : $this->calcEffectiveTime();
        $hitPointRecovery ? $this->setHitPointRecovery($hitPointRecovery) : $this->calcHitPointRecovery();
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
            if (!($ingredient instanceof Ingredient)) {
                throw new Exception("Invalid ingredient type.");
            }
        }
        $this->ingredients = $ingredients;
    }

    public function addIngredient(Ingredient $ingredient) {
        if (count($this->ingredients) < 5) {
            array_push($this->ingredients, $ingredient);
        } else {
            throw new Exception("Cannot add more than 5 ingredients.");
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

    public function getHitPointRecovery() {
        return $this->hitPointRecovery;
    }

    private function setHitPointRecovery(int $hitPointRecovery) {
        $this->hitPointRecovery = $hitPointRecovery;
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

    private function setLifeMaxUp(int $lifeMaxUp) {
        $this->lifeMaxUp = $lifeMaxUp;
    }

    public function getStaminaRecover() {
        return $this->staminaRecover;
    }

    private function setStaminaRecover(int $staminaRecover) {
        $this->staminaRecover = $staminaRecover;
    }

    public function getExStaminaMaxUp() {
        return $this->exStaminaMaxUp;
    }

    private function setExStaminaMaxUp(int $exStaminaMaxUp) {
        $this->exStaminaMaxUp = $exStaminaMaxUp;
    }

    private function calcEffectiveTime(): void
    {
        $dur = 0;
        foreach ($this->getIngredients() as $ingredient) $dur += $ingredient->getEffectiveTime()+300; //Add 30 seconds for each ingredient in the dish (?)
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
                    case 0:
                        $this->setStaminaRecover(0);
                        break;
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
                    default:
                        $this->setStaminaRecover(0);
                        break;
                }
                break;
            case 'ExStaminaMaxUp':
                switch ($effectLevel = $this->getEffectLevel()) {
                    case 0:
                        $this->setExStaminaMaxUp(0);
                        break;
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
                    default:
                        $this->setExStaminaMaxUp(0);
                        break;
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

    private function calcHitPointRecovery(): void
    {
        $hitPointRecovery = 0;
        foreach ($this->getIngredients() as $ingredient) $hitPointRecovery += $ingredient->getHitPointRecovery();

        switch ($this->getCookingMethod()) {
            case 'Fire':
                $this->setHitPointRecovery(intval(ceil($hitPointRecovery*1.5)));
                break;
            case 'Frozen':
                $this->setHitPointRecovery($hitPointRecovery);
                break;
            case 'Cooking Pot':
            default:
                $this->setHitPointRecovery($hitPointRecovery*2);
                break;
        }
    }
}
