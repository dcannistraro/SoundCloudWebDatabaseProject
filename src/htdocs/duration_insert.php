<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "soundcloud";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$sql = sprintf('INSERT INTO playlistduration VALUES (%d, %d)', $_POST['id'], $_POST['duration']);

if ($conn->query($sql) === TRUE) {
    echo "New duration created successfully";
} else {
   // echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();

?>