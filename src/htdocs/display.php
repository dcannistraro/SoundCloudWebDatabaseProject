<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
<script src="http://connect.soundcloud.com/sdk/sdk-3.0.0.js"></script>
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<script src="__jquery.tablesorter\jquery.tablesorter.min.js"></script>
<script type="text/javascript">
SC.initialize({
  client_id: '02gUJC0hH2ct1EGOcYXQIzRFU91c72Ea'
});

function getTrack(row){
	var id = $(row).children().first().text();
	SC.get('/tracks/'+id, {}).then(function(track){
		SC.oEmbed(track.permalink_url, { auto_play: true }).then(function(oEmbed) {
		  $("#embed").html(oEmbed.html);
		  $("#myModal").modal("show");
		  $("#myTable").tablesorter(); 
		});
	});
}

$(document).ready(function() 
    { 
        $("#myTable").tablesorter(); 
    } 
); 
</script>
</head>
<body>
<div class="container">
	<h2>Songs sorted by Popularity</h2>
	<table id="myTable"class="table table-striped tablesorter">
		<thead>
			<tr><th>Track ID</th><th>Title</th><th>Creator</th><th>Genre</th><th>Likes</th><th>Plays</th><th>Likes/Plays</th><th>Date Posted</th></tr>
		</thead>
		<tbody>
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

		$sql = 'SELECT Track.ID AS track_id, Track.title AS title, User.Username AS creator, Genre.Name AS genre, Track.Favorite_count AS likes, Track.Play_count AS plays, '
				.'(Track.favorite_count/Track.play_count) AS popularity_ratio, Track.Created_at AS date_posted FROM Track INNER JOIN Genre ON Genre.ID = Track.GenreId '
				.'INNER JOIN User ON User.ID = Track.CreatorId WHERE Track.favorite_count >= 100 AND Track.play_count >= 1000 GROUP BY creator '
				.'ORDER BY popularity_ratio DESC, likes DESC, plays DESC, date_posted DESC, creator, title';
		$result = $conn->query($sql);

		if ($result->num_rows > 0){
			while($row = $result->fetch_assoc()) {
				echo sprintf("<tr style='cursor:pointer'onclick='getTrack($(this))'><td>%d</td><td>%s</td><td>%s</td><td>%s</td><td>%d</td><td>%d</td><td>%.3f</td><td>%s</td></tr>", $row['track_id'], $row['title'], $row['creator'], $row['genre'], $row['likes'], $row['plays'], $row['popularity_ratio'], $row['date_posted']);
			}
		}
		$conn->close();

		?>
		</tbody>
	</table>
	<!-- Modal -->
	  <div class="modal fade" id="myModal" role="dialog">
		<div class="modal-dialog modal-lg">
		
		  <!-- Modal content-->
		  <div class="modal-content">
			<div class="modal-header">
			  <button type="button" class="close" data-dismiss="modal">&times;</button>
			  <h4 class="modal-title">Embedded Song</h4>
			</div>
			<div id="embed"class="modal-body">
			  <p>No song has been selected.</p>
			</div>
			<div class="modal-footer">
			  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		  </div>
		  
		</div>
	  </div>
</div>
<button type="button" class="btn btn-primary"style="position:fixed;bottom:10px;right:10px;"onclick="$('#myModal').modal('show')">Show Embed</button>
<a href="menu.html"><button type="button" class="btn btn-primary"style="position:fixed;bottom:10px;left:10px;">Back to Menu</button></a>
</body>
</html>