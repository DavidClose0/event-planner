<!DOCTYPE html>
<html>

<head>
	<title></title>
	<link rel="stylesheet" href="style.css">
</head>

<body>
	<script>
		function submitForm(row) {
			document.getElementById("form-group-id").value = row.getAttribute("group-id");
			document.getElementById("hidden-form").submit();
		}
	</script>
	<form id="hidden-form" action="group_view.php" method="post" enctype="multipart/form-data">
		<input id="form-group-id" type="hidden" id="group-id" name="group-id">
	</form>
	<?php
		$servername = "localhost";
		$username = "root";
		$password = "";
		$database = "event_planning";
		// Create connection
		$mysqli = new mysqli($servername, $username, $password, $database);
		
		$registered = FALSE;
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			$name = $mysqli -> real_escape_string($_REQUEST['name']);
			if (empty($name)) {
				echo "Error: name cannot be blank.<br><br>";
				echo "<button class=\"back-button\" onclick=\"location.href='homepage.html'\"> &#60 Back to Homepage </button>";
			} else {
				echo "<button class=\"back-button\" onclick=\"location.href='registered_events.html'\"> &#60 Back </button><br><br>";
				$results = $mysqli -> query("SELECT group_id, event_name, venue_name, start_time, end_time, seat_number
											 FROM group_table JOIN group_member USING (group_id)
											 JOIN event USING (event_id) JOIN venue USING (venue_id)
											 JOIN visit USING (visit_id) JOIN visitor USING (visitor_id)
											 WHERE visitor_name = '$name';");
				if ($results -> num_rows != 0) {
					$registered = TRUE;
					echo "<h2>Group Registrations</h2><br><br><br>";
					echo "<table class=\"clickable-table\">";
					echo "<tr>";
					echo "<th>Group</th>";
					echo "<th>Event</th>";
					echo "<th>Venue</th>";
					echo "<th>Start time</th>";
					echo "<th>End time</th>";
					echo "<th>Seat</th>";
					echo "</tr>";
					while ($row = $results -> fetch_array()) {
						echo "<tr group-id={$row['group_id']} onclick=\"submitForm(this)\" style=\"cursor: pointer;\">";
						echo "<td>{$row['group_id']}</td>";
						echo "<td>{$row['event_name']}</td>";
						echo "<td>{$row['venue_name']}</td>";
						echo "<td>{$row['start_time']}</td>";
						echo "<td>{$row['end_time']}</td>";
						echo "<td>{$row['seat_number']}</td>";
						echo "</tr>";
					}
					echo "</table>";
				}
				$results = $mysqli -> query("SELECT event_name, venue_name, start_time, end_time, seat_number
											 FROM event JOIN venue USING (venue_id)JOIN visit USING (event_id) 
											 JOIN visitor USING (visitor_id)
											 WHERE visitor_name = '$name' AND visit_id NOT IN (
												SELECT visit_id FROM group_member);");
				if ($results -> num_rows != 0) {
					$registered = TRUE;
					echo "<h2>Individual Registrations</h2><br><br><br>";
					echo "<table>";
					echo "<tr>";
					echo "<th>Event</th>";
					echo "<th>Venue</th>";
					echo "<th>Start time</th>";
					echo "<th>End time</th>";
					echo "<th>Seat</th>";
					echo "</tr>";
					while ($row = $results -> fetch_array()) {
						echo "<td>{$row['event_name']}</td>";
						echo "<td>{$row['venue_name']}</td>";
						echo "<td>{$row['start_time']}</td>";
						echo "<td>{$row['end_time']}</td>";
						echo "<td>{$row['seat_number']}</td>";
						echo "</tr>";
					}
					echo "</table>";				
				}
				if ($registered === FALSE) {
					echo "$name has not registered for any events.";
				}
			}
		}
	?>
</body>

</html>