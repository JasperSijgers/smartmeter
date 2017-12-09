<?php

// Connect to the MySQL database
$mysqli = new mysqli('home.jaspersijgers.nl', 'smartmeter', '', 'smartmeter');

// If there is an error, disconnect
if ($mysqli->connect_errno) {
    echo('ERROR CONNECTING<br>');
    exit;
}

// Get the appropriate dates
$current_date = date('Y-m-d H');
$previous_hour = date('Y-m-d H', strtotime('-1 hour'));

$sql = "SELECT timestamp, verbr_laag, verbr_hoog FROM `data` WHERE (`timestamp` BETWEEN '$previous_hour:59:00' AND '$previous_hour:59:59')";

for($i = 0; $i < date('i'); $i++){
    $sql .= " OR (`timestamp` BETWEEN '$current_date:$i:00' AND '$current_date:$i:59')";
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