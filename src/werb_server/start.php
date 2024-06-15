<?php
	//Open the serial port for reading
    $serialPort = fopen('/dev/ttyS0', 'w');
    system('stty -F /dev/ttyS0 57600');

    //Start signal
    $byte1 = 10; //Command byte
    $byte2 = 1; //Data byte
    $start = array($byte1, $byte2);
    $start = implode(array_map("chr", $start));
    fwrite($serialPort, $start);
    usleep(5000);
    fclose($serialPort);
?>

