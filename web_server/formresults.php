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
            Liquid leve tank2: <?= dechex((int)$_POST['tank2']); ?> 
        </p> 
        <p>
            Speed percentage water pump1: <?= (int)$_POST['water_p1']; ?> 
        </p> 
        <p>
            Speed percentage water pump2: <?= (int)$_POST['water_p2']; ?> 
        </p> 
        <p>
            Set temperature: <?= (int)$_POST['temp']; ?> 
        </p> 
        <p>
            Cool-down time: <?= (int)$_POST['sec']; ?> 
        </p> 
    </body>
</html>