<?php
$values = array(
    'tank1' => (int)$_POST['tank1'],
    'tank2' => (int)$_POST['tank2'],
    'water_p1' => (int)$_POST['water_p1'],
    'water_p2' => (int)$_POST['water_p2'],
    'temp' => (int)$_POST['temp'],
    'sec' => (int)$_POST['sec']
);

// Convert values to hex format
$hexValues = array_map('dechex', $values);

// Build command to send data over serial with screen
$serialDevice = '/dev/ttyS0'; // Replace with your device name
$baudRate = 57600;
$parity = 'n';
$dataBits = 8;
$stopBits = 1;
$command = "screen -S serial -dm bash -c 'stty -F $serialDevice $baudRate cs$dataBits $parity stopbits $stopBits && echo -n " . implode('', $hexValues) . " > $serialDevice'";

// Execute command in shell
shell_exec($command);
?>

<!DOCTYPE html>
<html>
<head>
    <title>formresults.php</title>
</head>
<body>
    <p>Data sent over serial in hex format:</p>
    <ul>
        <?php foreach ($hexValues as $key => $value) : ?>
            <li><?= $key ?>: <?= $value ?></li>
        <?php endforeach; ?>
    </ul>
</body>
</html>



