<?php
$values = array(
    'tank1' => (int)$_POST['tank1'],
    'tank2' => (int)$_POST['tank2'],
    'water_p1' => (int)$_POST['water_p1'],
    'water_p2' => (int)$_POST['water_p2'],
    'temp' => (int)$_POST['temp'],
    'sec' => (int)$_POST['sec']
);

$serial_port = "/dev/ttyS0";
$baud_rate = 57600;
$command = "screen -L $serial_port $baud_rate,cs8,-parenb,-cstopb";

foreach ($values as $key => $value) {
    $hexValue = dechex($value);
    $bytes = str_split($hexValue, 2);

    foreach ($bytes as $byte) {
        $byteValue = hexdec($byte);
        $escapedByte = escapeshellarg(chr($byteValue));
        $cmd = "echo -ne $escapedByte > $serial_port";
        shell_exec($cmd);
        usleep(1000); // wait for 1 millisecond
    }
}

// Open screen and send data
shell_exec($command);
?>

