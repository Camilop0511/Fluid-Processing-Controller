<!DOCTYPE html>
<html>
    <head>
        <title>formresults.php</title>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    </head>
    <!--Posts the parameters previously entered by the user-->
    <body> 
        <p>
            Liquid level tank1: <?= (int)$_POST['tank1']; ?> 
        </p> 
        <p>
            Liquid level tank2: <?= (int)$_POST['tank2']; ?> 
        </p> 
        <p>
            Water pump 1 speed: <?= (int)$_POST['water_p1']; ?> 
        </p>
        <p>
            Water pump 2 speed: <?= (int)$_POST['water_p2']; ?> 
        </p>
        <p>
            Set temperature: <?= (int)$_POST['temp']; ?> 
        </p>
        <p>
            Heating Resistance Power: <?= (int)$_POST['hres']; ?> 
        </p>
        <p>
            Cooldown Time: <?= (int)$_POST['sec']; ?> 
        </p>
        <br>
        <br>
        <p>
             Step: <span id="step_rx"></span> 
        </p>
		<p>
             Temperature: <span id="temperature_rx"></span> 
        </p>
        <p>
             Processing Tank Volume: <span id="real_volume_int"></span> 
        </p>
        <br>
        <p>
             Start State: <span id="start_state"></span> 
        </p>
        <p>
             Stop State: <span id="stop_state"></span> 
        </p>
        <br>
        <p>
             Serve State: <span id="serve_state"></span> 
        </p>
        <p>
             Cooldown Counter: <span id="serve_count"></span> 
        </p>

        <button onclick="start_process()">Start Process</button>
        <button onclick="stop_process()">Stop Process</button>
        <button onclick="serve()">Serve Liquid</button>
    <body>

    <script>
    //AJAX script to make GET request to "start.php" file
        function start_process() {
            var xhttp = new XMLHttpRequest();   //Creates a XMLHttpRequest object
            xhttp.open("GET", "start.php");     //Opens connection
            xhttp.send();                       //Sends a request
        }   
    </script>

    <script>
     //AJAX script to make GET request to "stop.php" file
        function stop_process() {
            var xhttp = new XMLHttpRequest();  
            xhttp.open("GET", "stop.php"); 
            xhttp.send();
        }   
    </script>

    <script>
    //AJAX script to make GET request to "serve.php" file
        function serve() {
            var xhttp = new XMLHttpRequest(); 
            xhttp.open("GET", "serve.php"); 
            xhttp.send();
        }   
    </script>

	<script>
    $(document).ready(function() {
        setInterval(function() {
            //Makes  AJAX request to fetch the values from serial_read.php
            $.getJSON('serial_read.php', function(data) {
                console.log(data);

                //Update the values in the HTML elements only if conditions are true
                if (data[0] && data[0].step_rx >= 0 && data[0].step_rx <= 10) {
                    $('#step_rx').text(data[0].step_rx);
                }
                if(data[1] && data[1].temperature_rx >= 15 && data[1].temperature_rx <= 65){
                    $('#temperature_rx').text(data[1].temperature_rx);
                }
                if(data[2] && data[2].real_volume_int >= 0 && data[2].real_volume_int <= 1500){
                    $('#real_volume_int').text(data[2].real_volume_int);
                }
                if(data[3] && data[3].start_state >= 0 && data[3].start_state <= 1){
                    $('#start_state').text(data[3].start_state);
                }
                if(data[4]&& data[4].stop_state >= 0 && data[4].stop_state <= 1){
                    $('#stop_state').text(data[4].stop_state);
                }
                if(data[5]&& data[5].serve_state >= 0 && data[5].serve_state <= 1){
                    $('#serve_state').text(data[5].serve_state);
                }
                if(data[6]&& data[6].serve_count >= 0 && data[6].serve_count <= 45){
                    $('#serve_count').text(data[6].serve_count);
                }

            }).fail(function(jqXHR, textStatus, errorThrown) {
                console.error(textStatus, errorThrown);
            });

        }, 1000); // fetch the data every 1 second
    });
	</script>
</html>


