<?php

function Connect()
{
    $dbhost = "localhost";
    $dbuser = "root";
    $dbpass = "";
    $dbname = "bakery";

    // Create Connection
    $conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

    // Check Connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}