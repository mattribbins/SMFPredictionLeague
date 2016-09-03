<?php
/********************************************************************************
* SMFPredictionLeague.php                                                                       				*
*********************************************************************************
*                                                                                								*
*  Execution file for "First Mod" modification.                                   					*
*                                                                                 								*
*                    													*
*                                                                                 								*
*********************************************************531
************************/

if (!defined('SMF'))
	die('Hacking attempt...');
	
/*	This file is all about the front end prediction league for users. It handles setting up the data to show
	at various points within the application

	void SMFPredictionLeague()
		// !!

	void GetHomePageData()
		// !!

	int BuildContextPageFromDb($querySqlFunc, $contextVar, $vars)(
		// !!

	string BuildContextPageFromDbReturnVal($querySqlFunc, $contextVar, $rowId, $vars)

	int BuildContextScalarFromDb($querySqlFunc, $contextVar, $rowId)

	string GetScalarFromDb($querySqlFunc, $rowId)

	void GetStatisticsData

	void GetTeamDetailsData

	void UpdateUserInfo

	void GetUserInfoData

	void GetTeamsData

	void GetLeagueData

	void UpdatePredictions

	void SavePredictions

	void OutstandingPredictions

	void GetPredictionsData

	void GetWeeks

	void GetPredictionLeagueUserCount

	void JoinLeague

*/

//isAllowedTo('pl_play');

global $settings;
require_once($sourcedir . '/SMFPredictionLeagueCommon.php');
require_once($sourcedir . '/SMFPredictionLeagueCommonFuncs.php');

