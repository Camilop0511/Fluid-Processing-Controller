<?php
	//Open the serial port for reading
    $serialPort = fopen('/dev/ttyS0', 'w');
    system('stty -F /dev/ttyS0 57600');

	//Stop signal 
    $byte1 = 95; 
    $byte2 = 1;
    $stop = array($byte1, $byte2); 
    $stop = implode(array_map("chr", $stop)); 
    fwrite($serialPort, $stop);
    usleep(5000);
    fclose($serialPort);
?>
