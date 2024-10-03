<!DOCTYPE html>
<html>

<head>
	<title>Event Creation</title>
	<link rel="stylesheet" href="style.css">
</head>

<body>
	<?php
		$servername = "localhost";
		$username = "root";
		$password = "";
		$database = "event_planning";
		// Create connection
		$mysqli = new mysqli($servername, $username, $password, $database);
		// Check connection
		if ($mysqli->connect_error) {
			die("Connection failed: " . $mysqli->connect_error);
		}
	?>
	<button class="back-button" onclick="location.href='homepage.html'"> &#60 Back </button>
	<h2>Event Creation</h2><br><br><br><br><br><br>
	<div>
		<form action="event_creation_2.php" method="post" enctype="multipart/form-data">
			<label for="event-name">Event name</label>
			<input type="text" id="event-name" name="event-name"><br><br><br><br>
			<label>Venue</label><br>
			<?php
				$results =  $mysqli -> query("SELECT venue_id, venue_name, seat_count FROM venue;");
				while ($row = $results -> fetch_array()) {
					echo "<input type=\"radio\" id=\"{$row['venue_name']}\" name=\"venue-id\" value=\"{$row['venue_id']}\">";
					echo "<label for=\"{$row['venue_name']}\">{$row['venue_name']} ({$row['seat_count']} seats)</label><br>";
				}
			?>
			<br><br><br>
			<label for="start-time">Start time</label>
			<input type="datetime-local" id="start-time" name="start-time"><br>
			<label for="start-time">End time</label>
			<input type="datetime-local" id="end-time" name="end-time"><br><br>
			<input type="submit" value="Submit">
		</form>
	</div>
</body>

</html>
