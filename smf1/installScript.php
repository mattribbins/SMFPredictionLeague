<?php
// SMFPredictionLeague Install Script

if (file_exists(dirname(__FILE__) . '/SSI.php') && !defined('SMF'))
	require_once(dirname(__FILE__) . '/SSI.php');
// Hmm... no SSI.php and no SMF?
elseif (!defined('SMF'))
	die('<b>Error:</b> Cannot install - please verify you put this in the same place as SMF\'s index.php.');

$version = '1.0.2';
$timestamp = time();

echo '<b><u>Instaling/Updating SMFPredictionLeague</u></b><br />';
echo '<i>Progress of the install is shown below</i><br />';

$result = db_query("SHOW TABLES like '%pl_week%'", __FILE__, __LINE__);
$rows = mysql_num_rows($result);
mysql_free_result($result);

if ($rows == 0) {
	$plWeekExists = false;
} else {
	$plWeekExists = true;
}

$result = db_query("SHOW TABLES like '%pl_teams%'", __FILE__, __LINE__);
$rows = mysql_num_rows($result);
mysql_free_result($result);
if ($rows == 0) {
	$plTeamsExists = false;
} else {
	$plTeamsExists = true;
}


echo '<br /><b>Adding Tables</b><br />';

// smf_pl_matches Table
db_query("
	CREATE TABLE IF NOT EXISTS `{$db_prefix}pl_matches` (
		`MatchId` int(10) unsigned NOT NULL auto_increment,
		`HomeTeamId` int(10) unsigned NOT NULL,
		`AwayTeamId` int(10) unsigned NOT NULL,
		`MatchDate` int(10) unsigned NOT NULL,
		`UpdatedDate` int(10) unsigned NOT NULL,
		`HomeScore` smallint(5) unsigned default NULL,
		`AwayScore` smallint(5) unsigned default NULL,
		`WeekId` int(10) unsigned NOT NULL,
		`Updated` tinyint(1) NOT NULL default '0',
		PRIMARY KEY  (`MatchId`)
	)", __FILE__, __LINE__);
echo "{$db_prefix}pl_matches created<br />";

// smf_pl_predictions Table
db_query("
	CREATE TABLE IF NOT EXISTS `{$db_prefix}pl_predictions` (
		`PredictionId` int(10) unsigned NOT NULL auto_increment,
		`MatchId` int(10) unsigned NOT NULL,
		`UserId` int(10) unsigned NOT NULL,
		`HomeScore` smallint(5) unsigned NOT NULL,
		`AwayScore` smallint(5) unsigned NOT NULL,
		`UpdatedDate` int(10) unsigned NOT NULL,
		`Points` smallint(5) unsigned NOT NULL default '0',
		`Bonus` smallint(5) unsigned NOT NULL default '0',
		`WeekId` int(10) unsigned NOT NULL,
		`PointType` char(1) NOT NULL default 'L',
		`GoalsFor` smallint(5) unsigned NOT NULL default '0',
		`GoalsAgainst` smallint(5) unsigned NOT NULL default '0',
		PRIMARY KEY  (`PredictionId`)
	)", __FILE__, __LINE__);
echo "{$db_prefix}pl_predictions created<br />";

// smf_pl_standings
db_query("
	CREATE TABLE IF NOT EXISTS `{$db_prefix}pl_standings` (
		`StandingId` int(10) unsigned NOT NULL auto_increment,
		`UserId` int(10) unsigned NOT NULL,
		`WeekId` int(10) unsigned NOT NULL,
		`Position` smallint(5) unsigned NOT NULL,
		`PreviousPosition` smallint(5) unsigned NOT NULL,
		`Played` int(10) unsigned NOT NULL,
		`Won` int(10) unsigned NOT NULL,
		`Drawn` int(10) unsigned NOT NULL,
		`Lost` int(10) unsigned NOT NULL,
		`GoalsFor` int(10) unsigned NOT NULL,
		`GoalsAgainst` int(10) unsigned NOT NULL,
		`StandingDate` int(10) unsigned NOT NULL,
		`Points` int(10) unsigned NOT NULL,
		`PosMove` int(11) NOT NULL default '0',
		PRIMARY KEY  (`StandingId`)
	)", __FILE__, __LINE__);
echo "{$db_prefix}pl_standings created<br />";

// smf_pl_teams Table
db_query("
	CREATE TABLE IF NOT EXISTS `{$db_prefix}pl_teams` (
		`TeamId` int(10) unsigned NOT NULL auto_increment,
		`Name` varchar(45) NOT NULL,
		`Image` varchar(400) default NULL,
		`Won` int(10) unsigned NOT NULL default '0',
		`Drawn` int(10) unsigned NOT NULL default '0',
		`Lost` int(10) unsigned NOT NULL default '0',
		`GoalsFor` int(10) unsigned NOT NULL default '0',
		`GoalsAgainst` int(10) unsigned NOT NULL default '0',
		`Points` int(10) unsigned NOT NULL default '0',
		PRIMARY KEY  (`TeamId`)
	)", __FILE__, __LINE__);
echo "{$db_prefix}pl_teams created<br />";

// smf_pl_users Table
db_query("
	CREATE TABLE IF NOT EXISTS `{$db_prefix}pl_users` (
		`UserId` int(10) unsigned NOT NULL auto_increment,
		`ReceivePMs` tinyint(1) NOT NULL,
		PRIMARY KEY  (`UserId`)
	)", __FILE__, __LINE__);
echo "{$db_prefix}pl_users created<br />";

// smf_pl_week Table
db_query("
	CREATE TABLE IF NOT EXISTS `{$db_prefix}pl_week` (
		`WeekId` int(10) unsigned NOT NULL,
		PRIMARY KEY  (`WeekId`)
	)", __FILE__, __LINE__);
echo "{$db_prefix}pl_week created<br />";

// smf_pl_shoutbox
db_query("
	CREATE TABLE IF NOT EXISTS `{$db_prefix}pl_shoutbox` (
		`id` int(5) NOT NULL auto_increment,
		`name` varchar(50) NOT NULL default '',
		`date` int(10) unsigned NOT NULL,
		`content` varchar(255) NOT NULL default '',
	PRIMARY KEY  (`id`)
	)", __FILE__, __LINE__);
echo "{$db_prefix}pl_shoutbox created<br />";


echo '<br /><b>Adding Data to Tables</b><br />';

// Only add data to the week table if it did not exist before
if ($plWeekExists == false) {
	db_query("INSERT INTO {$db_prefix}pl_week (WeekId) VALUES (1)", __FILE__, __LINE__);
	echo "Added one row to {$db_prefix}pl_week<br />";
} else {
	echo "Table {$db_prefix}pl_week already exists - no data added<br />";
}

// Only add data to the team table if it did not exist before
if ($plTeamsExists == false) {

	db_query("INSERT INTO {$db_prefix}pl_teams (Name, Image) VALUES ('Arsenal', 'Arsenal.gif')", __FILE__, __LINE__);
	db_query("INSERT INTO {$db_prefix}pl_teams (Name, Image) VALUES ('Liverpool', 'Liverpool.gif')", __FILE__, __LINE__);
	db_query("INSERT INTO {$db_prefix}pl_teams (Name, Image) VALUES ('Chelsea', 'Chelsea.gif')", __FILE__, __LINE__);
	db_query("INSERT INTO {$db_prefix}pl_teams (Name, Image) VALUES ('Aston Villa', 'Aston_Villa.gif')", __FILE__, __LINE__);
	db_query("INSERT INTO {$db_prefix}pl_teams (Name, Image) VALUES ('West Ham', 'West_Ham.gif')", __FILE__, __LINE__);
	db_query("INSERT INTO {$db_prefix}pl_teams (Name, Image) VALUES ('Hull City', 'Hull_City.gif')", __FILE__, __LINE__);
	db_query("INSERT INTO {$db_prefix}pl_teams (Name, Image) VALUES ('Blackburn Rovers', 'Blackburn_Rovers.gif')", __FILE__, __LINE__);
	db_query("INSERT INTO {$db_prefix}pl_teams (Name, Image) VALUES ('West Brom', 'West_Brom.gif')", __FILE__, __LINE__);
	db_query("INSERT INTO {$db_prefix}pl_teams (Name, Image) VALUES ('Portsmouth', 'Portsmouth.gif')", __FILE__, __LINE__);
	db_query("INSERT INTO {$db_prefix}pl_teams (Name, Image) VALUES ('Manchester City', 'Manchester_City.gif')", __FILE__, __LINE__);
	db_query("INSERT INTO {$db_prefix}pl_teams (Name, Image) VALUES ('Middlesbrough', 'Middlesbrough.gif')", __FILE__, __LINE__);
	db_query("INSERT INTO {$db_prefix}pl_teams (Name, Image) VALUES ('Manchester United', 'Manchester_United.gif')", __FILE__, __LINE__);
	db_query("INSERT INTO {$db_prefix}pl_teams (Name, Image) VALUES ('Sunderland', 'Sunderland.gif')", __FILE__, __LINE__);
	db_query("INSERT INTO {$db_prefix}pl_teams (Name, Image) VALUES ('Wigan Athletic', 'Wigan_Athletic.gif')", __FILE__, __LINE__);
	db_query("INSERT INTO {$db_prefix}pl_teams (Name, Image) VALUES ('Everton', 'Everton.gif')", __FILE__, __LINE__);
	db_query("INSERT INTO {$db_prefix}pl_teams (Name, Image) VALUES ('Fulham', 'Fulham.gif')", __FILE__, __LINE__);
	db_query("INSERT INTO {$db_prefix}pl_teams (Name, Image) VALUES ('Newcastle United', 'Newcastle_United.gif')", __FILE__, __LINE__);
	db_query("INSERT INTO {$db_prefix}pl_teams (Name, Image) VALUES ('Stoke City', 'Stoke_City.gif')", __FILE__, __LINE__);
	db_query("INSERT INTO {$db_prefix}pl_teams (Name, Image) VALUES ('Bolton Wanderers', 'Bolton_Wanderers.gif')", __FILE__, __LINE__);
	db_query("INSERT INTO {$db_prefix}pl_teams (Name, Image) VALUES ('Tottenham Hostpur', 'Tottenham_Hostpur.gif')", __FILE__, __LINE__);
	echo "Added twenty rows to {$db_prefix}pl_teams<br />";
} else {
	echo "Table {$db_prefix}pl_teams already exists - no data added<br />";
}

echo '<br /><b>Updating SMF Tables</b><br />';

// Update members table to include flag for prediction league. We need to see if it exists first though
$result = db_query("SHOW COLUMNS FROM {$db_prefix}members", __FILE__, __LINE__);
$foundColumn = false;
while ($row = mysql_fetch_assoc($result)){
	if ($row['Field'] == 'pl_enabled') {
		$foundColumn = true;
		break;
	}
}
mysql_free_result($result);

if ($foundColumn == false) {
	$result = db_query("ALTER TABLE {$db_prefix}members ADD COLUMN `pl_enabled` BOOLEAN DEFAULT 0", __FILE__, __LINE__);
	echo "Inserted pl_enabled column into {$db_prefix}members<br />";
} else {
	echo "Column pl_enabled exists in {$db_prefix}members<br />";
}

// Inserting settings
db_query("INSERT IGNORE INTO {$db_prefix}settings (`variable`,`value`) VALUES ('SMFPredictionLeague_enabled', 'on')", __FILE__, __LINE__);
db_query("INSERT IGNORE INTO {$db_prefix}settings (`variable`,`value`) VALUES ('SMFPredictionLeague_pmsOn', 'on')", __FILE__, __LINE__);
db_query("INSERT IGNORE INTO {$db_prefix}settings (`variable`,`value`) VALUES ('SMFPredictionLeague_debugOn', '0')", __FILE__, __LINE__);
db_query("INSERT IGNORE INTO {$db_prefix}settings (`variable`,`value`) VALUES ('SMFPredictionLeague_usersPerPage', '30')", __FILE__, __LINE__);
db_query("INSERT IGNORE INTO {$db_prefix}settings (`variable`,`value`) VALUES ('SMFPredictionLeague_homeScorePoints', '1')", __FILE__, __LINE__);
db_query("INSERT IGNORE INTO {$db_prefix}settings (`variable`,`value`) VALUES ('SMFPredictionLeague_awayScorePoints', '1')", __FILE__, __LINE__);
db_query("INSERT IGNORE INTO {$db_prefix}settings (`variable`,`value`) VALUES ('SMFPredictionLeague_correctResultPoints', '1')", __FILE__, __LINE__);
db_query("INSERT IGNORE INTO {$db_prefix}settings (`variable`,`value`) VALUES ('SMFPredictionLeague_correctScorePoints', '3')", __FILE__, __LINE__);
db_query("INSERT IGNORE INTO {$db_prefix}settings (`variable`,`value`) VALUES ('SMFPredictionLeague_firstBackground', 'gold')", __FILE__, __LINE__);
db_query("INSERT IGNORE INTO {$db_prefix}settings (`variable`,`value`) VALUES ('SMFPredictionLeague_secondBackground', 'lightgreen')", __FILE__, __LINE__);
db_query("INSERT IGNORE INTO {$db_prefix}settings (`variable`,`value`) VALUES ('SMFPredictionLeague_thirdBackground', 'lightyellow')", __FILE__, __LINE__);
db_query("INSERT IGNORE INTO {$db_prefix}settings (`variable`,`value`) VALUES ('SMFPredictionLeague_version', '{$version}')", __FILE__, __LINE__);
db_query("INSERT IGNORE INTO {$db_prefix}settings (`variable`,`value`) VALUES ('SMFPredictionLeague_timeOffset', '0')", __FILE__, __LINE__);
db_query("INSERT IGNORE INTO {$db_prefix}settings (`variable`,`value`) VALUES ('SMFPredictionLeague_maximumPredictionScore', '10')", __FILE__, __LINE__);
db_query("INSERT IGNORE INTO {$db_prefix}settings (`variable`,`value`) VALUES ('SMFPredictionLeague_weekId', '1')", __FILE__, __LINE__);
db_query("INSERT IGNORE INTO {$db_prefix}settings (`variable`,`value`) VALUES ('SMFPredictionLeague_drawsEnabled', 'on')", __FILE__, __LINE__);
echo "Added 11 settings into {$db_prefix} settings<br />";

// Adding welcome to shoutbox
db_query("INSERT INTO {$db_prefix}pl_shoutbox (Name, Date, Content) VALUES ('SMFModding', {$timestamp}, 'Prediction League v{$version} installed')", __FILE__, __LINE__);

//Done
echo '<br/><font color="green"><b>Install script complete</b></font>';
echo '<br/>Any issues please go to <a href="http://www.smfmodding.com/">http://www.smfmodding.com/</a>';

?>
