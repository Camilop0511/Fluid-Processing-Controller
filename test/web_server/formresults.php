<!DOCTYPE html>
<html>
    <head>
        <title>formresults.php</title>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    </head>
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
        <!--<button onclick="serial_read()">Serial Test</button>-->
    <body>

    <script>
    //AJAX application that asynchronously retrieves and displays contents of the gpio.php file
        function start_process() {
            var xhttp = new XMLHttpRequest();   //Creates a XMLHttpRequest object
            xhttp.open("GET", "start.php"); //Sends a request
            xhttp.send();
        }   
    </script>
    <script>
    //AJAX application that asynchronously retrieves and displays contents of the gpio.php file
        function stop_process() {
            var xhttp = new XMLHttpRequest();   //Creates a XMLHttpRequest object
            xhttp.open("GET", "stop.php"); //Sends a request
            xhttp.send();
        }   
    </script>
    <script>
    //AJAX application that asynchronously retrieves and displays contents of the gpio.php file
        function serve() {
            var xhttp = new XMLHttpRequest();   //Creates a XMLHttpRequest object
            xhttp.open("GET", "serve.php"); //Sends a request
            xhttp.send();
        }   
    </script>
	<script>
    $(document).ready(function() {
        setInterval(function() {
            // make an AJAX request to fetch the values from serial_read.php
            $.getJSON('serial_read.php', function(data) {
                console.log(data);
                // update the values in the HTML elements
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
   
   // Clear any existing data in the buffer
   stream_set_blocking($serialPort, false);
   stream_set_timeout($serialPort, 0);
   $clearData = fread($serialPort, 8192);
   
   usleep(10000); // Wait for 10ms to ensure buffer is cleared
   
   // Flush the input and output buffers
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

    $byte1 = 74; // Put your fixed byte value here
    $byte2 = $_POST['tank1']; // Receive the tank1 value through POST
    $liquid_1 = array($byte1, $byte2); // Create an array with two bytes
    $liquid_1 = implode(array_map("chr", $liquid_1)); // Convert array to string of characters
    fwrite($serialPort, $liquid_1);

    /*// Loop through the array and send each byte with a delay between them
    foreach ($liquid_1 as $byte) {
        fwrite($serialPort, chr($byte));
        usleep(5000); // Delay between bytes in microseconds
    }*/
   usleep(5000);

   $byte1 = 44; // Put your fixed byte value here
   $byte2 = $_POST['tank2']; // Receive the tank2 value through POST
   $liquid_2 = array($byte1, $byte2); // Create an array with two bytes
   $liquid_2 = implode(array_map("chr", $liquid_2)); // Convert array to string of characters
   fwrite($serialPort, $liquid_2);
   usleep(5000);

   $byte1 = 91; // Put your fixed byte value here
   $byte2 = $_POST['water_p1']; // Receive the tank2 value through POST
   $wp1_speed = array($byte1, $byte2); // Create an array with two bytes
   $wp1_speed = implode(array_map("chr", $wp1_speed)); // Convert array to string of characters
   fwrite($serialPort, $wp1_speed);
   usleep(5000);

   $byte1 = 61; // Put your fixed byte value here
   $byte2 = $_POST['water_p2']; // Receive the tank2 value through POST
   $wp2_speed = array($byte1, $byte2); // Create an array with two bytes
   $wp2_speed = implode(array_map("chr", $wp2_speed)); // Convert array to string of characters
   fwrite($serialPort, $wp2_speed);
   usleep(5000);

   $byte1 = 31; // Put your fixed byte value here
   $byte2 = $_POST['temp']; // Receive the tank2 value through POST
   $temperature = array($byte1, $byte2); // Create an array with two bytes
   $temperature = implode(array_map("chr", $temperature)); // Convert array to string of characters
   fwrite($serialPort, $temperature);
   usleep(5000);

   $byte1 = 78; // Put your fixed byte value here
   $byte2 = $_POST['hres']; // Receive the tank2 value through POST
   $resistance = array($byte1, $byte2); // Create an array with two bytes
   $resistance = implode(array_map("chr", $resistance)); // Convert array to string of characters
   fwrite($serialPort, $resistance);
   usleep(5000);

   $byte1 = 14; // Put your fixed byte value here
   $byte2 = $_POST['sec']; // Receive the tank2 value through POST
   $cooldown = array($byte1, $byte2); // Create an array with two bytes
   $cooldown = implode(array_map("chr", $cooldown)); // Convert array to string of characters
   fwrite($serialPort, $cooldown);
   usleep(5000);

   /*$byte1 = 10; // Put your fixed byte value here
   $byte2 = 1; // Receive the tank2 value through POST
   $start = array($byte1, $byte2); // Create an array with two bytes
   $start = implode(array_map("chr", $start)); // Convert array to string of characters
   fwrite($serialPort, $start);
   usleep(5000);*/

   fclose($serialPort);
?>

