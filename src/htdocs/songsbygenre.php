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

function getGenre(row){
	var genre = $(row).children().first().text();
	$(".songrow").prop("hidden", true);
	$("[genre='"+genre+"']").prop("hidden", false);
	$("#myModal").modal("show");
}

function getTrack(row){
	var id = $(row).children().first().text();
	SC.get('/tracks/'+id, {}).then(function(track){
		SC.oEmbed(track.permalink_url, { auto_play: true }).then(function(oEmbed) {
		  $("#embed").html(oEmbed.html);
		});
	});
}



$(document).ready(function() 
    { 
        $("#myTable").tablesorter();
		$("#songTable").tablesorter(); 
    } 
); 
</script>
</head>
<body>
<div class="container">
	<h2>Genre Statistics</h2>
	<table id="myTable"class="table table-striped tablesorter">
		<thead>
			<tr><th>Genre ID</th><th>Genre</th><th>Number of Tracks</th><th>Total Duration of all Tracks (seconds)</th></tr>
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

		$sql = 'SELECT Genre.ID AS genre_id, Genre.Name AS genre, COUNT(*) AS num_of_tracks, (ROUND(SUM(duration.duration)/1000)) AS total_duration FROM Track '
				.'INNER JOIN Genre ON Genre.ID = Track.GenreId '
				.'INNER JOIN duration ON duration.trackid = track.id '
				.'GROUP BY genre '
				.'HAVING num_of_tracks >= 20 '
				.'ORDER BY num_of_tracks DESC, genre, duration DESC';
		$result = $conn->query($sql);

		if ($result->num_rows > 0){
			while($row = $result->fetch_assoc()) {
				if ($row['genre'] == ""){
					$row['genre'] = "No Genre";
				}
				echo sprintf("<tr style='cursor:pointer'onclick='getGenre($(this))'><td>%d</td><td>%s</td><td>%d</td><td>%d</td></tr>", $row['genre_id'], $row['genre'], $row['num_of_tracks'], $row['total_duration']);
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
			<div class="modal-body">
			  <div id="embed">
				<p>No song has been selected.</p>
			  </div>
			  <table id="songTable"class="table table-striped tablesorter">
				<thead>
					<tr><th>Track ID</th><th>Title</th><th>Creator</th><th>Likes</th><th>Plays</th><th>Date Posted</th></tr>
				</thead>
				<tbody id="songs">
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
            
				$sql = 'SELECT Track.ID AS track_id, Track.title AS title, User.Username AS creator, Genre.id AS genre, Track.Favorite_count AS likes, Track.Play_count AS plays, '
						.'Track.Created_at AS date_posted FROM Track INNER JOIN Genre ON Genre.ID = Track.GenreId '
						.'INNER JOIN User ON User.ID = Track.CreatorId WHERE Track.GenreId IN (SELECT Genre.ID FROM Track INNER JOIN Genre ON Genre.ID = Track.GenreId '
						.'GROUP BY genre.id HAVING COUNT(track.id) > 20) '
						.'ORDER BY likes DESC, plays DESC, date_posted DESC, creator, title';
				$result = $conn->query($sql);
            
				if ($result->num_rows > 0){
					while($row = $result->fetch_assoc()) {
						echo sprintf("<tr class='songrow'genre='%d'style='cursor:pointer'onclick='getTrack($(this))'hidden><td>%d</td><td>%s</td><td>%s</td><td>%d</td><td>%d</td><td>%s</td></tr>", $row['genre'], $row['track_id'], $row['title'], $row['creator'], $row['likes'], $row['plays'], $row['date_posted']);
					}
				}
				$conn->close();
            
				?>
				</tbody>
			 </table>
			</div>
			<div class="modal-footer">
			  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		  </div>
		  
		</div>
	  </div>

</div>
<button type="button" class="btn btn-primary"style="position:fixed;bottom:10px;right:10px;"onclick="$('#myModal').modal('show')">Show Songs</button>
<a href="menu.html"><button type="button" class="btn btn-primary"style="position:fixed;bottom:10px;left:10px;">Back to Menu</button></a>
</body>
</html>