function SMFPredictionLeague() {

	global $context, $txt, $modSettings, $user_settings;
	
	$context['page_title'] = $txt['SMFPredictionLeague'];

	/*
		$context['html_headers'] .= '
		
		<script type=\'text/javascript\'>
		function fireMyPopup() {
			
			// Determine how much the visitor had scrolled

			var scrolledX, scrolledY;
			if( self.pageYOffset ) {
			  scrolledX = self.pageXOffset;
			  scrolledY = self.pageYOffset;
			} else if( document.documentElement && document.documentElement.scrollTop ) {
			  scrolledX = document.documentElement.scrollLeft;
			  scrolledY = document.documentElement.scrollTop;
			} else if( document.body ) {
			  scrolledX = document.body.scrollLeft;
			  scrolledY = document.body.scrollTop;
			}

			// Determine the coordinates of the center of the page

			var centerX, centerY;
			if( self.innerHeight ) {
			  centerX = self.innerWidth;
			  centerY = self.innerHeight;
			} else if( document.documentElement && document.documentElement.clientHeight ) {
			  centerX = document.documentElement.clientWidth;
			  centerY = document.documentElement.clientHeight;
			} else if( document.body ) {
			  centerX = document.body.clientWidth;
			  centerY = document.body.clientHeight;
			}
			
			var leftOffset = scrolledX + (centerX - 250) / 2;
			var topOffset = scrolledY + (centerY - 200) / 2;
			
			document.getElementById("mypopup").style.top = topOffset + "px";
			document.getElementById("mypopup").style.left = leftOffset + "px";
			document.getElementById("mypopup").style.display = "block";
			
		}
		</script> 
	';
	*/
	
	// Add some debug output for form variables if required
	if (isset($modSettings["SMFPredictionLeague_debugOn"]) && $modSettings["SMFPredictionLeague_debugOn"] == 'on' && $context['user']['is_admin'] == 1) {
		getDebugInfo();
	}
	
	// Load the language file for use in the output
	loadLanguage('SMFPredictionLeague');
	
	// Load the template
	loadTemplate('SMFPredictionLeague');

	// Only load data if Preidtion League enabled
	if (isset($modSettings['SMFPredictionLeague_enabled']) && $modSettings['SMFPredictionLeague_enabled'] == 'on') {
	
		$modSettings['disableQueryCheck'] = 1;

		// Create an array of possible actions with the functions that will be called
		$actions = array(
			'predictions' => 'GetPredictionsData',
			'user_info' => 'GetUserInfoData',
			'league_table' => 'GetLeagueData',
			'outstanding_predictions' => 'OutstandingPredictions',
			'save_predictions' => 'SavePredictions',
			'update_predictions' => 'UpdatePredictions',
			'teams' => 'GetTeamsData',
			'update_userInfo' => 'UpdateUserInfo',
			'team_detail' => 'GetTeamDetailsData',
			'home' => 'GetHomePageData',
			'statistics' => 'GetStatisticsData',
		);

		$context['tab_links'] = array();
		$context['tab_links'][] = array('action' => 'home',
											'label' => isset($txt['SMFPredictionLeague_tabs']['home']) ? $txt['SMFPredictionLeague_tabs']['home'] : 'Home');
		$context['tab_links'][] = array('action' => 'league_table',
											'label' => isset($txt['SMFPredictionLeague_tabs']['leagueTable']) ? $txt['SMFPredictionLeague_tabs']['leagueTable'] : 'League Table');
		$context['tab_links'][] = array('action' => 'predictions', 
											'label' => isset($txt['SMFPredictionLeague_tabs']['predictions']) ? $txt['SMFPredictionLeague_tabs']['predictions'] : 'Predictions');
		$context['tab_links'][] = array('action' => 'teams',
											'label' => isset($txt['SMFPredictionLeague_tabs']['teams']) ? $txt['SMFPredictionLeague_tabs']['teams'] : 'Teams');
		$context['tab_links'][] = array('action' => 'user_info',
											'label' => isset($txt['SMFPredictionLeague_tabs']['userInfo']) ? $txt['SMFPredictionLeague_tabs']['userInfo'] : 'User Info');
		$context['tab_links'][] = array('action' => 'statistics',
											'label' => isset($txt['SMFPredictionLeague_tabs']['statistics']) ? $txt['SMFPredictionLeague_tabs']['statistics'] : 'Statistics');

		if (isset($_POST['formaction'])){
			$action = $_POST['formaction'];
		} elseif (!isset($_GET['sa'])){
			$action = 'home';
		} else {
			$action = $_GET['sa'];
		}	
	
		if(isset($actions[$action])) {
			$actions[$action]();
		}
	}
	if ($user_settings['pl_enabled'] == 0 && (isset($_POST['submit']) && $_POST['submit'] == 'Join')) {
		JoinLeague();
	}
	
}


function GetHomePageData() {
	global $context, $settings, $boardurl, $modSettings;

	// Add the shoutbox Javascript to the page header
	$context['html_headers'] .= '
		<script type="text/javascript">
			shoutboxSaveUrl = \'' . $boardurl . '/Sources/SMFPredictionLeagueShoutboxSend.php\';
			shoutboxOutputUrl = \'' . $boardurl . '/Sources/SMFPredictionLeagueShoutboxOutput.php\';
		</script>';
	$context['html_headers'] .= '
		<script type="text/javascript" src="'.$settings['default_theme_url'].'/scripts/pl_scripts/shoutbox.js">
		</script>
	';
	
	// Get the maximum week
	// TODO - might be worth looking at caching this or something
	$maxWeek = GetScalarFromDb('GetReturnMaxWeeksSql', 'MaxWeek');
	
	// Build nextMatch context value with results from GetReturnNextMatchSql query. 
	$vars["offsetDate"] = getOffsetTime(time(), -$modSettings["SMFPredictionLeague_timeOffset"]);
	$nextMatchId = BuildContextPageFromDbReturnVal('GetReturnNextMatchSql', 'nextMatch', 'MatchId', $vars);
	
	// Build topPositions context value with results from GetPredictionLeagueSql query. Note that we pass variables to this through the $vars array
	$vars = array();
	$vars['limit'] = 'LIMIT 0,10';
	$vars['version'] = 1;
	$vars['sort'] = 'S.Position';
	$vars['dir'] = 'ASC';
	$vars['week'] = $maxWeek;
	BuildContextPageFromDb('GetPredictionLeagueSql', 'topPositions', $vars);

	// Build topPositions context value with results from GetPredictionLeagueSql query. Note that we pass variables to this through the $vars array
	// We can only get data if a match is to be played
	if (!empty($nextMatchId)) {
		$vars = array();
		$vars['matchId'] = $nextMatchId;
		BuildContextPageFromDb('GetReturnMatchPredictionsSql', 'nextMatchPredictions', $vars);
	}

	// Get the biggest climbers in the league
	$climbWeek = 1;
	if ($maxWeek > 1) {
		// We want to show climbers from last week, not this coming one
		$climbWeek = $maxWeek - 1;
	}
	$vars = array();
	$vars['weekId'] = $climbWeek;
	$vars['maxRows'] = 5;
	$vars['version'] = 1;
	BuildContextPageFromDb('GetReturnTopMoversSql', 'topMovers', $vars);

	$context['SMFPredictionLeague']['action'] = 'home';
}

