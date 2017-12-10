<?php

// Connect to the MySQL database
$mysqli = new mysqli('127.0.0.1', 'smartmeter', '', 'smartmeter');

// If there is an error, disconnect
if ($mysqli->connect_errno) {
    echo('ERROR CONNECTING<br>');
    exit;
}

// Get the appropriate dates
$yesterday = date('Y-m-d', strtotime('yesterday'));

$sql = "SELECT timestamp, verbr_laag, verbr_hoog FROM `data` WHERE (`timestamp` BETWEEN '$yesterday 23:59:00' AND '$yesterday 23:59:59')";

$weekday = date('w');
if($weekday == 0) $weekday = 7;

for($i = 2; $i < $weekday + 1; $i++){
    $date = date('Y-m-d', strtotime('-' . $i . ' days'));
    $sql .= " OR (`timestamp` BETWEEN '$date 23:59:00' AND '$date 23:59:59')";
}

// If there is an error with the query, disconnect
if (!$result = $mysqli->query($sql)) {
    echo('ERROR EXECUTING QUERY<br>');
    exit;
}

// If the amount of rows returned is 0, disconnect
if ($result->num_rows === 0) {
    echo('0 RESULTS<br>');
    exit;
}

// Create a new array
$result_array = array();

// Loop through the resultset and add the results to the array
while ($row = $result->fetch_assoc()) {
    $result_array[] = $row;
}

// Encode the array to JSON and print it to the page
echo json_encode($result_array);

// Disconnect
$result->free();
$mysqli->close();