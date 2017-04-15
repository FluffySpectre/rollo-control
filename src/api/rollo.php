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

            #shutterContainer {
                width: 2em;
                height: 6em;
                margin-top: 1em;
            }

            #btnShut1 {
                border: none;
                border-radius: 0;
                background-image: url("images/state_1.png");
                background-size: 2em 2em;
                background-repeat: no-repeat;
                width: 2em;
                height: 2em;
            }

            #btnShut2 {
                border: none;
                border-radius: 0;
                background-image: url("images/state_2.png");
                background-size: 2em 2em;
                background-repeat: no-repeat;
                width: 2em;
                height: 2em;
            }

            #btnShut3 {
                border: none;
                border-radius: 0;
                background-image: url("images/state_3.png");
                background-size: 2em 2em;
                background-repeat: no-repeat;
                width: 2em;
                height: 2em;
            }

            .spinner {
                background-image: url("https://d13yacurqjgara.cloudfront.net/users/82092/screenshots/1073359/spinner.gif");
            }
        </style>
    </head>
    <body>
        <h1>Rollo Control v1.0</h1>
        <h4>by Björn Bosse</h4>
        
        <?php
        
        // needed time for transition:
        // Full open <-> full closed: 41s
        $transitionTime = 42000000; // in microseconds

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
            echo "<p>Rollo zu Position gefahren!</p>";
            sendMail("Rollo zu Position gefahren!", "");
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

            <div id="shutterContainer">
                <form method="get" action="#">
                    <input type="hidden" name="position" value="0.33" /> 
                    <button type="submit" id="btnShut1" onclick="onShutClick(1)"></button>
                </form>
                <form method="get" action="#">
                    <input type="hidden" name="position" value="0.5" /> 
                    <button type="submit" id="btnShut2" onclick="onShutClick(2)"></button>
                </form>
                <form method="get" action="#">
                    <input type="hidden" name="position" value="0.66" /> 
                    <button type="submit" id="btnShut3" onclick="onShutClick(3)"></button>
                </form>
            </div>
        </div>
    </body>
</html>