function BuildContextPageFromDb($querySqlFunc, $contextVar, $vars) {
	global $context, $modSettings;
	
	$query = $querySqlFunc(isset($vars) ? $vars : '');
	
	// If debug is on and this is the admin output trace information
	if ($modSettings["SMFPredictionLeague_debugOn"] == 'on' && $context['user']['is_admin'] == 1) {
		$context["SMFPredictionLeague_debugOutput"] .= '<font color="blue">BuildContextPageFromDb</font> - <font color="orange">db_query</font> - <font color="green">' . $query . '</font><br/>';
	}
//echo '<br>' , $querySqlFunc;
//echo '<br>' , $query;
	// TODO: Updated for v1
	//$result = $smcFunc['db_query']('', $query);
	$result = db_query($query, __FILE__, __LINE__);
	
	$context['SMFPredictionLeague'][$contextVar] = array();

	// TODO: Updated for v1
	//while ($row = $smcFunc['db_fetch_assoc']($result)) {
	while ($row = mysql_fetch_assoc($result)){
		
		$context['SMFPredictionLeague'][$contextVar][] = $row;
	}
	
	$rows = mysql_num_rows($result);
	// TODO: Updated for v1
	//$rows = $smcFunc['db_num_rows']($result);
	
	// TODO: Updated for v1
	//$smcFunc['db_free_result']($result);
	mysql_free_result($result);

	
	return $rows;
}


function BuildContextPageFromDbReturnVal($querySqlFunc, $contextVar, $rowId, $vars) {
	global $context, $modSettings;
	
	$query = $querySqlFunc(isset($vars) ? $vars : '');
	
	// If debug is on and this is the admin output trace information
	if ($modSettings["SMFPredictionLeague_debugOn"] == 'on' && $context['user']['is_admin'] == 1) {
		$context["SMFPredictionLeague_debugOutput"] .= '<font color="blue">BuildContextPageFromDb</font> - <font color="orange">db_query</font> - <font color="green">' . $query . '</font><br/>';
	}

	$result = db_query($query, __FILE__, __LINE__);
	
	// TODO: Updated for v1
	//$result = $smcFunc['db_query']('', $query);
	
	$context['SMFPredictionLeague'][$contextVar] = array();
	
	// TODO: Updated for v1
	//while ($row = $smcFunc['db_fetch_assoc']($result)) {
	while ($row = mysql_fetch_assoc($result)){
		$returnVal = $row[$rowId];
		$context['SMFPredictionLeague'][$contextVar][] = $row;
	}
	
	// TODO: Updated for v1
	//$smcFunc['db_free_result']($result);
	mysql_free_result($result);
	
	
	return isset($returnVal) ? $returnVal : '';
}


