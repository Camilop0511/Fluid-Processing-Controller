<?php 
    //Opens serial port for reading
    $serialPort = fopen('/dev/ttyS0', 'r');
    //Sets the baud rate of serial port to 57600
    system('stty -F /dev/ttyS0 57600');
    
	//Create an empty array
	$json_array = array(2);

    //Variables initialization
    $a3_received = false;
    $b4_received = false;
    $c5_received = false;
    $d1_received = false;
    $e2_received = false;
    $f6_received = false;
    $n9a_received = false;
    
   $start_time = time();
   
   //Reads for 1 second
   while (time() - $start_time < 1) {
        $byte = fread($serialPort, 1);      //Reads one byte from the serial port
    
        //Check $byte and assign corresponding values to variables
        switch ($byte) {
            case "\xa3":
                $a3_received = true;
                $b4_received = false;
                $c5_received = false;
                $d1_received = false;
                $e2_received = false;
                $f6_received = false;
                $n9a_received = false;
                break;
            case "\xb4":
                $a3_received = false;
                $b4_received = true;
                $c5_received = false;
                $d1_received = false;
                $e2_received = false;
                $f6_received = false;
                $n9a_received = false;
                break;
            case "\xc5":
                $a3_received = false;
                $b4_received = false;
                $c5_received = true;
                $d1_received = false;
                $e2_received = false;
                $f6_received = false;
                $n9a_received = false;
                break;
            case "\xd1":
                $a3_received = false;
                $b4_received = false;
                $c5_received = false;
                $d1_received = true;
                $e2_received = false;
                $f6_received = false;
                $n9a_received = false;
                break;
            case "\xe2":
                $a3_received = false;
                $b4_received = false;
                $c5_received = false;
                $d1_received = false;
                $e2_received = true;
                $f6_received = false;
                $n9a_received = false;
                break;
            case "\xf6":
                $a3_received = false;
                $b4_received = false;
                $c5_received = false;
                $d1_received = false;
                $e2_received = false;
                $f6_received = true;
                $n9a_received = false;
                break;
            case "\x9a":
                $a3_received = false;
                $b4_received = false;
                $c5_received = false;
                $d1_received = false;
                $e2_received = false;
                $f6_received = false;
                $n9a_received = true;
                break;
            default:
                //Step
                if ($a3_received) {
                    $step_rx = ord($byte);
                    $a3_received = false;
                } 
                //Temperature
                elseif ($b4_received) {
                    $temperature_rx = ord($byte); 
                    $b4_received = false;
                } 
                //Volume
                elseif ($c5_received) {
                    $level_pt = ord($byte);
                    $real_volume = ($level_pt * 6.24824) - 9.16149;
                    if($real_volume < 0){
                        $real_volume = 0;
                    }
                    $real_volume_int = intval($real_volume);
                    $c5_received = false;
                } 
                //Start State
                elseif ($d1_received) {
                    $start_state = ord($byte);
                    $d1_received = false;
                } 
                //Stop State
                elseif ($e2_received) {
                    $stop_state = ord($byte);
                    $e2_received = false;
                } 
                //Serve State
                elseif ($f6_received) {
                    $serve_state = ord($byte);
                    $f6_received = false;
                } 
                 //Serve Count
                elseif ($n9a_received) {
                    $serve_count = ord($byte);
                    $n9a_received = false;
                }
            break;
        }
    }
    fclose($serialPort);

// Check if $step_rx has a value and add it to the array
if (isset($step_rx)) {
    $data[0] = array(
        "step_rx" => $step_rx);
}

// Check if $temperature_rx has a value and add it to the array
if (isset($temperature_rx)) {
    $data[1] = array(
        "temperature_rx" => $temperature_rx);
}

// Check if $real_volume_int has a value and add it to the array
if (isset($real_volume_int)) {
    $data[2] = array(
        "real_volume_int" => $real_volume_int);
}

// Check if $start_state has a value and add it to the array
if (isset($start_state)) {
    $data[3] = array(
        "start_state" => $start_state);
}

// Check if $stop_state has a value and add it to the array
if (isset($stop_state)) {
    $data[4] = array(
        "stop_state" => $stop_state);
}

// Check if $serve_state has a value and add it to the array
if (isset($serve_state)) {
    $data[5] = array(
        "serve_state" => $serve_state);
}

// Check if $n9a_received has a value and add it to the array
if (isset($serve_count)) {
    $data[6] = array(
        "serve_count" => $serve_count);
}

// Encode the array as a JSON string and echo it
echo json_encode($data);
?> 