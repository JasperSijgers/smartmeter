<?php

// Connect to the MySQL database
$mysqli = new mysqli('127.0.0.1', 'smartmeter', '', 'smartmeter');

// If there is an error, disconnect
if ($mysqli->connect_errno) {
    echo('ERROR CONNECTING<br>');
    exit;
}

// Get the appropriate dates
$current_date = date('Y-m-d H:i:s');

$sql = "(SELECT `timestamp`, `verbr_laag`, `verbr_hoog`, `terug_laag`, `terug_hoog` FROM `data` WHERE `timestamp` < '$current_date' ORDER BY `timestamp` DESC LIMIT 1)";

for($i = 0; $i > -12; $i--){
    $month = date('Y-m-d', strtotime( $current_date . ' first day of ' . $i . ' month'));
    $sql .= " UNION (SELECT `timestamp`, `verbr_laag`, `verbr_hoog`, `terug_laag`, `terug_hoog` FROM `data` WHERE `timestamp` < '$month 00:00:00' ORDER BY `timestamp` DESC LIMIT 1)";
}

$sql .= ' ORDER BY `timestamp` ASC;';

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