function BuildContextScalarFromDb($querySqlFunc, $contextVar, $rowId) {
	global $context, $modSettings;
	
	$query = $querySqlFunc();
	
	// If debug is on and this is the admin output trace information
	if ($modSettings["SMFPredictionLeague_debugOn"] == 'on' && $context['user']['is_admin'] == 1) {
		$context["SMFPredictionLeague_debugOutput"] .= '<font color="blue">BuildContextScalarFromDb</font> - <font color="orange">db_query</font> - <font color="green">' . $query . '</font><br/>';
	}
	
	// TODO: Updated for v1
	//$result = $smcFunc['db_query']('', $query);
	$result = db_query($query, __FILE__, __LINE__);
	
	$context['SMFPredictionLeague'][$contextVar] = array();

	// TODO: Updated for v1
	//while ($row = $smcFunc['db_fetch_assoc']($result)) {
	while ($row = mysql_fetch_assoc($result)){
		$context['SMFPredictionLeague'][$contextVar] = $row[$rowId];
	}
	
	$rows = mysql_num_rows($result);
	// TODO: Updated for v1
	//$rows = $smcFunc['db_num_rows']($result);
	
	// TODO: Updated for v1
	//$smcFunc['db_free_result']($result);
	mysql_free_result($result);
	
	return $rows;
}


function GetScalarFromDb($querySqlFunc, $rowId) {
	global $context, $modSettings;
	
	$query = $querySqlFunc();
	
	// If debug is on and this is the admin output trace information
	if ($modSettings["SMFPredictionLeague_debugOn"] == 'on' && $context['user']['is_admin'] == 1) {
		$context["SMFPredictionLeague_debugOutput"] .= '<font color="blue">GetScalarFromDb</font> - <font color="orange">db_query</font> - <font color="green">' . $query . '</font><br/>';
	}
	
	// TODO: Updated for v1
	//$result = $smcFunc['db_query']('', $query);
	$result = db_query($query, __FILE__, __LINE__);
	
	// TODO: Updated for v1
	//while ($row = $smcFunc['db_fetch_assoc']($result)) {
	while ($row = mysql_fetch_assoc($result)){
		
		$returnVal = $row[$rowId];
	}
	
	// TODO: Updated for v1
	//$smcFunc['db_free_result']($result);
	mysql_free_result($result);
	
	return $returnVal;
}



function GetStatisticsData() {
	global $context;
	
	// Build predictionStatsPage context value with results from GetReturnPredictionStatsSql query
	BuildContextPageFromDb('GetReturnPredictionStatsSql', 'predictionStatsPage', null);
	
	$maxWeek = GetScalarFromDb('GetReturnMaxWeeksSql', 'MaxWeek');
	
	// Build topMoversPage context value with results from GetReturnTopMoversSql query. Note that we pass variables to this through the $vars array
	$climbWeek = 1;
	if ($maxWeek > 1) {
		// We want to show climbers from last week, not this coming one
		$climbWeek = $maxWeek - 1;
	}
	$vars = array();
	$vars['weekId'] = $climbWeek;
	$vars['maxRows'] = 5;
	$vars['sortDir'] = 'DESC';
	$vars['version'] = 1;
	BuildContextPageFromDb('GetReturnTopMoversSql', 'topMoversPage', $vars);
	
	// Build topLosersPage context value with results from GetReturnTopMoversSql query. Note that we pass variables to this through the $vars array
	$vars = array();
	$vars['weekId'] = $climbWeek;
	$vars['maxRows'] = 5;
	$vars['sortDir'] = 'ASC';
	$vars['version'] = 1;
	BuildContextPageFromDb('GetReturnTopMoversSql', 'topLosersPage', $vars);
	
	// Build averagePointsPage context value with results from GetReturnAveragePointsStatsSql query. Note that we pass variables to this through the $vars array
	$vars = array();
	$vars['maxRows'] = 5;
	$vars['sortDir'] = 'DESC';
	$vars['version'] = 1;
	BuildContextPageFromDb('GetReturnAveragePointsStatsSql', 'averagePointsPage', $vars);
	
	// Build weeksAtTopPage context value with results from GetReturnMaxWeeksAtTopStatsSql query. Note that we pass variables to this through the $vars array
	$vars = array();
	$vars['maxRows'] = 5;
	$vars['version'] = 1;
	BuildContextPageFromDb('GetReturnMaxWeeksAtTopStatsSql', 'weeksAtTopPage', $vars);

	$context['SMFPredictionLeague']['action'] = 'statistics';
}

