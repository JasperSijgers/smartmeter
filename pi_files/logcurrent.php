<?php

exec("/usr/bin/python /home/pi/reader.py", $output);

// Database Values
$vebr_laag;
$vebr_hoog;
$terug_laag;
$terug_hoog;
$vebr_act;
$terug_act;
$gasstand;

foreach($output as $line){
    if(strpos($line, ':1.8.1') !== false){
        $vebr_laag = str_replace('*kWh)', '', explode('(', $line)[1]);
    }
    elseif(strpos($line, ':2.8.1') !== false){
        $vebr_hoog = str_replace('*kWh)', '', explode('(', $line)[1]);
    }
    elseif(strpos($line, ':1.8.2') !== false){
        $terug_laag = str_replace('*kWh)', '', explode('(', $line)[1]);
    }
    elseif(strpos($line, ':2.8.2') !== false){
        $terug_hoog = str_replace('*kWh)', '', explode('(', $line)[1]);
    }
    elseif(strpos($line, ':1.7.0') !== false){
        $vebr_act = str_replace('*kW)', '', explode('(', $line)[1]);
    }
    elseif(strpos($line, ':2.7.0') !== false){
        $terug_act = str_replace('*kW)', '', explode('(', $line)[1]);
    }
    elseif(strpos($line, ':24.2.1') !== false){
        $gasstand = str_replace('*m3)', '', explode('(', $line)[2]);
    }
}

$mysqli = new mysqli('127.0.0.1', 'smartmeter', '', 'smartmeter');
if ($mysqli->connect_errno) {
    exit;
}

$sql = "INSERT INTO `smartmeter`.`data` (`vebr_laag`, `vebr_hoog`, `terug_laag`, `terug_hoog`, `vebr_act`, `terug_act`, `gasstand`) VALUES ('$vebr_laag', '$vebr_hoog', '$terug_laag', '$terug_hoog', '$vebr_act', '$terug_act', '$gasstand');";

if ($mysqli->query($sql) === TRUE) {
    echo 'VEBR_LAAG: ' . $vebr_laag .
        '<br>VEBR_HOOG: ' . $vebr_hoog .
        '<br>TERUG_LAAG: ' . $terug_laag .
        '<br>TERUG_HOOG: ' . $terug_hoog .
        '<br>VEBR_ACT: ' . $vebr_act .
        '<br>TERUG_ACT: ' . $terug_act .
        '<br>GASSTAND: ' . $gasstand;
    echo '<br><b>inserted successfully</b>';
    echo $mysqli->error;
}

$mysqli->close();



?>