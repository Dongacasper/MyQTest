# MyQTest

#Cammands to start celaning:

1) Simple command:
php cleaning_robot test1.json result1.json
2) Command to write the log to the console we add the flag -c
php cleaning_robot -c test1.json result1.json
3) Command to write the log to a log file called CleaningRobot.log we add the flag -f CleaningRobot.log
php cleaning_robot -f CleaningRobot.log test1.json result1.json
4) Command to write the log to both console and log file called CleaningRobot.log we add the flags -c and -f CleaningRobot.log
php cleaning_robot -c -f CleaningRobot.log test1.json result1.json


-----------------------------------
#Testing:

Using phpunit with the test file CleaningRobotTest.php 
which has 9 tests and 11 assertions.
testInvalidInputMap
testInvalidInputStart
testInvalidInput
testNoPosition
testNoFacing
testOutOfBattery
testStuck
testBackOffTriggered1T
testBackOffTriggered2T
