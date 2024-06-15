<?php
    $serialPort = fopen('/dev/ttyS0', 'w');
    system('stty -F /dev/ttyS0 57600');

    $byte1 = 10; // Put your fixed byte value here
    $byte2 = 1; // Receive the tank2 value through POST
    $start = array($byte1, $byte2); // Create an array with two bytes
    $start = implode(array_map("chr", $start)); // Convert array to string of characters
    fwrite($serialPort, $start);
    usleep(5000);
    fclose($serialPort);
?>