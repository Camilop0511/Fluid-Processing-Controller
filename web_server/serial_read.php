<?php 
    $serialPort = fopen('/dev/ttyS0', 'r');
    system('stty -F /dev/ttyS0 57600');
    
    //$real_volume = 22;
	// Create an empty array
	$json_array = array();


    $a3_received = false;
    $b4_received = false;
    $c5_received = false;
    $d1_received = false;
    $e2_received = false;
    $f6_received = false;
    $n9a_received = false;
    
   $start_time = time();
    
   while (time() - $start_time < 2) {
	 //while(true){
        $byte = fread($serialPort, 1);
       //echo "Received byte: " . bin2hex($byte) . "\n";

    
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
                if ($a3_received) {
                    //echo "Step: " . ord($byte) . "\n";
                    $step_rx = ord($byte);
                    $a3_received = false;
                } elseif ($b4_received) {
                    //echo "Temperature: " . ord($byte) . "\n";
                    $temperature_rx = ord($byte); 
                    $b4_received = false;
                } elseif ($c5_received) {
                    $level_pt = ord($byte);
                    $real_volume = ($level_pt * 6.24824) - 9.16149;
                    //echo "Level (pt): " . $level_pt . "\n";
                    //echo "Real Volume: " . intval($real_volume) . "\n";
                    $c5_received = false;
                } elseif ($d1_received) {
                    $start_state = ord($byte);
                   // echo "Start State: " . $start_state . "\n";
                    $d1_received = false;
                } elseif ($e2_received) {
                    $stop_state = ord($byte);
                   // echo "Stop State: " . $stop_state . "\n";
                    $e2_received = false;
                } elseif ($f6_received) {
                    $serve_state = ord($byte);
                    //echo "Serve State: " . $serve_state . "\n\n";
                    $f6_received = false;
                } elseif ($n9a_received) {
                    $serve_count = ord($byte);
                   // echo "Serve Count: " . $serve_count . "\n";
                    $n9a_received = false;
                }
            break;
        }
    }
    fclose($serialPort);

// Check if $step_rx has a value and add it to the array
if (isset($step_rx)) {
    $data[] = array(
        "step_rx" => $step_rx);
}

// Check if $temperature_rx has a value and add it to the array
if (isset($temperature_rx)) {
    $data[] = array(
        "temperature_rx" => $temperature_rx);
}

/*// Check if $real_volume has a value and add it to the array
if (isset($real_volume)) {
    $data[] = array(
        "real_volume" => $real_volume);
}

// Check if $start_state has a value and add it to the array
if (isset($start_state)) {
    $data[] = array(
        "start_state" => $start_state);
}

// Check if $stop_state has a value and add it to the array
if (isset($stop_state)) {
    $data[] = array(
        "stop_state" => $stop_state);
}

// Check if $serve_state has a value and add it to the array
if (isset($serve_state)) {
    $data[] = array(
        "serve_state" => $serve_state);
}

// Check if $serve_count has a value and add it to the array
if (isset($serve_count)) {
    $data[] = array(
        "serve_count" => $serve_count);
}*/

// Encode the array as a JSON string and echo it
echo json_encode($data);


?> 