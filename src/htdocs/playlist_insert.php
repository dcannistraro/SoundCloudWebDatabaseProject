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

$sql = sprintf('INSERT INTO playlist VALUES (%d, "%s", "%s", "%s", %d)', $_POST['id'], addslashes($_POST['title']), addslashes($_POST['desc']), $_POST['created_at'], $_POST['creatorid']);

if ($conn->query($sql) === TRUE) {
    echo sprintf("Successfully added playlist %d: %s - %s<br>", $_POST['id'], $_POST['title'], $_POST['creator']);
} else {
   // echo "Error: " . $sql . "<br>" . $conn->error;
}
$sql = sprintf('INSERT INTO playlistduration VALUES (%d, %d)', $_POST['id'], $_POST['duration']);
$result = $conn->query($sql);

$conn->close();

?>