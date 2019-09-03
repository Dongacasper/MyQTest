<?php


class CleaningRobot
{

    /**
     *All directions sorted N->E->S->W->N
     * Do not change the sort
     */
    const DIRECTIONS = ['N', 'E', 'S', 'W'];
    /**
     * @var array
     */
    private $map;
    /**
     * @var array
     */
    private $visited = [];
    /**
     * @var array
     */
    private $cleaned = [];
    /**
     * @var array
     */
    private $position;
    /**
     * @var int
     */
    private $battery;
    /**
     * @var array
     */
    private $commands;
    /**
     * @var string
     */
    private $facing;
    /**
     * @var int
     */
    private $backOffTriggered = 0;
    /**
     * @var boolean
     */
    private $outOfBattery = false;
    /**
     * @var boolean
     */
    private $stuck = false;
    /**
     * @var array
     */
    private $options = ['writeToLog' => false, 'writeToConsole' => false];
    /**
     * @var string
     */
    private $logFile = 'CommandsLog.log';


    /**************************************
     * Constructor getters and setters
     **************************************/


    /**
     * CleaningRobot constructor.
     * @param $inputData
     */
    public function __construct($inputData)
    {
            $this->validateOptions();
            $validatedInput = $this->validateInput($inputData);

            $this->setMap($validatedInput['map']);
            $this->setPosition($validatedInput['position']);
            $this->setFacing($validatedInput['facing']);
            $this->setBattery($validatedInput['battery']);
            $this->setCommands($validatedInput['commands']);
            $this->setVisited($validatedInput['position']);
    }

    /**
     * @return array
     */
    public function getMap()
    {
        return $this->map;
    }

    /**
     * @param array $map
     */
    public function setMap($map)
    {
        $this->map = $map;
    }

    /**
     * @return array
     */
    public function getVisited()
    {
        return $this->visited;
    }

    /**
     * @param array $visited
     */
    public function setVisited($visited)
    {
        $this->visited = [$visited];
    }

    /**
     * @param array $newVisited
     */
    public function addToVisited($newVisited)
    {
        if(!in_array($newVisited, $this->visited))
            array_push($this->visited,$newVisited);
    }

    /**
     * @return array
     */
    public function getCleaned()
    {
        return $this->cleaned;
    }

    /**
     * @param array $cleaned
     */
    public function setCleaned($cleaned)
    {
        $this->cleaned = $cleaned;
    }

    /**
     * @param array $newCleaned
     */
    public function addToCleaned($newCleaned)
    {
        if(!in_array($newCleaned, $this->cleaned))
            array_push($this->cleaned,$newCleaned);
    }

    /**
     * @return array
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param array $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }

    /**
     * @return int
     */
    public function getBattery()
    {
        return $this->battery;
    }

    /**
     * @param int $battery
     */
    public function setBattery($battery)
    {
        $this->battery = $battery;
    }

    /**
     * @return array
     */
    public function getCommands()
    {
        return $this->commands;
    }

    /**
     * @param array $commands
     */
    public function setCommands($commands)
    {
        $this->commands = $commands;
    }

    /**
     * @return string
     */
    public function getFacing()
    {
        return $this->facing;
    }
    /**
     * @return string
     */
    public function getBackOffTriggered()
    {
        return $this->backOffTriggered;
    }
    /**
     * @return array
     */
    public function getFinalPosition()
    {
        $finalPosition = $this->getPosition();
        $finalPosition['facing'] = $this->getFacing();
        return $finalPosition;
    }

    /**
     * @return boolean
     */
    public function getOutOfBattery()
    {
        return $this->outOfBattery;
    }

    /**
     * @return boolean
     */
    public function getStuck()
    {
        return $this->stuck;
    }

    /**
     * @param string $facing
     */
    public function setFacing($facing)
    {
        $this->facing = $facing;
    }

    /**************************************
     * Class private functions
     **************************************/

