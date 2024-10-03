<!DOCTYPE html>
<html>

<head>
	<title>Event Signup</title>
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
		$group_size = $_REQUEST['group-size'];
		$event_id = $mysqli -> real_escape_string($_REQUEST['event-id']);
		$visitor_names = $_REQUEST['visitor-names'];
		$results = $mysqli -> query("SELECT event_name, seats_left, start_time, end_time
									 FROM event WHERE event_id = $event_id;");
		$row = $results -> fetch_array();
		$event_name = $row['event_name'];
		$seats_left = $row['seats_left'];
		$start_time = $row['start_time'];
		$end_time = $row['end_time'];
		$mysqli -> begin_transaction();
		try {
			if ($group_size > $seats_left) {
				throw new Exception("not enough seats left.");
			} 
			$mysqli -> query("UPDATE event SET seats_left = seats_left - $group_size WHERE event_id = $event_id;");
			$results = $mysqli -> query("SELECT seat_count FROM event JOIN venue USING (venue_id) WHERE event_id = $event_id;");
			$seat_count = ($results -> fetch_array())['seat_count'];
			if ($group_size > 1) {
				$mysqli -> query("INSERT INTO group_table (event_id, group_size) VALUES ('$event_id', '$group_size');");
				$group_id = $mysqli -> insert_id;
			}
			for ($i = 0; $i < count($visitor_names); $i++) {
				$visitor_name = $mysqli -> real_escape_string($visitor_names[$i]);
				if (empty($visitor_name)) {
					throw new Exception("name cannot be empty.");
				}
				$seat_number = $seat_count - $seats_left + $i + 1;
				$sql = "INSERT IGNORE INTO visitor (visitor_name) VALUES ('$visitor_name');";
				$mysqli -> query($sql);
				$results = $mysqli -> query("SELECT visitor_id FROM visitor WHERE visitor_name = '$visitor_name';");
				$visitor_id = ($results -> fetch_array())['visitor_id'];
				$results = $mysqli -> query("SELECT event_name, start_time, end_time 
											 FROM visit JOIN event USING (event_id)
											 WHERE visitor_id = $visitor_id AND 
											 ((start_time >= '$start_time' AND start_time < '$end_time') OR 
											 (end_time > '$start_time' AND end_time <= '$end_time'));");
				if ($row = $results -> fetch_array()) {
					throw new Exception("{$visitor_names[$i]} has a conflict with {$row['event_name']} happening from {$row['start_time']} to {$row['end_time']}.");
				}
				$sql = "INSERT INTO visit (visitor_id, seat_number, event_id) 
						VALUES ('$visitor_id', '$seat_number', '$event_id');";
				$mysqli -> query($sql);
				if ($group_size > 1) {
					$visit_id = $mysqli -> insert_id;
					$mysqli -> query("INSERT INTO group_member (group_id, visit_id)
									  VALUES ('$group_id', '$visit_id');");
				}
			}
			if ($group_size == 1) {
				echo "Successfully registered $visitor_name for $event_name at seat #$seat_number.";
			} else {
				echo "Successfully registered Group $group_id for $event_name:";
				$results = $mysqli -> query("SELECT visitor_name, seat_number 
											 FROM visitor JOIN visit USING (visitor_id) 
											 JOIN group_member USING (visit_id) 
											 WHERE group_id = $group_id;");
				echo "<br>";
				while ($row = $results -> fetch_array()) {
					echo "<br>{$row['visitor_name']} has seat #{$row['seat_number']}.";
				}
			}
		} catch (Exception $e) {
			$mysqli -> rollback();
			echo "Error: ", $e -> getMessage();
		}
	}
	$mysqli -> commit();
	$mysqli -> close();
	?>
	<br><br>
	<button class="back-button" onclick="location.href='homepage.html'"> &#60 Back to Homepage </button>
</body>

</html>