function GetTeamDetailsData() {

	global $context;
	
	$teamId = isset($_GET['id']) ? $_GET['id'] : 0;
	
	// Build page context value with results from getReturnTeamMatchesSql query. Note that we pass variables to this through the $vars array
	$vars = array();
	$vars['teamId'] = $teamId;
	BuildContextPageFromDb('getReturnTeamMatchesSql', 'page', $vars);

	$context['SMFPredictionLeague']['action'] = 'team_details';
}

function UpdateUserInfo() {

	global $context;

	$query = GetUpdateUserInfoSql($context['user']['id'], $_POST["PMNotification"]);
	
	//$smcFunc['db_query']('', $query);
	db_query($query, __FILE__, __LINE__);
	
	GetUserInfoData();
	
	$context['SMFPredictionLeague']['action'] = 'user_info';
}

// Returns data for the user information page
function GetUserInfoData() {
	global $context, $txt;

	$userId = isset($_GET['id']) ? $_GET['id'] : $context['user']['id'];
	
	// Build page context value with results from GetReturnUserInfoSql query. Note that we pass variables to this through the $vars array
	$vars = array();
	$vars['userId'] = $userId;
	$vars['version'] = 1;
	$rows = BuildContextPageFromDb('GetReturnUserInfoSql', 'page', $vars);

	if ($rows == 0) {
		$context['SMFPredictionLeague']['error'] = $txt["SMFPredictionLeague_errors"]["noUserInfo"];
	}
	
	$maxWeek = GetScalarFromDb('GetReturnMaxWeeksSql', 'MaxWeek');
	
	// This query will return positional user data
	// TODO - 40 constant configurable
	$maxValue = 40;
	if ($maxWeek - $maxValue > 0) {
		$startWeek = $maxWeek - $maxValue;
	} else {
		$startWeek = 0;
	}
	
	// Build subpage context value with results from GetReturnUserPositionsSql query. Note that we pass variables to this through the $vars array
	$vars = array();
	$vars['userId'] = $userId;
	$vars['startWeek'] = $startWeek;
	$vars['max'] = $maxValue;
	BuildContextPageFromDb('GetReturnUserPositionsSql', 'subpage', $vars);


	// Build maxUsers context value with results from GetReturnMaxUsersSql query. Note that we pass variables to this through the $vars array
	BuildContextScalarFromDb('GetReturnMaxUsersSql', 'maxUsers', 'MaxUsers');

	$context['SMFPredictionLeague']['action'] = 'user_info';
}

