<!DOCTYPE html>
<html>

<head>
	<title>Event Signup</title>
	<link rel="stylesheet" href="style.css">
</head>

<body>
	<script>
		function submitForm(row) {
			document.getElementById("form-event-id").value = row.getAttribute("event-id");
			document.getElementById("hidden-form").submit();
		}
	</script>
	<form id="hidden-form" action="event_signup_2.php" method="post" enctype="multipart/form-data">
		<input id="form-event-id" type="hidden" id="event-id" name="event-id">
	</form>
	<?php
		$servername = "localhost";
		$username = "root";
		$password = "";
		$database = "event_planning";
		// Create connection
		$mysqli = new mysqli($servername, $username, $password, $database);
	?>
	
	<button class="back-button" onclick="location.href='homepage.html'"> &#60 Back </button>
	<h2>Available Events</h2>
	<br><br><br>
	<table class="clickable-table">
		<tr>
			<th>Event</th>
			<th>Venue</th>
			<th>Start time</th>
			<th>End time</th>
			<th>Seats left</th>
		</tr>
		<?php
			$results = $mysqli -> query("SELECT event_id, event_name, venue_name, start_time, end_time, seats_left
										 FROM event JOIN venue USING (venue_id) WHERE seats_left > 0 ORDER BY start_time;");
			while ($row = $results -> fetch_array()) {
				echo "<tr event-id={$row['event_id']} onclick=\"submitForm(this)\" style=\"cursor: pointer;\">";
				echo "<td>{$row['event_name']}</td>";
				echo "<td>{$row['venue_name']}</td>";
				echo "<td>{$row['start_time']}</td>";
				echo "<td>{$row['end_time']}</td>";
				echo "<td>{$row['seats_left']}</td>";
				echo "</tr>";
			}
		?>
	</table>
</body>

</html>
