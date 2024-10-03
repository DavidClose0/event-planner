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
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$event_name = $mysqli -> real_escape_string($_REQUEST['event-name']);
		@$venue_id = $mysqli -> real_escape_string($_REQUEST['venue-id']);
		$start_time = strtotime($_REQUEST['start-time']);	
		$end_time = strtotime($_REQUEST['end-time']);
		$mysqli -> begin_transaction();
		try {
			if (empty($event_name)) {
				throw new Exception("event name cannot be empty.");
			}
			if (empty($venue_id)) {
				throw new Exception("you must select a venue.");
			}
			if (empty($start_time)) {
				throw new Exception("you must select a start time.");
			}
			if (empty($end_time)) {
				throw new Exception("you must select an end time.");
			}
			if ($end_time <= $start_time) {
				throw new Exception("event must end after it starts.");
			}
			$start_time = $mysqli -> real_escape_string(date("Y-m-d H:i:s", $start_time));
			$end_time = $mysqli -> real_escape_string(date("Y-m-d H:i:s", $end_time));	
			$results = $mysqli -> query("SELECT event_name, start_time, end_time
										 FROM event 
										 WHERE venue_id = $venue_id AND
										 ((start_time >= '$start_time' AND start_time < '$end_time') OR
										 (end_time > '$start_time' AND end_time <= '$end_time'));");
			if ($row = $results -> fetch_array()) {
				throw new Exception("conflict with {$row['event_name']} happening from {$row['start_time']} to {$row['end_time']}.");
			}
			$results = $mysqli -> query("SELECT venue_name, seat_count FROM venue WHERE venue_id = $venue_id;");
			$row = $results -> fetch_array();
			$venue_name = $row['venue_name'];
			$seats_left = $row['seat_count'];
			$sql = ("INSERT INTO event (event_name, venue_id, start_time, end_time, seats_left) VALUES
					 ('$event_name', '$venue_id', '$start_time', '$end_time', '$seats_left');");
			$mysqli -> query($sql);
			echo "Successfully created {$_REQUEST['event-name']} at $venue_name from $start_time to $end_time.";	
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
