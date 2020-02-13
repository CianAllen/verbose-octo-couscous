<?php

/*
 *  Cian Allen Verbose-octo-couscous Development Exercise
 *  Feb 13th, 2020
 *  
 *  Classes implemented:
 *      -a player object
 *      -a controller object
 *      -'IReadWritePlayers' interface used by reader/writer classes
 *      -Array based reader/writer object
 *      -Json String based reader/writer object
 *      -File based reader/writer object 
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

//Controller object responsible for choosing correct type of reader/writer
class controller {
    private $readerWriter;
    private $type;
    
    /*
     * Constructor for the controller
     * Assumed that the parameters in this case are being passed in using POST
     *      -source parameter for 'array', 'json' or 'file'
     *      -file parameter only used when 'file' source
     * Use those parameters to determine what kind of reader/writer to use
     * Assumed that our model is our reader/writer
     */
    public function __construct(){
        if ($_POST['source'] == 'array'){
            $this->readerWriter = new PlayersObjectArray();
        } else if ($_POST['source'] == 'json'){
            $this->readerWriter = new PlayersObjectJson();
        } else if ($_POST['source'] == 'file'){
            $this->readerWriter = new PlayersObjectFile();
            $this->readerWriter->file = $_POST['file']; //Assumed we can access the file name through the post method
        }
    }
}

//Interface used by the Player reader and writers
interface IReadWritePlayers {
    function readPlayers();
    function getPlayerData();
    function writePlayer($player);
    function display($isCLI);
}

//Player Object Reader/Writer for Arrays - used for only implementing array type data
class PlayersObjectArray implements IReadWritePlayers {

    private $playersArray; //Used to keep track of players 
    private $type; //Type of data used -will be set to 'array' by controller

    public function __construct() {
        $this->playersArray = []; //Initialize player array
        $this->type = 'array';
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
    private $type; //Type of data used -will be set to 'json' by controller

    public function __construct() {
        $this->playerJson = null;
        $this->type = 'json';
    }
    
    /*
     * @param $filename string Only used if we're reading players in 'file' mode.
     * @return player as json string
     */
    function readPlayers() { 
        $this->playerJson = json_encode($this->getPlayerData()); //ensures that result is encoded json
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
     * @param $player given as Player Object -assumed it's given as player object in this case
     */
    function writePlayer($player) {
        $tempArray = json_decode($this->playerJson);
        array_push($tempArray, $player);
        $this->playerJson = json_encode($tempArray);
    }
    
    function display($isCLI) {

        $players = $this->readPlayers();
        $players = json_decode($players); //Convert the Json string into an iterable array

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

//Player Object Array used for only implementing file type data
class PlayersObjectFile implements IReadWritePlayers {

    private $playerJson;
    private $file;
    private $type; //Type of data used -will be set to 'file' by controller

    public function __construct() {
        $this->playerJson = null;
        $this->file = null; //will be changed by post method in controller
        $this->type = 'file';
    }
    
    /*
     * @param $filename string Only used if we're reading players in 'file' mode.
     * @return player data as json string
     */
    function readPlayers() { 
        $this->playerJson = json_encode($this->getPlayerData()); //ensure data is in json format
        return $this->playerJson;
    }
    
    /*
     * get player data by using filename
     */
    function getPlayerData() {
        $fileResult = file_get_contents($this->file);
        return $fileResult;
    }
    
    /*
     * @param $filename string Only used if we're writing in 'file' mode
     * @param $player Class implementation of the player with name, age, job, salary.
     *  -again, assumed that $player will be given as a player object
     */
    function writePlayer($player) {
        $players = json_decode($this->playerJson);
        array_push($players, $player);
        $this->playerJson = json_encode($players);
        file_put_contents($this->file, $this->playerJson); //write back to file with new Json string including new player
    }
    
    function display($isCLI) {

        $players = $this->readPlayers($this->file);
        $players = json_decode($players); //Convert the json string into iterable array

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

/*
 * Assumed that what is wanted is to use a controller to determine 
 * the type of reader/writer to use here
 */
$controllerPlayer = new controller();

$playersObject = &($controllerPlayer->readerWriter);

//Display for an array type or json type reader/writer
if (($controllerPlayer->type = 'array') || ($controllerPlayer->type = 'json')){
    $playersObject->display(php_sapi_name() === 'cli');
} 
//Display for a file type reader/writer
else {
    $playersObject->display(php_sapi_name() === 'cli', $playersObject->file);
}
?>
