<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width" />
        <title>Rollo Control</title>
        
        <style>
            h1, h4 {
                width: 100%;
                text-align: center;
            }
            
            #buttonContainer {
                margin: auto;
                width: 7em;
                height: 6em;
            }
            
            button {
                -webkit-appearance: none;
                padding: 0px;
                width: 2em;
                height: 2em;
                font-size: 60px;
                border-radius: 50%;
                border: 10px solid #cfdcec;
                background: #fff; 
                box-shadow: 0 0 3px gray;
            }
            button:active {
                border: 10px solid #4679BD;
            }
            
            p {
                width: 100%;
                text-align: center;
            }
        </style>
    </head>
    <body>
        <h1>Rollo Control v1.0</h1>
        <h4>by Björn Bosse</h4>
        
        <?php
        
        // needed time for transition:
        // Full open <-> full closed: 41s
        
        // TODO: read state from the database
        // state can be: 
        // 0 = closed
        // 1 = open
        // 2 = stopped
        
        $db = connectDb();
        $config = getConfig();
        
        // ports for controller
        $upPort = 8;
        $downPort = 9;
        $stopPort = 7;
        
        initPorts();
        
        if (isset($_GET["up"])) {
            rolloUp();
            echo "<p>Rollo wird geöffnet!</p>";
            sendMail("Rollo wird geöffnet!", "");
        }
        else if (isset($_GET["down"])) {
            rolloDown();
            echo "<p>Rollo wird geschlossen!</p>";
            sendMail("Rollo wird geschlossen!", "");
        }
        else if (isset($_GET["stop"])) {
            rolloStop();
            echo "<p>Rollo gestoppt!</p>";
            sendMail("Rollo gestoppt!", "");
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
            global $upPort;
            shell_exec("gpio write $upPort 0");
        }
        function rolloDown() {
            global $downPort;
            shell_exec("gpio write $downPort 0");
        }
        function rolloStop() {
            global $stopPort;
            shell_exec("gpio write $stopPort 0");
        }
        
        // DATABASE FUNCTIONS
        function getConfig() {
            global $db;
            
            $res = $db->query("SELECT * FROM config;");
            $row = $res->fetch_assoc();
            return $row;
        }
        
        function setConfig($status, $stopTime) {
            global $db;
            
            $db->query("UPDATE config SET status=$status, stop_time=$stopTime;");
        }
        
        function connectDb() {
            return mysqli_connect("127.0.0.1", "root", "raspberry", "rollo");
        }
        
        function sendMail($subject, $msg) {
            require_once "lib/swift-mailer/swift_required.php";

            $transport = Swift_SmtpTransport::newInstance("smtp.gmail.com", 465, "ssl")
            ->setUsername("b.bosse1991@gmail.com")
            ->setPassword("t!ct@cto3");

            $mailer = Swift_Mailer::newInstance($transport);

            $message = Swift_Message::newInstance($subject)
            ->setFrom(array('status@rollo.de' => 'Rollo status'))
            ->setTo(array("b.bosse1991@gmail.com"))
            ->setBody($msg);

            $result = $mailer->send($message);
        }

        ?>
        
		<!--<h2>Nutzer 'Malte' hat leider keinen Zugriff auf diese Funktion! <br><br>MUHAHAHAHA!</h2>-->
		
        <div id="buttonContainer">
            
            <form method="get" action="#">
                <button type="submit" name="up">&#9650;</button><br>
                <button type="submit" name="stop">&#9609;</button><br>
                <button type="submit" name="down">&#9660;</button> 
            </form>
            
        </div>
    </body>
</html>
