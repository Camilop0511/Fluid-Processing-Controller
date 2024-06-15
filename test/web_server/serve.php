<?php
    $serialPort = fopen('/dev/ttyS0', 'w');
    system('stty -F /dev/ttyS0 57600');

    $byte1 = 27; // Put your fixed byte value here
    $byte2 = 1; // Receive the tank2 value through POST
    $serve = array($byte1, $byte2); // Create an array with two bytes
    $serve = implode(array_map("chr", $serve)); // Convert array to string of characters
    fwrite($serialPort, $serve);
    usleep(5000);
    fclose($serialPort);
?>