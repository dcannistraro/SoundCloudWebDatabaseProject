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

	$sql = 'SELECT DISTINCT trackid FROM playlisttrack';
	$result = $conn->query($sql);

	if ($result->num_rows > 0){
		while($row = $result->fetch_assoc()) {
			echo sprintf("SC.get('/tracks/%d', {}, function(tracks){
						$(tracks).each(function(index, track){
							var created_at = track.created_at.substr(0, 10).replace('/', '-').replace('/', '-');
							$.post('script.php', { id: track.id, title: track.title, created_at: created_at, favorite_count: track.likes_count, play_count: track.playback_count, creatorid: track.user_id, genre: track.genre }, function(result) { 
							   console.log(result);
							});
						});
					});", $row['trackid']);
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