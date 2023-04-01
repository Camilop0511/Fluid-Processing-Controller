<?php
   $serialPort = fopen('/dev/ttyS0', 'w');
   system('stty -F /dev/ttyS0 57600');
   
   // Clear any existing data in the buffer
   stream_set_blocking($serialPort, false);
   stream_set_timeout($serialPort, 0);
   $clearData = fread($serialPort, 8192);
   
   usleep(10000); // Wait for 10ms to ensure buffer is cleared
   
   $value = 67;
   fwrite($serialPort, chr($value));
   usleep(1000);
   fclose($serialPort);
?>






