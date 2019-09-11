<?php

use PHPUnit\Framework\TestCase;



class CleaningRobotTest extends TestCase
{
    public function testInvalidInputMap()
    {
        include_once('CleaningRobot.php');
        $inputDataWithoutMap = array('maps' => [["C", "S", "S", "S"],["S", "C", "S", "C"],["S", "S", "S", "S"],["S", "null", "S", "S"]],
            'start' => ['X' => 3, 'Y' => 0, 'facing' => 'W'],
            'commands' => [ "TR","A","C","A","C","TR","B","C","B","C","B","C"],
            'battery' => 1094);


        $this->expectExceptionMessage('Invalid Input: Missing Map');
        $this->expectException(InvalidArgumentException::class);
        new CleaningRobot($inputDataWithoutMap);
    }

    public function testInvalidInputStart()
    {
        include_once('CleaningRobot.php');
        $inputDataWithoutStart = array('map' => [["C", "S", "S", "S"],["S", "C", "S", "C"],["S", "S", "S", "S"],["S", "null", "S", "S"]],
            'commands' => [ "TR","A","C","A","C","TR","B","C","B","C","B","C"],
            'battery' => 1094);


        $this->expectExceptionMessage('Invalid Input: Missing Start');
        $this->expectException(InvalidArgumentException::class);
        new CleaningRobot($inputDataWithoutStart);
    }

    public function testInvalidInput()
    {
        $argv[0] = 'flag1';
        $argv[1] = 'flag2';
        $argv[2] = '2filedoesnotexists.json';
        $expected = "Caught exception: The file $argv[2] does not exists\n";

        $this->expectOutputString($expected);

        include_once('cleaning_robot');
    }

    public function testNoPosition()
    {
        $expected = ['X'=>0,'Y'=>0];

        include_once('CleaningRobot.php');
        $inputDataWithoutNoPosition = array('map' => [["C", "S", "S", "S"],["S", "C", "S", "C"],["S", "S", "S", "S"],["S", "null", "S", "S"]],
            'start' => ['facing' => 'W'],
            'commands' => [ "TR","A","C","A","C","TR","B","C","B","C","B","C"],
            'battery' => 1094);

        $myqRobot = new CleaningRobot($inputDataWithoutNoPosition);
        $result = $myqRobot->getPosition();

        $this->assertEquals($expected, $result);

    }

    public function testNoFacing()
    {
        $expected = 'N';

        include_once('CleaningRobot.php');
        $inputDataWithoutNoFacing = array('map' => [["C", "S", "S", "S"],["S", "C", "S", "C"],["S", "S", "S", "S"],["S", "null", "S", "S"]],
            'start' => ['X' => 3, 'Y' => 0],
            'commands' => [ "TR","A","C","A","C","TR","B","C","B","C","B","C"],
            'battery' => 1094);

        $myqRobot = new CleaningRobot($inputDataWithoutNoFacing);
        $result = $myqRobot->getFacing();

        $this->assertEquals($expected, $result);

    }

    public function testOutOfBattery()
    {
        $expected = true;

        include_once('CleaningRobot.php');
        $inputDataWithoutNoFacing = array('map' => [["S", "S", "S", "S"],["S", "C", "S", "C"],["S", "S", "S", "S"],["S", "null", "S", "S"]],
            'start' => ['X' => 3, 'Y' => 0, 'facing' => 'W'],
            'commands' => ["C"],
            'battery' => 4);

        $myqRobot = new CleaningRobot($inputDataWithoutNoFacing);
        $myqRobot->executeCommands();
        $result = $myqRobot->getOutOfBattery();

        $this->assertEquals($expected, $result);

    }

    public function testStuck()
    {
        $expected = true;

        $this->expectExceptionMessage('Robot Stuck');
        include_once('CleaningRobot.php');
        $inputDataWithoutNoFacing = array('map' => [["C", "S", "C", "S"],["S", "C", "S", "C"],["S", "S", "S", "S"],["S", "null", "S", "S"]],
            'start' => ['X' => 3, 'Y' => 0, 'facing' => 'W'],
            'commands' => ["A"],
            'battery' => 4);

        $myqRobot = new CleaningRobot($inputDataWithoutNoFacing);
        $myqRobot->executeCommands();
        $result = $myqRobot->getStuck();

        $this->assertEquals($expected, $result);

    }

    public function testBackOffTriggered1T()
    {
        $expected = 1;

        include_once('CleaningRobot.php');
        $inputDataWithoutNoFacing = array('map' => [["S", "S", "S", "S"],["S", "C", "S", "C"],["S", "S", "S", "S"],["S", "null", "S", "S"]],
            'start' => ['X' => 3, 'Y' => 0, 'facing' => 'W'],
            'commands' => ["TL","A","C"],
            'battery' => 40);
        $myqRobot = new CleaningRobot($inputDataWithoutNoFacing);
        $myqRobot->executeCommands();

        $result = $myqRobot->getBackOffTriggered();

        $this->assertEquals($expected, $result);
    }

    public function testBackOffTriggered2T()
    {
        $expected = 2;

        include_once('CleaningRobot.php');
        $inputDataWithoutNoFacing = array('map' => [["S", "S", "S", "S"],["S", "C", "S", "C"],["S", "S", "S", "S"],["S", "null", "S", "S"]],
            'start' => ['X' => 2, 'Y' => 0, 'facing' => 'W'],
            'commands' => ["TR","A","C","A","C"],
            'battery' => 40);
        $myqRobot = new CleaningRobot($inputDataWithoutNoFacing);
        $myqRobot->executeCommands();

        $result = $myqRobot->getBackOffTriggered();

        $this->assertEquals($expected, $result);
    }
}
