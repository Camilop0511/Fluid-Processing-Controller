<?php
   $serialPort = fopen('/dev/ttyS0', 'w');
   system('stty -F /dev/ttyS0 57600');
   $value = 100;
   fwrite($serialPort, chr($value));
   usleep(1000);
   fclose($serialPort);
?>




