<?php
	//Open the serial port for reading
    $serialPort = fopen('/dev/ttyS0', 'w');
    system('stty -F /dev/ttyS0 57600');

	//Serve signal
    $byte1 = 27; //Command byte
    $byte2 = 1; //Data byte
    $serve = array($byte1, $byte2); 
    $serve = implode(array_map("chr", $serve));
    fwrite($serialPort, $serve);
    usleep(5000);
    fclose($serialPort);
?>
