<?php
define('SMF', 1);
require_once('../Settings.php');
require_once($sourcedir . '/Load.php');

// Create a variable to store some SMF specific functions in.
$smcFunc = array();

// Initate the database connection and define some database functions to use.
loadDatabase();

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

	$shout_result = $smcFunc['db_query']('', $shout_sql);
} else {
	$shout_result = 'empty';
}
?>