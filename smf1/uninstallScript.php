<?php
// SMFPredictionLeague Install Script
global $db_prefix;

if (file_exists(dirname(__FILE__) . '/SSI.php') && !defined('SMF'))
	require_once(dirname(__FILE__) . '/SSI.php');
// Hmm... no SSI.php and no SMF?
elseif (!defined('SMF'))
	die('<b>Error:</b> Cannot uninstall - please verify you put this in the same place as SMF\'s index.php.');

echo '<b><u>Uninstaling SMFPredictionLeague</u></b><br />';
echo '<i>Progress of the install is shown below</i><br />';

/* TODO: Include some logic here, we want to ask user whether we can drop existing tables if they exist  
echo '<br /><b>Removing Tables</b><br />';

// smf_pl_matches Table
db_query("DROP TABLE IF EXISTS {$db_prefix}pl_matches", __FILE__, __LINE__);
echo "{$db_prefix}pl_matches dropped<br />";

// smf_pl_predictions Table
db_query("DROP TABLE IF EXISTS {$db_prefix}pl_predictions", __FILE__, __LINE__);
echo "{$db_prefix}pl_predictions dropped<br />";

// smf_pl_standings Table
db_query("DROP TABLE IF EXISTS {$db_prefix}pl_standings", __FILE__, __LINE__);
echo "{$db_prefix}pl_standings dropped<br />";

// smf_pl_teams Table
db_query("DROP TABLE IF EXISTS {$db_prefix}pl_teams", __FILE__, __LINE__);
echo "{$db_prefix}pl_teams dropped<br />";

// smf_pl_users Table
db_query("DROP TABLE IF EXISTS {$db_prefix}pl_users", __FILE__, __LINE__);
echo "{$db_prefix}pl_users dropped<br />";

// smf_pl_week Table
db_query("DROP TABLE IF EXISTS {$db_prefix}pl_week", __FILE__, __LINE__);
echo "{$db_prefix}pl_week dropped<br />";

// smf_pl_shoutbox Table
db_query("DROP TABLE IF EXISTS {$db_prefix}pl_shoutbox", __FILE__, __LINE__);
echo "{$db_prefix}pl_shoutbox dropped<br />";

echo '<br /><b>Removing Updates to SMF Tables</b><br />';

*/

// smf_members
// DONT REMOVE THIS COLUMN, AS IF THERE IS AN UPGRADE GOING ON IT WILL MEAN USERS WILL HAVE TO ASSIGN THEMSELVES AGAIN
//db_query("ALTER TABLE {$db_prefix}members DROP `pl_enabled`", __FILE__, __LINE__);
//echo "Dropped pl_enabled column from {$db_prefix}members<br />";


// smf_settings
// DONT REMOVE THESE, AS IF THERE IS AN UPGRADE THE SETTINGS WILL BE LOST
//db_query("DELETE FROM {$db_prefix}settings WHERE variable LIKE 'SMFPredictionLeague%'", __FILE__, __LINE__);
//echo "Removed SMFPredictionLeague settings from {$db_prefix}settings<br />";

//Done
echo '<br/><font color="green"><b>Uninstall script complete</b></font>';
echo '<br/>Any issues please go to <a href="http://www.smfmodding.com/">http://www.smfmodding.com/</a>';

?>
