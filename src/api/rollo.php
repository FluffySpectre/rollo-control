<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// needed time for transition:
// Full open <-> full closed: 41s
$transitionTime = 42000000; // in microseconds

// TODO: read state from the database
// state can be: 
// 0 = closed
// 1 = open
// 2 = stopped

// ports for controller
$upPort = 8;
$downPort = 9;
$stopPort = 7;

if (isset($_GET["up"])) {
    rolloUp();

    writeLogEntry("Rollo wurde geöffnet!");

    //sendMail("Rollo wird geöffnet!", "");
    die(json_encode(array("success" => true)));
}
else if (isset($_GET["down"])) {
    rolloDown();
    //sendMail("Rollo wird geschlossen!", "");
    writeLogEntry("Rollo wurde geschlossen!");
    die(json_encode(array("success" => true)));
}
else if (isset($_GET["stop"])) {
    rolloStop();
    //sendMail("Rollo gestoppt!", "");
    writeLogEntry("Rollo wurde gestoppt!");
    die(json_encode(array("success" => true)));
}
else if (isset($_GET["position"])) {
    $position = floatval($_GET["position"]);

    // move all the way up
    rolloUp();

    // wait the maximum time to travel between closed and open state
    usleep($transitionTime);

    rolloDown();

    // wait the time the rollo needs to travel to the target position
    usleep($position * $transitionTime);

    rolloStop();

    // send status email
    //sendMail("Rollo zu Position gefahren!", "");
    writeLogEntry("Rollo wurde zu Position gefahren: " . $position);

    die(json_encode(array("success" => true)));
}

// control functions
function initPorts() {
    global $upPort, $downPort, $stopPort;
    
    // configure the ports 2,3,4 as output
    shell_exec("gpio mode $upPort out");
    shell_exec("gpio mode $downPort out");
    shell_exec("gpio mode $stopPort out");
    
    // reset all ports first
    shell_exec("gpio write $upPort 1");
    shell_exec("gpio write $downPort 1");
    shell_exec("gpio write $stopPort 1");
}

function rolloUp() {
    initPorts();
    global $upPort;
    shell_exec("gpio write $upPort 0");
}
function rolloDown() {
    initPorts();
    global $downPort;
    shell_exec("gpio write $downPort 0");
}
function rolloStop() {
    initPorts();
    global $stopPort;
    shell_exec("gpio write $stopPort 0");
}

function writeLogEntry($msg) {
    $todayLogFile = "logs/log_" . date("Y-m-d") . ".txt";
    $file = fopen($todayLogFile, "a");
    if ($file) {
        fwrite($file, "[" . date("H:i:s") . "] " . $msg . "\n");
        fclose($file);
    }
}
