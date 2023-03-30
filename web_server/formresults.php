<!DOCTYPE html>
<html>
    <head>
        <title>formresults.php</title>
    </head>
    <body> 
        <p>
            Liquid level tank1: <?= dechex((int)$_POST['tank1']); ?> 
        </p> 
        <p>
            Liquid level tank2: <?= dechex((int)$_POST['tank2']); ?> 
        </p> 
        <p>
            Speed percentage water pump1: <?= dechex((int)$_POST['water_p1']); ?> 
        </p> 
        <p>
            Speed percentage water pump2: <?= dechex((int)$_POST['water_p2']); ?> 
        </p> 
        <p>
            Set temperature: <?= dechex((int)$_POST['temp']); ?> 
        </p> 
        <p>
            Cool-down time: <?= dechex((int)$_POST['sec']); ?> 
        </p> 
    </body>
</html>