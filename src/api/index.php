<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// read the current state
$state = readState();

// RASPI
// GET CURRENT STATE (Command, Timings etc.)
if (isset($_GET["state"])) {
    echo json_encode(array("success" => true, "state" => $state));

    // reset the command in the state
    $state->cmd = "";
    $state->cmd_param = "";
    writeState($state);
}
// SET COMMAND
else if (isset($_POST["cmd"])) {
    $cmd = $_POST["cmd"];
    $state->cmd = $_POST["cmd"];
    if (isset($_POST["cmd_param"])) {
        $state->cmd_param = $_POST["cmd_param"];
    }
    writeState($state);

    // log the command
    writeLogEntry("Befehl ausgeführt: " . $cmd . " - Parameter: " . $state->cmd_param);

    echo json_encode(array("success" => true));
}

// APP API
else if (isset($_GET["timings"])) {
    echo json_encode(array("success" => true, "timings" => $state->timings));
}
else if (isset($_POST["timings"])) {
    // array value format: (0 => array("07:00", "15:00"))
    $timings = json_decode($_POST["timings"]);
    $state->timings = $timings;
    writeState($state);

    echo json_encode(array("success" => true));
}
else if (isset($_POST["enable_timer"])) {
    $enable = $_POST["enabled"];
    $state->timer_enabled = $enable;
    writeState($state);

    echo json_encode(array("success" => true, "enabled" => $enable));
}
else if (isset($_GET["enable_timer"])) {
    echo json_encode(array("success" => true, "enabled" => $state->timer_enabled));
}
else if (isset($_GET["log"])) {
    // get the log for a specific date ?
    $logDate = date("Y-m-d");
    if (isset($_GET["date"])) {
        $logDate = $_GET["date"];
    }
 
    // read in the log file from today
    $logContents = getLogContents($logDate);

    // return log content as json
    echo json_encode(array("success" => true, "date" => $logDate, "log" => $logContents));
}

// FUNCTIONS
function readState() {
    if (file_exists("state.json")) {
        $state = file_get_contents("state.json");
        return json_decode($state);
    }
    return array(
        "cmd" => "", 
        "cmd_param" => "",
        "timer_enabled" => 0,
        "timings" => array(
            array("00:00", "00:00"),
            array("00:00", "00:00"),
            array("00:00", "00:00"),
            array("00:00", "00:00"),
            array("00:00", "00:00"),
            array("00:00", "00:00"),
            array("00:00", "00:00")
        ));
}

function writeState($state) {
    $file = fopen("state.json", "w");
    if ($file) {
        fwrite($file, json_encode($state));
        fclose($file);
    }
}

function getLogContents($day) {
    $logFile = "logs/log_" . $day . ".txt";
    if (file_exists($logFile)) {
        $handle = fopen($logFile, "r");
        if ($handle) {
            $logEntries = array();
            while (($line = fgets($handle)) !== false) {
                $logEntries[] = $line;
            }
            fclose($handle);
            return $logEntries;
        }
    }
    return array();
}

function writeLogEntry($msg) {
    $todayLogFile = "logs/log_" . date("Y-m-d") . ".txt";
    $file = fopen($todayLogFile, "a");
    if ($file) {
        fwrite($file, "[" . date("H:i:s") . "] " . $msg . "\n");
        fclose($file);
    }
}