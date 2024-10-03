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
		
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			$event_id = $_REQUEST['event-id'];
			$sql = ("SELECT event_name, seats_left FROM event WHERE event_id = $event_id;");
			$results = $mysqli -> query($sql);
		}
		$row = $results -> fetch_array();
		$event_name = $row['event_name'];
		$seats_left = $row['seats_left'];
		echo "<h2>Signing up for $event_name</h2>";
	?>
	<script>
		var groupSize = 1;
		
		function addMember() {
			groupSize++;
			var groupSizeInput = document.getElementById('group-size');
			groupSizeInput.value = groupSize;
			
			var newMemberLabel = document.createElement('label');
			newMemberLabel.textContent = 'Name ';
			
			var newMemberInput = document.createElement('input');
			newMemberInput.type = 'text';
			newMemberInput.name = 'visitor-names[]';
			
			var removeButton = document.createElement('button');
			removeButton.type = 'button';
			removeButton.textContent = 'Remove';
			removeButton.onclick = function() {
				removeMember(newMemberInput, removeButton, container);
			}
			
			var lineBreak = document.createElement('br');
			var container = document.getElementById('group-members');
			
			container.appendChild(newMemberLabel);
			container.appendChild(newMemberInput);
			container.appendChild(removeButton);
			container.appendChild(lineBreak);
			
			if (groupSize >= 10 || groupSize >= <?php echo $seats_left ?>) {
				document.getElementById('add-member-button').style.display = 'none';
			}
		}
		
		function removeMember(memberInput, removeButton, container) {
			container.removeChild(memberInput.previousSibling);
			container.removeChild(memberInput);
			container.removeChild(removeButton.nextSibling);
			container.removeChild(removeButton);
			
			groupSize--;
			var groupSizeInput = document.getElementById('group-size');
			groupSizeInput.value = groupSize;
			
			document.getElementById('add-member-button').style.display = 'block';
		}
	</script>
	
	<button class="back-button" onclick="location.href='event_signup.php'"> &#60 Back </button><br><br><br><br><br><br> 
	<form action="event_signup_3.php" method="post" enctype="multipart/form-data">
		<input type="hidden" id="event-id" name="event-id" value="<?php echo $event_id ?>">
		<input type="hidden" id="group-size" name="group-size" value="1">
		<div id="group-members">
			<label for="visitor-name">Name</label>
			<input type="text" id="visitor-name" name="visitor-names[]"><br>
		</div>
		<button id="add-member-button" type="button" onclick="addMember()">Add group member</button><br><br>
		<input type="submit" value="Submit"><br><br><br>
	</form>
</body>

</html>
