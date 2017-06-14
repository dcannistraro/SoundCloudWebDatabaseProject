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

$sql = sprintf('INSERT INTO groups VALUES (%d, "%s", "%s", "%s", %d)', $_POST['id'], addslashes($_POST['name']), addslashes($_POST['desc']), addslashes($_POST['created_at']), $_POST['creatorid']);
if ($conn->query($sql) === TRUE) {
    echo "New group created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();

?>