<?php
?>
<script>
	
	FB.api(
		'/me',
		'GET',
		{"fields":"email,address,first_name,last_name"},
		function(response) {
				// Insert your code here
		}
	);
</script>