<?php
   $serialPort = fopen('/dev/ttyS0', 'w');
   system('stty -F /dev/ttyS0 57600');
   
   //Clears any existing data in the buffer
   stream_set_blocking($serialPort, false);
   stream_set_timeout($serialPort, 0);
   $clearData = fread($serialPort, 8192);
   
   usleep(10000); //Waits for 10ms to ensure buffer is cleared
   
   //Flush the input and output buffers
   exec('stty -F /dev/ttyS0 -icrnl');
   exec('stty -F /dev/ttyS0 -inlcr');
   exec('stty -F /dev/ttyS0 -opost');
   exec('stty -F /dev/ttyS0 -onlcr');
   exec('stty -F /dev/ttyS0 -isig');
   exec('stty -F /dev/ttyS0 -icanon');
   exec('stty -F /dev/ttyS0 -echo');
   exec('stty -F /dev/ttyS0 -echoe');
   exec('stty -F /dev/ttyS0 -echok');
   exec('stty -F /dev/ttyS0 -echoctl');
   exec('stty -F /dev/ttyS0 -echoke');
   exec('stty -F /dev/ttyS0 -crtscts');
   exec('stty -F /dev/ttyS0 57600');
   exec('stty -F /dev/ttyS0 -icrnl');
   exec('stty -F /dev/ttyS0 -inlcr');
   exec('stty -F /dev/ttyS0 -opost');
   exec('stty -F /dev/ttyS0 -onlcr');
   exec('stty -F /dev/ttyS0 -isig');
   exec('stty -F /dev/ttyS0 -icanon');
   exec('stty -F /dev/ttyS0 -echo');
   exec('stty -F /dev/ttyS0 -echoe');
   exec('stty -F /dev/ttyS0 -echok');
   exec('stty -F /dev/ttyS0 -echoctl');
   exec('stty -F /dev/ttyS0 -echoke');
   exec('stty -F /dev/ttyS0 -crtscts');

    //Tank 1 liquid quantity
    $byte1 = 74; //Command byte
    $byte2 = $_POST['tank1']; //Data byte
    $liquid_1 = array($byte1, $byte2); //Array with two bytes
    $liquid_1 = implode(array_map("chr", $liquid_1)); // Convert array to string of characters
    fwrite($serialPort, $liquid_1); //Write to serial port
   usleep(5000);

   //Tank 2 liquid quantity
   $byte1 = 44; 
   $byte2 = $_POST['tank2']; 
   $liquid_2 = array($byte1, $byte2); 
   $liquid_2 = implode(array_map("chr", $liquid_2)); 
   fwrite($serialPort, $liquid_2);
   usleep(5000);

   //Water pump 1 speed
   $byte1 = 91; 
   $byte2 = $_POST['water_p1']; 
   $wp1_speed = array($byte1, $byte2); 
   $wp1_speed = implode(array_map("chr", $wp1_speed)); 
   fwrite($serialPort, $wp1_speed);
   usleep(5000);

   //Water pump 2 speed
   $byte1 = 61; 
   $byte2 = $_POST['water_p2']; 
   $wp2_speed = array($byte1, $byte2); 
   $wp2_speed = implode(array_map("chr", $wp2_speed)); 
   fwrite($serialPort, $wp2_speed);
   usleep(5000);

   //Temperature
   $byte1 = 31; 
   $byte2 = $_POST['temp']; 
   $temperature = array($byte1, $byte2); 
   $temperature = implode(array_map("chr", $temperature)); 
   fwrite($serialPort, $temperature);
   usleep(5000);

   //Resistance power
   $byte1 = 78; 
   $byte2 = $_POST['hres']; 
   $resistance = array($byte1, $byte2); 
   $resistance = implode(array_map("chr", $resistance)); 
   fwrite($serialPort, $resistance);
   usleep(5000);

   //Cooldown counter
   $byte1 = 14; 
   $byte2 = $_POST['sec']; 
   $cooldown = array($byte1, $byte2); 
   $cooldown = implode(array_map("chr", $cooldown)); 
   fwrite($serialPort, $cooldown);
   usleep(5000);

   fclose($serialPort);
?>

