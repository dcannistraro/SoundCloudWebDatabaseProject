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

function load(){
	var searchTerm = $("#search").val();
	$.post('commentsearch.php', { search: searchTerm }, function(result) { 
		$(document.body).html(result);
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
	<h2>Comment Search</h2>
	<div class="form-group">
	  <label for="search">Search Term:</label>
	  <?php
	  if (isset($_POST['search'])){
		  echo sprintf('<input type="text" class="form-control" id="search"value="%s"><br>', addslashes($_POST['search']));
	  }else{
		  echo '<input type="text" class="form-control" id="search"><br>';
	  }
	  ?>
	</div>
	<button type="button" class="btn btn-primary"onclick="load()">Load Comments</button>
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
		if (isset($_POST['search'])){
			
			echo '<table id="myTable"class="table table-striped tablesorter"><thead>'
				.'<tr><th>Comment ID</th><th>Poster / Track</th><th>Text</th></tr></thead><tbody>';
			
			$sql = sprintf('SELECT text, CONCAT(User.username, " / ", Track.title) AS title, commentid FROM TrackComment '
							.'INNER JOIN Track ON Track.ID = TrackComment.TrackId INNER JOIN User ON User.ID = Track.CreatorId '
							.'WHERE text LIKE "%s"', "%".addslashes($_POST['search'])."%");
			$result = $conn->query($sql);

			if ($result->num_rows > 0){
				while($row = $result->fetch_assoc()) {
					echo sprintf("<tr><td>%d</td><td>%s</td><td>%s</td></tr>", $row['commentid'], $row['title'], $row['text']);
				}
			}
			echo '</tbody></table>';
		}else{
			echo '<p>No data yet</p>';
		}
		$conn->close();

		?>
</div>
<a href="menu.html"><button type="button" class="btn btn-primary"style="position:fixed;bottom:10px;left:10px;">Back to Menu</button></a>
</body>
</html>