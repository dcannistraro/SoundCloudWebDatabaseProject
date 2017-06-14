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

$sql = sprintf('INSERT INTO trackcomment VALUES (%d, %d, "%s")', $_POST['track_id'], $_POST['id'], addslashes($_POST['text']));
if ($conn->query($sql) === TRUE) {
    echo "New comment created successfully";
} else {
    //echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();

?>