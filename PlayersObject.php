<?php

/*
    Development Exercise

      The following code is poorly designed and error prone. Refactor the objects below to follow a more SOLID design.
      Keep in mind the fundamentals of MVVM/MVC and Single-responsibility when refactoring.

      Further, the refactored code should be flexible enough to easily allow the addition of different display
        methods, as well as additional read and write methods.

      Feel free to add as many additional classes and interfaces as you see fit.

      Note: Please create a fork of the https://github.com/BrandonLegault/exercise repository and commit your changes
        to your fork. The goal here is not 100% correctness, but instead a glimpse into how you
        approach refactoring/redesigning bad code. Commit often to your fork.

*/

/*
 *  A Player Class
 *  Holds information about that single player
 *  Construct and Get/Setter functions
 */
class Player{
    private $name;
    private $age;
    private $job;
    private $salary;
    
    public function _construct($nme, $ag, $jb, $sal){
        $this->name = $nme;
        $this->age = $ag;
        $this->job = $jb;
        $this->salary = $sal;
    }
    
    public function __get($var){
        return $this->$var;
    }
    
    public function __set($var, $val){
        $this->$var = $val;
    }
}

//Interface used by the Player reader and writers
interface IReadWritePlayers {
    function readPlayers($filename = null);
    function getPlayerData($filename = null);
    function writePlayer($player, $filename = null);
    function display($isCLI, $filename = null);
}

//Player Object Reader/Writer for Arrays - used for only implementing array type data
class PlayersObjectArray implements IReadWritePlayers {

    private $playersArray; //Used to keep track of players 

    public function __construct() {
        $this->playersArray = []; //Initialize player array
    }
    
    /*
     * @param $filename string Only used if we're reading players in 'file' mode.
     * @return player Data Array
     */
    function readPlayers() { 
                
        $this->playersArray = $this->getPlayerData(); //Call function to retrieve player data
        return $this->playersArray;
    }
    
    /*
     * Assuming that statically writing our player information in this way is acceptable
     * Uses player object to create a player then pushes that player into array
     * Result should be an array that for every index points to a player object
     */
    function getPlayerData() {

        $jonas = new Player('Jonas Valenciunas', 26, 'Center', '4.66m');
        $kyle = new Player('Kyle Lowry', 32, 'Point Guard', '28.7m');
        $demar = new Player('Demar DeRozan', 28, 'Shooting Guard', '26.54m');
        $jakob = new Player('Jakob Poeltl', 22, 'Center', '2.704m');

        $players = [];
        array_push($players, $jonas, $kyle, $demar, $jakob);

        return $players;
    }
    
    /*
     * @param $filename string Only used if we're writing in 'file' mode
     * @param $player Class implementation of the player with name, age, job, salary.
     */
    function writePlayer($player) {
      
        array_push($this->playersArray, $player);
    }
    
    function display($isCLI) {

        $players = $this->readPlayers();

        if ($isCLI) {
            echo "Current Players: \n";
            foreach ($players as $player) {

                echo "\tName: $player->name\n";
                echo "\tAge: $player->age\n";
                echo "\tSalary: $player->salary\n";
                echo "\tJob: $player->job\n\n";
            }
        } else {

            ?>
            <!DOCTYPE html>
            <html>
            <head>
                <style>
                    li {
                        list-style-type: none;
                        margin-bottom: 1em;
                    }
                    span {
                        display: block;
                    }
                </style>
            </head>
            <body>
            <div>
                <span class="title">Current Players</span>
                <ul>
                    <?php foreach($players as $player) { ?>
                        <li>
                            <div>
                                <span class="player-name">Name: <?= $player->name ?></span>
                                <span class="player-age">Age: <?= $player->age ?></span>
                                <span class="player-salary">Salary: <?= $player->salary ?></span>
                                <span class="player-job">Job: <?= $player->job ?></span>
                            </div>
                        </li>
                    <?php } ?>
                </ul>
            </body>
            </html>
            <?php
        }
    }
}

//Player Object Array used for only implementing Json string type data
class PlayersObjectJson implements IReadWritePlayers {

    private $playerJson;

    public function __construct() {
        $this->playerJson = null;
    }
    
    /*
     * @param $filename string Only used if we're reading players in 'file' mode.
     * @return player as json string
     */
    function readPlayers() { 
        $this->playerJson = json_encode($this->getPlayerData());
        return $this->playerJson;
    }
    
