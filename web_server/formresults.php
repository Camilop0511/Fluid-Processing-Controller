<!DOCTYPE html>
<html>
    <head>
        <title>formresults.php</title>
    </head>
    <body> 
        <?php
            $values = array(
                'tank1' => (int)$_POST['tank1'],
                'tank2' => (int)$_POST['tank2'],
                'water_p1' => (int)$_POST['water_p1'],
                'water_p2' => (int)$_POST['water_p2'],
                'temp' => (int)$_POST['temp'],
                'sec' => (int)$_POST['sec']
            );
            $port = fopen("/dev/ttyS0", "w");

            // Set serial port settings
            stream_set_write_buffer($port, 0);
            stream_set_blocking($port, false);
            stream_set_timeout($port, 1);
            $serial_settings = "57600n8";
            exec("stty -F /dev/ttyS0 $serial_settings");

            foreach ($values as $key => $value) {
                $hexValue = dechex($value);
                $bytes = str_split($hexValue, 2);

                foreach ($bytes as $byte) {
                    $byteValue = hexdec($byte);
                    fwrite($port, chr($byteValue));
                    usleep(1000); // wait for 1 millisecond
                }
            }

            fclose($port);
        ?>
        <p>Data sent over serial in hex format:</p>
        <ul>
            <?php foreach ($values as $key => $value) : ?>
                <li><?= $key ?>: <?= dechex($value) ?></li>
            <?php endforeach; ?>
        </ul>
    </body>
</html>
