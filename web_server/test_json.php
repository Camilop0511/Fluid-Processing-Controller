<!DOCTYPE html>
<html>
<head>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script>
		$(document).ready(function() {
			setInterval(function() {
				// make an AJAX request to fetch the values from test_json2.php
				$.getJSON('test_json2.php', function(data) {
					// update the HTML content with the real_volume value
					$("#real-volume").html(data.real_volume);
				}).fail(function(jqXHR, textStatus, errorThrown) {
					// handle errors
					console.log("Error: " + textStatus + " " + errorThrown);
					$("#real-volume").html("N/A");
				});
			}, 20000); // fetch the data every 20 seconds
		});
	</script>
</head>
<body>
	<p>The real volume is: <span id="real-volume"></span></p>
</body>
</html>
