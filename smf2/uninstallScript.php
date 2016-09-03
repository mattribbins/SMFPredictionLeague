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
$result = $smcFunc['db_query']('', "DROP TABLE IF EXISTS {$db_prefix}pl_matches");
echo "{$db_prefix}pl_matches dropped<br />";

// smf_pl_predictions Table
$result = $smcFunc['db_query']('', "DROP TABLE IF EXISTS {$db_prefix}pl_predictions");
echo "{$db_prefix}pl_predictions dropped<br />";

// smf_pl_standings Table
$result = $smcFunc['db_query']('', "DROP TABLE IF EXISTS {$db_prefix}pl_standings");
echo "{$db_prefix}pl_standings dropped<br />";

// smf_pl_teams Table
$result = $smcFunc['db_query']('', "DROP TABLE IF EXISTS {$db_prefix}pl_teams");
echo "{$db_prefix}pl_teams dropped<br />";

// smf_pl_users Table
$result = $smcFunc['db_query']('', "DROP TABLE IF EXISTS {$db_prefix}pl_users");
echo "{$db_prefix}pl_users dropped<br />";

// smf_pl_week Table
$result = $smcFunc['db_query']('', "DROP TABLE IF EXISTS {$db_prefix}pl_week");
echo "{$db_prefix}pl_week dropped<br />";

echo '<br /><b>Removing Updates to SMF Tables</b><br />';

// smf_shoutbox
$result = $smcFunc['db_query']('', "DROP TABLE IF EXISTS {$db_prefix}pl_shoutbox");
echo "{$db_prefix}pl_shoutbox dropped<br />";
*/

// smf_members
// DONT REMOVE THIS COLUMN, AS IF THERE IS AN UPGRADE GOING ON IT WILL MEAN USERS WILL HAVE TO ASSIGN THEMSELVES AGAIN
//$result = $smcFunc['db_query']('', "ALTER TABLE {$db_prefix}members DROP `pl_enabled`");
//echo "Dropped pl_enabled column from {$db_prefix}members<br />";

// smf_settings
// DONT REMOVE THESE, AS IF THERE IS AN UPGRADE THE SETTINGS WILL BE LOST
//$result = $smcFunc['db_query']('', "DELETE FROM {$db_prefix}settings WHERE variable LIKE 'SMFPredictionLeague%'");
//echo "Removed SMFPredictionLeague settings from {$db_prefix}settings<br />";

//Done
echo '<br/><font color="green"><b>Uninstall script complete</b></font>';
echo '<br/>Any issues please go to <a href="http://www.smfmodding.com/">http://www.smfmodding.com/</a>';

?>
