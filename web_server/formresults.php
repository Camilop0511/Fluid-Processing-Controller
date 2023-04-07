<!DOCTYPE html>
<html>
    <head>
        <title>formresults.php</title>
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
    <body>
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

    // Loop through the array and send each byte with a delay between them
    foreach ($liquid_1 as $byte) {
        fwrite($serialPort, chr($byte));
        usleep(5000); // Delay between bytes in microseconds
    }
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

   $byte1 = 10; // Put your fixed byte value here
   $byte2 = 1; // Receive the tank2 value through POST
   $start = array($byte1, $byte2); // Create an array with two bytes
   $start = implode(array_map("chr", $start)); // Convert array to string of characters
   fwrite($serialPort, $start);
   fwrite($serialPort, $start);
   usleep(5000);

   fclose($serialPort);


?>












