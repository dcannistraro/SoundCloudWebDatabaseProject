<!DOCTYPE html>
<html>
<head>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
<script src="http://connect.soundcloud.com/sdk.js"></script>
<script type="text/javascript">
SC.initialize({
  client_id: '02gUJC0hH2ct1EGOcYXQIzRFU91c72Ea'
});

function load(){
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

	$sql = 'SELECT id FROM groups';
	$result = $conn->query($sql);

	if ($result->num_rows > 0){
		while($row = $result->fetch_assoc()) {
			echo sprintf("SC.get('/groups/%d/members', {}, function(users){
						$(users).each(function(index, user){
							$.post('memberof_insert.php', { user_id: user.id, group_id: %d}, function(result) { 
							   console.log(result);
							});
						});
					});", $row['id'], $row['id']);
		}
	}
	$conn->close();

	?>
	
	
	
	
}
</script>
</head>
<body>

<button id="Save"onclick="load();">Load Data</button> 

</body>
</html>