    /**
     * @param array $inputData
     * @return array
     * @throws \http\Exception\InvalidArgumentException
     */
    private function validateInput($inputData){

        // TODO make validation for the map array

        if(!isset($inputData['map']))
            throw new InvalidArgumentException(
                'Invalid Input: Missing Map'
            );
        if(!isset($inputData['start']))
            throw new InvalidArgumentException(
                'Invalid Input: Missing Start'
            );


        $validatedData['map'] = $inputData['map'];


        if(isset($inputData['start']['X']) && isset($inputData['start']['Y'])) {
            $x = is_int($inputData['start']['X'])?$inputData['start']['X']:0;
            $y = is_int($inputData['start']['Y'])?$inputData['start']['Y']:0;
        }else{
            $x=0;
            $y=0;
        }
        $validatedData['position'] = array('X' => $x, 'Y' => $y);

        if(isset($inputData['start']['facing']))
            $facing = in_array($inputData['start']['facing'], self::DIRECTIONS)?$inputData['start']['facing']:'N';
        else
            $facing = 'N';
        $validatedData['facing'] = $facing;

        if(isset($inputData['battery']))
            $battery = is_int($inputData['battery'])?$inputData['battery']:'0';
        else $battery = 0;
        $validatedData['battery'] = $battery;


        // TODO make validation for the map array

        if(isset($inputData['commands']))
            $commands = is_array($inputData['commands'])?$inputData['commands']:[];
        else $commands = [];
        $validatedData['commands'] = $commands;

        return $validatedData;
    }
    /**
     * @return boolean
     */
    private function validateOptions(){



        $options = getopt("f:cp:",[],$optind);

        if(isset($options['c']))
            $this->options['writeToConsole'] = true;

        if(isset($options['f'])) {
            $this->options['writeToLog'] = true;
            $this->logFile = $options['f'];
        }

        return true;
    }


    /**************************************
     * Class public functions
     **************************************/

    /**
     * @param string $commandLogMsg
     */
    public function writeToConsole($commandLogMsg){
        if($this->options['writeToConsole'])
            echo $commandLogMsg;
    }
    /**
     * @param string $commandLogMsg
     */
    public function writeToLog($commandLogMsg){
        if($this->options['writeToLog'])
            error_log ($commandLogMsg, 3, $this->logFile);
    }

    /**
     * Turn the robot left and consume battery by 1
     * @return bool
     */
    public function TL(){
        if($this->getBattery()<1)
            return false;
        $this->setFacing(self::DIRECTIONS[((array_search($this->getFacing(), self::DIRECTIONS))+3)%4]);
        $this->setBattery($this->getBattery()-1);

        //logging
        $commandLogMsg = "Turn Left to ".$this->getFacing()."\n";
        $this->writeToConsole($commandLogMsg);
        $this->writeToLog($commandLogMsg, 3, $this->logFile);
        return true;
    }

    /**
     * move the robot forward and consume battery by 2
     * if there is any block back the robot off
     * @return bool
     * @throws Exception if Robot Stuck
     */
    public function A(){
        if($this->battery < 2)
            return false;
        $this->setBattery($this->getBattery()-2);

        $nextForward = $this->getNextForward();
        if($nextForward) {
            $this->setPosition($nextForward);
            $this->addToVisited($nextForward);

            //logging
            $commandLogMsg = "Advance ".$nextForward['X'].','.$nextForward['Y']."\n";
            $this->writeToConsole($commandLogMsg);
            $this->writeToLog($commandLogMsg, 3, $this->logFile);
        }
        elseif(!$this->backOff()){
            $this->stuck = true;

            //logging
            $commandLogMsg = 'stuck';
            $this->writeToConsole($commandLogMsg);
            $this->writeToLog($commandLogMsg, 3, $this->logFile);
            throw new Exception(
                'Robot Stuck'
            );
        }

        //TODO back off till possible forward move
        /*
            while(!$nextForward = $this->getNextForward())
            {
                if(!$this->backOff()){
                    $this->stuck = true;
                    echo 'stuck';
                    die;
                }
            }
        */

        return true;
    }

