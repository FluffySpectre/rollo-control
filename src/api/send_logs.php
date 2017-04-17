<?php

// check if a log file for today was created
$todayLogFile = "logs/log_" . date("Y-m-d") . ".txt";
if (!is_readable($todayLogFile)) {
    die("Nothing to send!");
}

// read log entries from file and send them to me
$logEntries = file_get_contents($todayLogFile);
sendMail("Rollo-Log vom " . date("d.m.Y"), $logEntries);


function sendMail($subject, $msg) {
    require_once "lib/swift-mailer/swift_required.php";

    $transport = Swift_SmtpTransport::newInstance("smtp.gmail.com", 465, "ssl")
    ->setUsername("b.bosse1991@gmail.com")
    ->setPassword("t!ct@cto3");

    $mailer = Swift_Mailer::newInstance($transport);

    $message = Swift_Message::newInstance($subject)
    ->setFrom(array("status@rollo.de" => "Rollo status"))
    ->setTo(array("b.bosse1991@gmail.com"))
    ->setBody($msg);

    $result = $mailer->send($message);
}