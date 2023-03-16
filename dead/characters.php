<?php
class character
{
    function __construct(
        $isAlive = true,
        $hitPoints = 100,
        $atkPower = 10,
        $evolveTmr = 0
    ) {
        $this->isAlive = $isAlive;
        $this->hitPoints = $hitPoints;
        $this->atkPower = $atkPower;
        $this->evolveTmr = $evolveTmr;
    }
}
class Void_ extends Character
{
    function __construct(
        $overloadPoints = 0,
        $restCounter = 0,
        $shutOff = false
    ){
        parent::__construct();
        $this->overloadPoints = $overloadPoints;
        $this->restCounter = $restCounter;
        $this->shutOff = $shutOff;
    }
}

class Lilith extends Character
{
    function __construct(
        $doubleUp = 0,
    ){
        parent::__construct();
        $this->doubleUp = $doubleUp;
    }

}

class Kite extends Character
{
    function __construct(
        $healAmt = 15,
    ){
        parent::__construct();
        $this->healAmt = $healAmt;
    }

}

class Kitt extends Character
{
    function __construct(
        $sentryTmr = 0
    ){
        parent::__construct();
        $this->sentryTmr = $sentryTmr;
    }
}

class Paige extends Character
{
    function __construct(
        $sentryXTmr = 0
    ){
        parent::__construct();
        $this->sentryXTmr = $sentryXTmr;
    }
}

class Ruby extends Character
{
    function __construct(
        $fireWallActive = false
    ){
        parent::__construct();
        $this->fireWallActive = $fireWallActive;
    }

}


class Attack 
{
    function __construct(
        $isRanged = false,
    ) {
        $this->isRanged = $isRanged;
    }
}