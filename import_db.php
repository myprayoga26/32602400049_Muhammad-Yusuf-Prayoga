<?php
$mysqli = new mysqli("127.0.0.1", "root", "", "");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Read the SQL file
$sql = file_get_contents('database.sql');

// Execute multi query
if ($mysqli->multi_query($sql)) {
    do {
        if ($result = $mysqli->store_result()) {
            $result->free();
        }
    } while ($mysqli->more_results() && $mysqli->next_result());
    echo "Database imported successfully.\n";
} else {
    echo "Error importing database: " . $mysqli->error;
}

$mysqli->close();
