<?php
@include_once('../SSI.php');
global $db_prefix;

// Setup headers
header("Expires: Sat, 05 Nov 2005 00:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Query to return shoutbox data
$shout_query = "
	SELECT 		name,
				date,
				content
	FROM 		{$db_prefix}pl_shoutbox 
	ORDER BY 	`date` DESC
	LIMIT		0, 50
";
$shout_result = db_query($shout_query, __FILE__, __LINE__);

echo '<table border="0" width="100%">';

// Only do this if we have some data
if (mysql_num_rows($shout_result)) {
	
	// Loop through the data and output shouts
	while ($shout_row = mysql_fetch_assoc($shout_result)){

		$shouter_name = $shout_row['name'];
		$shout_content = $shout_row['content'];
		$shout_content = stripslashes($shout_content);
		$shout_date = $shout_row['date'];
		$contact_count = 0;

		echo '
			<tr class="smalltext">
				<td valign="top" align="left"><nobr><i>' , date('M d H:i:s',$shout_date) , '</i></nobr></td>
				<td valign="top" align="left"><nobr><b>' , $shouter_name , '</b></nobr></td>
				<td width="100%" valign="top" align="left">' , $shout_content , '</td>
			</tr>
		';
	}
} else {

	// Otherwise there is no data, so just output this
	// TODO - Language specific
	echo '<tr class="smalltext"><td>No shouts</td></tr>';
}

mysql_free_result($shout_result);
echo '</table>';
?>