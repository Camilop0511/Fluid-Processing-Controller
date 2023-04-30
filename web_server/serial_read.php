<?php 
    $serialPort = fopen('/dev/ttyS0', 'r');
    system('stty -F /dev/ttyS0 57600');
    
    //$real_volume = 22;

    $a3_received = false;
    $b4_received = false;
    $c5_received = false;
    $d1_received = false;
    $e2_received = false;
    $f6_received = false;
    $n9a_received = false;
    
   $start_time = time();
    
   while (time() - $start_time < 2) {
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
                    echo "Step: " . ord($byte) . "\n";
                    $step_rx = ord($byte);
                    $a3_received = false;
                } elseif ($b4_received) {
                    echo "Temperature: " . ord($byte) . "\n";
                    $temperature_rx = ord($byte); 
                    $b4_received = false;
                } elseif ($c5_received) {
                    $level_pt = ord($byte);
                    $real_volume = ($level_pt * 6.24824) - 9.16149;
                    echo "Level (pt): " . $level_pt . "\n";
                    echo "Real Volume: " . intval($real_volume) . "\n";
                    $c5_received = false;
                } elseif ($d1_received) {
                    $start_state = ord($byte);
                    echo "Start State: " . $start_state . "\n";
                    $d1_received = false;
                } elseif ($e2_received) {
                    $stop_state = ord($byte);
                    echo "Stop State: " . $stop_state . "\n";
                    $e2_received = false;
                } elseif ($f6_received) {
                    $serve_state = ord($byte);
                    echo "Serve State: " . $serve_state . "\n\n";
                    $f6_received = false;
                } elseif ($n9a_received) {
                    $serve_count = ord($byte);
                    echo "Serve Count: " . $serve_count . "\n";
                    $n9a_received = false;
                }
            break;
        }
    }
    fclose($serialPort);


	$data = array();

    // return variables as JSON
    $data['step'] = $step_rx;

	$json = json_encode($data);

   echo json_encode($data);
?> 