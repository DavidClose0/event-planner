<!DOCTYPE html>
<html>

<head>
	<title>Venue Registration</title>
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
		$venue_name = $mysqli -> real_escape_string($_REQUEST['venue-name']);
		$seat_count = $mysqli -> real_escape_string($_REQUEST['seat-count']);		
		try {
			if (empty($venue_name)) {
				throw new Exception("venue name cannot be empty.");
			} 
			if (!is_numeric($seat_count) or $seat_count == 0) {
				throw new Exception("seat count must be a number greater than 0.");
			}
			$mysqli -> query("INSERT INTO venue (venue_name, seat_count) VALUES ('$venue_name', '$seat_count');");
			echo "Successfully registered {$_REQUEST['venue-name']} with $seat_count seats.";
		} catch (Exception $e) {
			$mysqli -> rollback();
			echo "Error: ", $e -> getMessage();
		}
	$mysqli -> commit();
	$mysqli -> close();
	}
	?>
	<br><br>
	<button class="back-button" onclick="location.href='homepage.html'"> &#60 Back to Homepage </button>
</body>

</html>