function GetTeamsData() {

	global $context, $txt;
	
	// Create an array that will map the sort selection to the query value
	$sort_methods = array(
		'pos' => 'T.Points',
		'teamname' => 'T.Name',
		'played' => 'Played',
		'won' => 'T.Won',
		'drawn' => 'T.Drawn',
		'lost' => 'T.Lost',
		'goalsfor' => 'T.GoalsFor',
		'goalsagainst' => 'T.GoalsAgainst',
		'points' => 'T.Points',
	);

	// If sort not set, do so now 
	if (!isset($_GET['sort'])) {
		$context['SMFPredictionLeague']['sort_by'] = 'pos';
		$_GET['sort'] = 'T.Points';
		$_GET['dir'] = 'up';
	} else {
		// Otherwise set the sort query string and reset context
		$context['SMFPredictionLeague']['sort_by'] = $_GET['sort'];
		$_GET['sort'] = $sort_methods[$_GET['sort']];
	}
	

	// Switch sort direction for next time
	// Note we are dealing with 'up' and 'down' here purely due to the name of the images, so we set to SQL ASC and DESC once finished
	If ($_GET['dir'] == 'up') {
		$context['SMFPredictionLeague']['sort_direction'] = 'down';
		$_GET['dir'] = "DESC";
	} else {
		$context['SMFPredictionLeague']['sort_direction'] = 'up';
		$_GET['dir'] = "ASC";
	}	

	// Build page context value with results from GetReturnTeamsSql query. Note that we pass variables to this through the $vars array
	$vars = array();
	$vars['orderBy'] = $_GET['sort'] . ' ' . $_GET['dir'];
	$rows = BuildContextPageFromDb('GetReturnTeamsSql', 'page', $vars);
	
	if ($rows == 0) {
		$context['SMFPredictionLeague']['error'] = $txt["SMFPredictionLeague_errors"]["noTeams"];
	}

	$context['SMFPredictionLeague']['action'] = 'teams';

}

function GetLeagueData() {
	global $context, $modSettings;

	// We need a count of the prediction league users to work with
	GetPredictionLeagueUserCount();

	// How many users to show per page
	$split = $modSettings["SMFPredictionLeague_usersPerPage"];
	$limit = '';

	if ($context['SMFPredictionLeague']['userCount'] > 0 && $context['SMFPredictionLeague']['userCount'] >= $split) {
		// Get any limiting to be done on the result
		list($start, $end) = split('[_]', $_POST["positions"]);
		$context['SMFPredictionLeague']['positions'] = $_POST["positions"];

		if ($start != null) {
			$limit = 'LIMIT ' . ($start - 1) . ',' . ($end - $start + 1);
		} else {
			$limit = 'LIMIT 0,' . $split;
		}
	}

	// The league page also shows weeks, so get data for weeks
	GetWeeks();
	
	// Create an array that will map the sort selection to the query value
	$sort_methods = array(
		'pos' => 'S.Position',
		'user' => 'M.realName',
		'played' => 'S.Played',
		'won' => 'S.Won',
		'drawn' => 'S.Drawn',
		'lost' => 'S.Lost',
		'goalsfor' => 'S.GoalsFor',
		'goalsagainst' => 'S.GoalsAgainst',
		'points' => 'S.Points',
	);

	// If sort not set, do so now 
	if (!isset($_GET['sort'])) {
		$context['SMFPredictionLeague']['sort_by'] = 'pos';
		$_GET['sort'] = 'S.Position';
		$_GET['dir'] = 'down';
	} else {
		// Otherwise set the sort query string and reset context
		$context['SMFPredictionLeague']['sort_by'] = $_GET['sort'];
		$_GET['sort'] = $sort_methods[$_GET['sort']];
	}

	// Switch sort direction for next time
	// Note we are dealing with 'up' and 'down' here purely due to the name of the images, so we set to SQL ASC and DESC once finished
	If ($_GET['dir'] == 'up') {
		$context['SMFPredictionLeague']['sort_direction'] = 'down';
		$_GET['dir'] = "DESC";
	} else {
		$context['SMFPredictionLeague']['sort_direction'] = 'up';
		$_GET['dir'] = "ASC";
	}

	// Build page context value with results from GetPredictionLeagueSql query. Note that we pass variables to this through the $vars array
	$vars = array();
	$vars['limit'] = $limit;
	$vars['version'] = 1;
	$vars['sort'] = $_GET['sort'];
	$vars['dir'] = $_GET['dir'];
	$vars['week'] = $context['SMFPredictionLeague']['selected_week'];
	BuildContextPageFromDb('GetPredictionLeagueSql', 'page', $vars);

	// Set the action for the template
	$context['SMFPredictionLeague']['action'] = 'league_table';
}

