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
            $port = fopen("/dev/ttyUSB0", "w");
            fwrite($port, "Starting data transmission\n");
            foreach ($values as $key => $value) {
                $hexValue = dechex($value);
                $bytes = str_split($hexValue, 2);

                foreach ($bytes as $byte) {
                    $byteValue = hexdec($byte);
                    fwrite($port, chr($byteValue));
                    usleep(1000); // wait for 1 millisecond
                }
            }
            fwrite($port, "Data transmission complete\n");
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