    /*
     * Assuming that statically writing our json string in this way is acceptable
     */
    function getPlayerData() {
        $json = '[{"name":"Jonas Valenciunas","age":26,"job":"Center","salary":"4.66m"},{"name":"Kyle Lowry","age":32,"job":"Point Guard","salary":"28.7m"},{"name":"Demar DeRozan","age":28,"job":"Shooting Guard","salary":"26.54m"},{"name":"Jakob Poeltl","age":22,"job":"Center","salary":"2.704m"}]';
        return $json;
    }
    
    /*
     * @param $filename string Only used if we're writing in 'file' mode
     * @param $player given as Player Object -assumed it's given as player in this case
     */
    function writePlayer($player) {
        $tempArray = json_decode($this->playerJson);
        array_push($tempArray, $player);
        $this->playerJson = json_encode($tempArray);
    }
    
    function display($isCLI) {

        $players = $this->readPlayers();

        if ($isCLI) {
            echo "Current Players: \n";
            foreach ($players as $player) {

                echo "\tName: $player->name\n";
                echo "\tAge: $player->age\n";
                echo "\tSalary: $player->salary\n";
                echo "\tJob: $player->job\n\n";
            }
        } else {

            ?>
            <!DOCTYPE html>
            <html>
            <head>
                <style>
                    li {
                        list-style-type: none;
                        margin-bottom: 1em;
                    }
                    span {
                        display: block;
                    }
                </style>
            </head>
            <body>
            <div>
                <span class="title">Current Players</span>
                <ul>
                    <?php foreach($players as $player) { ?>
                        <li>
                            <div>
                                <span class="player-name">Name: <?= $player->name ?></span>
                                <span class="player-age">Age: <?= $player->age ?></span>
                                <span class="player-salary">Salary: <?= $player->salary ?></span>
                                <span class="player-job">Job: <?= $player->job ?></span>
                            </div>
                        </li>
                    <?php } ?>
                </ul>
            </body>
            </html>
            <?php
        }
    }
}

//Player Object Array used for only implementing Json string type data
class PlayersObjectFile implements IReadWritePlayers {

    private $playerJson;

    public function __construct() {
        $this->playerJson = null;
    }
    
    /*
     * @param $filename string Only used if we're reading players in 'file' mode.
     * @return player data as array
     */
    function readPlayers($filename) { 
        $this->playerJson = $this->getPlayerData($filename);
        return $this->playerJson;
    }
    
    /*
     * get player data by using filename
     */
    function getPlayerData($filename) {
        $file = file_get_contents($filename);
        return $file;
    }
    
    /*
     * @param $filename string Only used if we're writing in 'file' mode
     * @param $player Class implementation of the player with name, age, job, salary.
     *  -again, assumed that $player will be given as a player object
     */
    function writePlayer($player, $filename) {
        $players = json_decode($this->playerJson);
        array_push($players, $player);
        $this->playerJson = json_encode($players);
        file_put_contents($filename, $this->playerJson); //write back to file with new Json string including new player
    }
    
    function display($isCLI, $filename) {

        $players = $this->readPlayers($filename);

        if ($isCLI) {
            echo "Current Players: \n";
            foreach ($players as $player) {

                echo "\tName: $player->name\n";
                echo "\tAge: $player->age\n";
                echo "\tSalary: $player->salary\n";
                echo "\tJob: $player->job\n\n";
            }
        } else {

            ?>
            <!DOCTYPE html>
            <html>
            <head>
                <style>
                    li {
                        list-style-type: none;
                        margin-bottom: 1em;
                    }
                    span {
                        display: block;
                    }
                </style>
            </head>
            <body>
            <div>
                <span class="title">Current Players</span>
                <ul>
                    <?php foreach($players as $player) { ?>
                        <li>
                            <div>
                                <span class="player-name">Name: <?= $player->name ?></span>
                                <span class="player-age">Age: <?= $player->age ?></span>
                                <span class="player-salary">Salary: <?= $player->salary ?></span>
                                <span class="player-job">Job: <?= $player->job ?></span>
                            </div>
                        </li>
                    <?php } ?>
                </ul>
            </body>
            </html>
            <?php
        }
    }
}

$playersObject = new PlayersObjectArray();

$playersObject->display(php_sapi_name() === 'cli');

?>
