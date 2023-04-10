<?php
    $serialPort = fopen('/dev/ttyS0', 'w');
    system('stty -F /dev/ttyS0 57600');

    $byte1 = 95; // Put your fixed byte value here
    $byte2 = 1; // Receive the tank2 value through POST
    $stop = array($byte1, $byte2); // Create an array with two bytes
    $stop = implode(array_map("chr", $stop)); // Convert array to string of characters
    fwrite($serialPort, $stop);
    usleep(5000);
    fclose($serialPort);
?>