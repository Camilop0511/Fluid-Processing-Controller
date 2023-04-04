<!DOCTYPE html>
<html>
    <head>
        <title>formresults.php</title>
    </head>
    <body> 
        <p>
            Liquid level tank1: <?= dechex((int)$_POST['tank1']); ?> 
        </p> 
        <p>
            Liquid level tank2: <?= dechex((int)$_POST['tank2']); ?> 
        </p> 
        <p>
            Water pump 1 speed: <?= dechex((int)$_POST['water_p1']); ?> 
        </p>
        <p>
            Water pump 2 speed: <?= dechex((int)$_POST['water_p2']); ?> 
        </p>
    <body>
</html>

<?php
   $serialPort = fopen('/dev/ttyS0', 'w');
   system('stty -F /dev/ttyS0 57600');
   
   /*// Clear any existing data in the buffer
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
   exec('stty -F /dev/ttyS0 -crtscts');*/
   
   /*$value = $_POST['tank1']; // Obtain the value of tank1 using POST
   fwrite($serialPort, chr($value));
   usleep(1000);
   fclose($serialPort);*/

   /*$byte1 = 91; // Put your fixed byte value here
   $byte2 = $_POST['tank1']; // Receive the tank1 value through POST
   $liquid_1 = array($byte1, $byte2); // Create an array with two bytes
   $liquid_1 = implode(array_map("chr", $liquid_1)); // Convert array to string of characters
   fwrite($serialPort, $liquid_1);
   
   usleep(2000);

   $byte1 = 74; // Put your fixed byte value here
   $byte2 = $_POST['tank2']; // Receive the tank2 value through POST
   $liquid_2 = array($byte1, $byte2); // Create an array with two bytes
   $liquid_2 = implode(array_map("chr", $liquid_2)); // Convert array to string of characters
   fwrite($serialPort, $liquid_2);*/

   usleep(2000);

   $byte1 = 61; // Put your fixed byte value here
   $byte2 = $_POST['water_p1']; // Receive the tank2 value through POST
   $wp1_speed = array($byte1, $byte2); // Create an array with two bytes
   $wp1_speed = implode(array_map("chr", $wp1_speed)); // Convert array to string of characters
   fwrite($serialPort, $wp1_speed);

   usleep(2000);

   $byte1 = 44; // Put your fixed byte value here
   $byte2 = $_POST['water_p2']; // Receive the tank2 value through POST
   $wp2_speed = array($byte1, $byte2); // Create an array with two bytes
   $wp2_speed = implode(array_map("chr", $wp2_speed)); // Convert array to string of characters
   fwrite($serialPort, $wp2_speed);

   usleep(2000);

   $byte1 = 31; // Put your fixed byte value here
   $byte2 = $_POST['temp']; // Receive the tank2 value through POST
   $temperature = array($byte1, $byte2); // Create an array with two bytes
   $temperature = implode(array_map("chr", $temperature)); // Convert array to string of characters
   fwrite($serialPort, $temperature);
  
   
   usleep(1000);
   fclose($serialPort);


?>






