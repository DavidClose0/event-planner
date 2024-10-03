<!DOCTYPE html>
<html>
<head>
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
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$group_id = $mysqli -> real_escape_string($_REQUEST['group-id']);
		$results = $mysqli -> query ("SELECT event_name, start_time, end_time 
									  FROM group_member JOIN visit USING(visit_id) 
									  JOIN event USING(event_id)
									  WHERE group_id = $group_id LIMIT 1;");
		$row = $results -> fetch_array();
		$event_name = $row['event_name'];
		$start_time = $row['start_time'];
		$end_time = $row['end_time'];
		echo "Group $group_id for $event_name from $start_time to $end_time:";
					$results = $mysqli -> query("SELECT visitor_name, seat_number 
												 FROM visitor JOIN visit USING (visitor_id) 
												 JOIN group_member USING (visit_id) 
												 WHERE group_id = $group_id;");
		echo "<br>";
		while ($row = $results -> fetch_array()) {
			echo "<br>{$row['visitor_name']} has seat #{$row['seat_number']}.";
		}
	$mysqli -> close();
	}
	?>
	<br><br>
	<button class="back-button" onclick="location.href='homepage.html'"> &#60 Back to Homepage </button>
</body>
</html>
