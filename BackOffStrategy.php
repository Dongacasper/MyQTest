<?php

/**
 * Class StrategyContext
 */
class StrategyContext {


    /**
     * @var StrategyInterface
     */
    private $strategy = NULL;
    /**
     * @var int
     */
    private $strategy_id = 1;
    /**
     * @var boolean
     */
    private $stuck = false;

    //bookList is not instantiated at construct time
    public function __construct() {
        $this->nextStrategy();
    }

    /**
     * set next strategy
     */
    private function nextStrategy(){
        switch ($this->strategy_id) {
            case 1:
                $this->strategy = new backOffStrategy1();
                break;
            case 2:
                $this->strategy = new backOffStrategy2();
                break;
            case 3:
                $this->strategy = new backOffStrategy3();
                break;
            case 4:
                $this->strategy = new backOffStrategy4();
                break;
            case 5:
                $this->strategy = new backOffStrategy5();
                break;
            default:
                $this->stuck = true;
        }
        $this->strategy_id++;
    }


    /**
     * back the robot off in case of obstacle
     * TR A TL
     * TR A TR
     * TR A TR
     * TR B TR A
     * TL TL A
     * @param CleaningRobot $cleaningRobot
     * @return bool
     */
    public function backOff(CleaningRobot $cleaningRobot):bool {
        do {
            $status = $this->strategy->backOff($cleaningRobot);
            $this->nextStrategy();

        }while (!$status && !$this->stuck);

        if($this->stuck)
            return false;
        return true;
    }

}

interface StrategyInterface {
    public function backOff(CleaningRobot $cleaningRobot):bool ;
}

class backOffStrategy1 implements StrategyInterface {


    /**
     * Strategy 1
     * TR A TL
     * @param CleaningRobot $cleaningRobot
     * @return bool
     * @throws Exception
     */
    public function backOff(CleaningRobot $cleaningRobot):bool {
        $cleaningRobot->writeToConsole("\n ------backOffStrategy1 start------ \n");
        $cleaningRobot->TR();
        if($cleaningRobot->getNextForward()){
            $cleaningRobot->A();
            $cleaningRobot->TL();
            $cleaningRobot->writeToConsole("\n ------backOffStrategy1 success------ \n");
            return true;
        }
        return false;
    }
}

class backOffStrategy2 implements StrategyInterface {
    /**
     * Strategy 2
     * TR A TR
     * @param $cleaningRobot
     * @return bool
     * @throws Exception
     */
    public function backOff(CleaningRobot $cleaningRobot):bool {
        $cleaningRobot->writeToConsole("\n ------backOffStrategy2 start------ \n");
        $cleaningRobot->TR();
        if($cleaningRobot->getNextForward()){
            $cleaningRobot->A();
            $cleaningRobot->TR();
            $cleaningRobot->writeToConsole("\n ------backOffStrategy2 success------ \n");
            return true;
        }
        return false;
    }
}

class backOffStrategy3 implements StrategyInterface {
    /**
     * Strategy 3
     * TR A TR
     * @param $cleaningRobot
     * @return bool
     * @throws Exception
     */
    public function backOff(CleaningRobot $cleaningRobot):bool {
        $cleaningRobot->writeToConsole("\n ------backOffStrategy3 start------ \n");
        $cleaningRobot->TR();
        if($cleaningRobot->getNextForward()){
            $cleaningRobot->A();
            $cleaningRobot->TR();
            $cleaningRobot->writeToConsole("\n ------backOffStrategy3 success------ \n");
            return true;
        }
        return false;
    }
}

class backOffStrategy4 implements StrategyInterface {
    /**
     * Strategy 4
     * TR B TR A
     * @param $cleaningRobot
     * @return bool
     * @throws Exception
     *
     */
    public function backOff(CleaningRobot $cleaningRobot):bool {
        $cleaningRobot->writeToConsole("\n ------backOffStrategy4 start------ \n");
        $cleaningRobot->TR();
        if($cleaningRobot->getNextBackward()){
            $cleaningRobot->B();
            $cleaningRobot->TR();
            if($cleaningRobot->getNextForward()){
                $cleaningRobot->A();
                $cleaningRobot->writeToConsole("\n ------backOffStrategy4 success------ \n");
                return true;
            }else
                return false;
        }
        return false;
    }
}

class backOffStrategy5 implements StrategyInterface {
    /**
     * Strategy 5
     * TL TL A
     * @param $cleaningRobot
     * @return bool
     * @throws Exception
     */
    public function backOff(CleaningRobot $cleaningRobot):bool {
        $cleaningRobot->writeToConsole("\n ------backOffStrategy5 start------ \n");
        $cleaningRobot->TL();
        $cleaningRobot->TL();
        if($cleaningRobot->getNextForward()){
            $cleaningRobot->A();
            $cleaningRobot->writeToConsole("\n ------backOffStrategy5 success------ \n");
            return true;
        }
        return false;
    }
}