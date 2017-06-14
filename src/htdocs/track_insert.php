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

$genreid = 0;
$sql = sprintf('SELECT id FROM genre WHERE name = "%s"', addslashes($_POST['genre']));
$result = $conn->query($sql);

if ($result->num_rows > 0){
	$genreid = $result->fetch_assoc()['id'];
}else{
	$sql = sprintf('INSERT INTO genre (name) VALUES ("%s")', addslashes($_POST['genre']));
	if ($conn->query($sql) === TRUE) {
		$genreid = $conn->insert_id;
	}
	echo sprintf("New genre added: %s<br>", $_POST['genre']);
}
$sql = sprintf('INSERT INTO track VALUES (%d, "%s", "%s", %d, %d, %d, %d)', $_POST['id'], addslashes($_POST['title']), $_POST['created_at'], $_POST['favorite_count'], $_POST['play_count'], $genreid, $_POST['creatorid']);

if ($conn->query($sql) === TRUE) {
    echo sprintf("Successfully added track %d: %s - %s<br>", $_POST['id'], $_POST['title'], $_POST['creator']);
} else {
    #echo sprintf("Error: duplicate track %d was not entered<br>", $_POST['id']);
}
$sql = sprintf('INSERT INTO duration VALUES (%d, %d)', $_POST['id'], $_POST['duration']);
$result = $conn->query($sql);

$conn->close();

?>