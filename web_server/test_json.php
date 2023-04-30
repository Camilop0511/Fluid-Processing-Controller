<!DOCTYPE html>
<html>
<head>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script>
		$(document).ready(function() {
			setInterval(function() {
				// make an AJAX request to fetch the values from serial_read.php
				$.getJSON('test_json2.php', function(data) {
					// update the HTML content with the real_volume value
					$("#real-volume").html(data.real_volume);
				});
			}, 20000); // fetch the data every 5 seconds
		});
	</script>
</head>
<body>
	<p>The real volume is: <span id="real-volume"></span></p>
</body>
</html>
