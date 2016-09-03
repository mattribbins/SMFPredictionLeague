<?php
@include_once('../SSI.php');
global $db_prefix;

// Get shout post values
if (!empty($_POST['name'])) {
	$shouter=addslashes(strip_tags(htmlspecialchars($_POST['name'], ENT_QUOTES))); // Cleans Input.
}
if (!empty($_POST['message'])) {
	$shout=addslashes(strip_tags(htmlspecialchars($_POST['message'], ENT_QUOTES))); // Cleans Input.
}

// Set the timestamp for this shout
$timestamp = time();

// Only insert if we have data
if (!empty($shouter) && !empty($shout)) {
	
	// Query to insert the shout
	$shout_sql = "
		INSERT INTO	{$db_prefix}pl_shoutbox 
					(
						name, 
						date, 
						content
					)
		VALUES 		(
						'$shouter', 
						'$timestamp', 
						'$shout')
	";

	$shout_result = db_query($shout_sql, __FILE__, __LINE__);
} else {
	$shout_result = 'empty';
}
?>