<?php
// dev database configuration settings
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "newrich"; 

// dev db connection
$conn = new mysqli($servername, $username, $password, $dbname);

// check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// set character to UTF-8
$conn->set_charset("utf8");

?>