    /**
     * clean area by robot and consume battery by 5
     * @return bool
     */
    public function C(){
        if($this->battery < 5)
            return false;
        $this->setBattery($this->getBattery()-5);
        $this->addToCleaned($this->getPosition());

        //logging
        $commandLogMsg = "Clean ".$this->getPosition()['X'].','.$this->getPosition()['Y']."\n";
        $this->writeToConsole($commandLogMsg);
        $this->writeToLog($commandLogMsg, 3, $this->logFile);
        return true;
    }

    /**
     * Turn the robot right and consume battery by 1
     * @return bool
     */
    public function TR(){
        if($this->getBattery()<1)
            return false;
        $this->setFacing(self::DIRECTIONS[((array_search($this->getFacing(), self::DIRECTIONS))+1)%4]);
        $this->setBattery($this->getBattery()-1);

        //logging
        $commandLogMsg = "Turn right to ".$this->getFacing()."\n";
        $this->writeToConsole($commandLogMsg);
        $this->writeToLog($commandLogMsg, 3, $this->logFile);
        return true;
    }

    /**
     * move the robot backward and consume battery by 3
     * if there is any block back the robot off
     * @return bool
     */
    public function B(){
        if($this->battery < 3)
            return false;
        while(!$nextBackward = $this->getNextBackward())
        {
            $this->backOff();
        }

        $this->setPosition($nextBackward);
        $this->setBattery($this->getBattery()-3);
        $this->addToVisited($nextBackward);

        //logging
        $commandLogMsg = "Back ".$nextBackward['X'].','.$nextBackward['Y']."\n";
        $this->writeToConsole($commandLogMsg);
        $this->writeToLog($commandLogMsg, 3, $this->logFile);
        return true;
    }

    /**
     * get the next block forward from the map
     * @return array|bool
     */
    public function getNextForward(){
        $pos = $this->getPosition();
        switch ($this->getFacing())
        {
            case 'N':
                $pos['Y']--;
            break;

            case 'E':
                $pos['X']++;
            break;

            case 'S':
                $pos['Y']++;
            break;

            case 'W':
                $pos['X']--;
            break;
        }

        $nextForward = $this->getMap()[$pos['Y']][$pos['X']]??'null';

        if($nextForward == 'S')
            return $pos;
        else
            return false;
    }

    /**
     * get the next block backward from the map
     * @return array|bool
     */
    public function getNextBackward(){
        $pos = $this->getPosition();
        switch ($this->getFacing())
        {
            case 'N':
                $pos['Y']++;
            break;

            case 'E':
                $pos['X']--;
            break;

            case 'S':
                $pos['Y']--;
            break;

            case 'W':
                $pos['X']++;
            break;
        }

        $nextBackward = $this->getMap()[$pos['Y']][$pos['X']]??'null';

        if($nextBackward == 'S')
            return $pos;
        else
            return false;
    }


    /**
     * back the robot off in case of obstacle
     * @return bool
     */
    public function backOff(){
        $this->backOffTriggered++;

        //logging
        $commandLogMsg = "Back Off:\n";
        $this->writeToConsole($commandLogMsg);
        $this->writeToLog($commandLogMsg, 3, $this->logFile);

        include_once ('BackOffStrategy.php');
        $backOffStrategy = new StrategyContext();
        return $backOffStrategy->backOff($this);
    }

    /**
     * Execute commands one by one
     */
    public function executeCommands(){
        foreach ($this->getCommands() as $command) {
            if(!$this->$command()) {
                $this->outOfBattery = true;
                //logging
                $commandLogMsg = ' Battery is out ';
                $this->writeToConsole($commandLogMsg);
                $this->writeToLog($commandLogMsg, 3, $this->logFile);

                break;
            }

        }
    }

}