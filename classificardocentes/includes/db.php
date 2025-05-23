<?php
$servername = "localhost";
$username = "Leo Misseque";
$password = "DornaLeo";
$dbname = "classificardocentes";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>