function UpdatePredictions() {
	global $context, $modSettings;

	// Array used to store the formatted prediction data that has been submitted for each insertion into database
	$matches = array();
	
	// Loop through all the posted data items
	foreach($_POST as $key => $value) {
		
		// Obtain the match identifier, which is achieved through a suffix at the end of each key. The key happens to be 4 characters long to make this easier
		$matchid = substr($key, 4);
		
		// Depending on the type of data this is, insert into array at appropriate place
		switch (substr($key, 0, 4)) {
			case 'home' :
				$matches[$matchid]["home"] = $value;
				break;
			case 'away' :
				$matches[$matchid]["away"] = $value;
				
				// This is here so we can store the matchid as well
				$matches[$matchid]["matchid"] = $matchid;
				break;
			
			case 'fxwk' :
				$matches[$matchid]["week"] = $value;
				break;
				
			case 'null' :
				$matches[$matchid]["null"] = 1;
				break;
				
		}
	}
	
	// Now insert this data into the database
	foreach ($matches as &$matchData) {
	
		$updatedDateTime = time();
		
		if (isset($matchData["null"]) && $matchData["null"] == 1) {

			$query = GetInsertPredictionSql($matchData, $context['user']['id']);
		
			// If debug is on and this is the admin output trace information
			if ($modSettings["SMFPredictionLeague_debugOn"] == 'on' && $context['user']['is_admin'] == 1) {
				$context["SMFPredictionLeague_debugOutput"] .= '<font color="blue">updatePredictions</font> - <font color="orange">db_query</font> - <font color="green">' . $query . '</font><br/>';
			}

			// TODO: Updated for v1
			//$result = $smcFunc['db_query']('', $query);
			$result = db_query($query, __FILE__, __LINE__);
		} else {
		
			$query = GetUpdatePredictionSql($matchData, $context['user']['id']);

			// If debug is on and this is the admin output trace information
			if ($modSettings["SMFPredictionLeague_debugOn"] == 'on' && $context['user']['is_admin'] == 1) {
				$context["SMFPredictionLeague_debugOutput"] .= '<font color="blue">updatePredictions</font> - <font color="orange">db_query</font> - <font color="green">' . $query . '</font><br/>';
			}

			// TODO: Updated for v1
			//$result = $smcFunc['db_query']('', $query);
			$result = db_query($query, __FILE__, __LINE__);
		}
		
	}
	
	// The subsequent predictions page that is displayed will need the prediction data
	GetPredictionsData();

	$context['SMFPredictionLeague']['action'] = 'predictions';
	
}

function SavePredictions() {
	global $context, $modSettings;

	// Array used to store the formatted prediction data that has been submitted for each insertion into database
	$matches = array();
	
	// Loop through all the posted data items
	foreach($_POST as $key => $value) {
		
		// Obtain the match identifier, which is achieved through a suffix at the end of each key. The key happens to be 4 characters long to make this easier
		$matchid = substr($key, 4);
		
		// Depending on the type of data this is, insert into array at appropriate place
		switch (substr($key, 0, 4)) {
			case 'home' :
				$matches[$matchid]["home"] = $value;
				break;
			case 'week' :
				$matches[$matchid]["week"] = $value;
				break;
			case 'away' :
				$matches[$matchid]["away"] = $value;
				
				// This is here so we can store the matchid as well
				$matches[$matchid]["matchid"] = $matchid;
				break;
		}
	}
	
	// Now insert this data into the database
	foreach ($matches as &$matchData) {
	
		$query = GetInsertPredictionSql($matchData, $context['user']['id']);
		
		// If debug is on and this is the admin output trace information
		if ($modSettings["SMFPredictionLeague_debugOn"] == 'on' && $context['user']['is_admin'] == 1) {
			$context["SMFPredictionLeague_debugOutput"] .= '<font color="blue">savePredictions</font> - <font color="orange">db_query</font> - <font color="green">' . $query . '</font><br/>';
		}

		db_query($query, __FILE__, __LINE__);
			
	}
	$context['SMFPredictionLeague']['action'] = 'outstanding_predictions';
	
}

