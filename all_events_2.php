<!DOCTYPE html>
<html>

<head>
	<title>All Events</title>
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
		
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			$event_id = $_REQUEST['event-id'];
			$sql = ("SELECT event_name FROM event WHERE event_id = $event_id;");
			$results = $mysqli -> query($sql);
			$row = $results -> fetch_array();
			$event_name = $row['event_name'];
		}
	?>
	
	<button class="back-button" onclick="location.href='all_events.php'"> &#60 Back </button>
	<?php
		$results = $mysqli -> query("SELECT visitor_name, seat_number, group_id
									 FROM visitor JOIN visit USING(visitor_id) JOIN event USING(event_id) 
									 LEFT JOIN group_member USING(visit_id)
									 WHERE event_id = $event_id;");
		if ($results -> num_rows != 0) {
			echo "<h2>$event_name</h2><br><br><br>";
			echo "<table>";
			echo "<tr>";
			echo "<th>Visitor</th>";
			echo "<th>Seat</th>";
			echo "<th>Group</th>";
			echo "</tr>";
			while ($row = $results -> fetch_array()) {
				echo "<tr>";
				echo "<td>{$row['visitor_name']}</td>";
				echo "<td>{$row['seat_number']}</td>";
				echo "<td>{$row['group_id']}</td>";
				echo "</tr>";
			}
		} else {
			echo "<br><br>No one has registered for $event_name.";
		}
	?>
</body>

</html>
