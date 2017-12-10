<?php

// Connect to the MySQL database
$mysqli = new mysqli('127.0.0.1', 'smartmeter', '', 'smartmeter');

// If there is an error, disconnect
if ($mysqli->connect_errno) {
    echo('ERROR CONNECTING<br>');
    exit;
}

// Get the appropriate dates
$current_date = date('Y-m-d');
$yesterday = date('Y-m-d', strtotime('yesterday'));

$sql = "SELECT timestamp, verbr_laag, verbr_hoog FROM `data` WHERE (`timestamp` BETWEEN '$yesterday 23:45:00' AND '$yesterday 23:45:59')";

for($i = 0; $i < 24; $i++){
    $sql .= " OR (`timestamp` BETWEEN '$current_date $i:00:00' AND '$current_date $i:00:59')";
    $sql .= " OR (`timestamp` BETWEEN '$current_date $i:15:00' AND '$current_date $i:15:59')";
    $sql .= " OR (`timestamp` BETWEEN '$current_date $i:30:00' AND '$current_date $i:30:59')";
    $sql .= " OR (`timestamp` BETWEEN '$current_date $i:45:00' AND '$current_date $i:45:59')";
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