function OutstandingPredictions() {
	global $context, $txt, $modSettings;

	// Build page context value with results from GetOutstandingPredictionsSql query. Note that we pass variables to this through the $vars array
	$vars = array();
	$vars['userId'] = $context['user']['id'];
	$vars['time'] = getOffsetTime(time(), -$modSettings["SMFPredictionLeague_timeOffset"]);
	$rows = BuildContextPageFromDb('GetOutstandingPredictionsSql', 'page', $vars);

	if ($rows == 0) {
		$context['SMFPredictionLeague']['error'] = $txt["SMFPredictionLeague_errors"]["noOutstandingPredictions"];
	}
	
	$context['SMFPredictionLeague']['action'] = 'outstanding_predictions';
}

function GetPredictionsData() {

	global $context, $txt;
	
	GetWeeks();
	
	// Build page context value with results from GetPredictionsSql query. Note that we pass variables to this through the $vars array
	$vars = array();
	$vars['userId'] = $context['user']['id'];
	$vars['weekId'] = $context['SMFPredictionLeague']['selected_week'];
	$rows = BuildContextPageFromDb('GetPredictionsSql', 'page', $vars);

	if ($rows == 0)
		$context['SMFPredictionLeague']['error'] = $txt["SMFPredictionLeague_errors"]["noMatchesEntered"];
			
	$context['SMFPredictionLeague']['action'] = 'predictions';
}


function GetWeeks() {

	global $context, $txt;

	// Build weeks context value with results from GetReturnWeeksSql query. Note that we pass variables to this through the $vars array
	$rows = BuildContextPageFromDb('GetReturnWeeksSql', 'weeks', null);
	
	if ($rows == 0) {
		$context['SMFPredictionLeague']['error'] = $txt['SMFPredictionLeague_errors']['noWeeks'];
	}

	$context['SMFPredictionLeague']['selected_week'] = isset($_POST['week']) ? $_POST['week'] : GetScalarFromDb('GetReturnMaxWeeksSql', 'MaxWeek');
}


function GetPredictionLeagueUserCount() {

	global $context;
	
	// Build userCount context value with results from GetReturnUserCountSql query. Note that we pass variables to this through the $vars array
	$rows = BuildContextScalarFromDb('GetReturnUserCountSql', 'userCount', 'UserCount');

	if ($rows == 0) {
		$context['SMFPredictionLeague']['userCount'] = 0;
	}
			
}

// Joins a user to the prediction league
function JoinLeague() {
	
	global $context, $user_settings, $modSettings;
	
	// Update the members table data for the flag determining whether this user is registered for the prediction league
	updateMemberData($user_settings['ID_MEMBER'], array('pl_enabled' => 1));
	
	$receivePMs = isset($_POST['ReceivePMNotification']) ? $_POST['ReceivePMNotification'] : 1;
	
	$query = GetJoinLeagueSql($receivePMs, $user_settings['ID_MEMBER']);
	
	// If debug is on and this is the admin output trace information
	if (isset($modSettings["SMFPredictionLeague_debugOn"]) && $modSettings["SMFPredictionLeague_debugOn"] == 'on' && $context['user']['is_admin'] == 1) {
		$context["SMFPredictionLeague_debugOutput"] .= '<font color="blue">JoinLeague</font> - <font color="orange">db_query</font> - <font color="green">' . $query . '</font><br/>';
	}

	// Join league
	$result = db_query($query, __FILE__, __LINE__);
}

?>