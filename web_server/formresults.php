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
   
   $value = $_POST['tank1']; // Obtain the value of tank1 using POST
   fwrite($serialPort, chr($value));
   usleep(1000);
   fclose($serialPort);
?>






