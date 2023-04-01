<?php
    // Open the serial port
    $serialPort = fopen('/dev/ttyUSB0', 'w');

    // Send the letter "T"
    fwrite($serialPort, 'T');

    // Close the serial port
    fclose($serialPort);
?>




