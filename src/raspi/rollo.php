<?php

set_time_limit(0);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// needed time for transition:
// Full open <-> full closed: 41s
$transitionTime = 42000000; // in microseconds

// globals
$apiUrl = "http://test.benntec-quiz-app.de/rollo/api/index.php";
$week = array();
$timerEnabled = 0;
$cmd = "";
$cmdParam = "";

// ports for controller
$upPort = 8;
$downPort = 9;
$stopPort = 7;

// run code
if (!getState()) {
    die();
}

checkCmd();

if ($timerEnabled == 1)
    checkTimer();


// FUNCTIONS
function checkCmd() {
    global $cmd;
    if ($cmd == "up")
        rolloUp();
    else if ($cmd == "down")
        rolloDown();
    else if ($cmd == "stop")
        rolloStop();
    else if ($cmd == "position") {
        global $cmdParam;
        $position = floatval($cmdParam);
        
        // move all the way up
        rolloUp();
    
        // wait the maximum time to travel between closed and open state
        global $transitionTime;
        usleep($transitionTime);
    
        rolloDown();
    
        // wait the time the rollo needs to travel to the target position
        usleep($position * $transitionTime);
    
        rolloStop();
    }
}

function checkTimer() {
    global $week;

    # get open/shut times of the current weekday
    $now = date("H:i");

    # check if in this minute the timer already has fired
    if (file_exists("last_timer.txt")) {
        $lastTimer = file_get_contents("last_timer.txt");
        if ($now == $lastTimer) {
            return;
        }
    }
    file_put_contents("last_timer.txt", $now);

    $weekday = date("w"); // 0 = Sunday
    $openShut = $week[$weekday];

    # check if the open and the shut time equal 00:00, if so, the timer for today is disabled
    if ($openShut[0] != "00:00" || $openShut[1] != "00:00") {
        # check if we reached the open time
        if ($now == $openShut[0]) {
            rolloUp();
        }

        # check if we reached the shut time
        if ($now == $openShut[1]) {
            rolloDown();
        }
    }
}

// server query functions
function getState() {
    global $apiUrl;
    $res = file_get_contents($apiUrl . "?state=1");
    $resObj = json_decode($res);
    if ($resObj->success) {
        // set timings
        global $week;
        $week = $resObj->state->timings;
    
        // set timer state
        global $timerEnabled;
        $timerEnabled = $resObj->state->timer_enabled;
    
        // set command and parameter
        global $cmd;
        $cmd = $resObj->state->cmd;
        global $cmdParam;
        $cmdParam = $resObj->state->cmd_param;
    
        return true;
    }
    
    return false;
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

