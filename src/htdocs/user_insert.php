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

$sql = sprintf('INSERT INTO user VALUES (%d, "%s", "%s", "%s", "%s")', $_POST['id'], addslashes($_POST['username']), addslashes($_POST['desc']), addslashes($_POST['city']), addslashes($_POST['country']));
if ($conn->query($sql) === TRUE) {
    echo sprintf("Successfully added user %d: %s<br>", $_POST['id'], $_POST['username']);
} else {
    //echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();

?>