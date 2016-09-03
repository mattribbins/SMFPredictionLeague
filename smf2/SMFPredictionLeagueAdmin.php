<?php

//SMFPredictionLeague Admin Source File
//SMFPredictionLeagueAdmin.php

// If the file isn't called by SMF, it's bad!
if (!defined('SMF'))
	die('Hacking attempt...');

// Check for the administrative permission to do this.
isAllowedTo('admin_forum');

// Include  files
require_once($sourcedir . '/SMFPredictionLeagueCommonAdmin.php');
require_once($sourcedir . '/SMFPredictionLeagueCommonFuncs.php');

// Define constants
define("DEFAULT_WEEK", 1);
define("DEFAULT_ACTION", "settings");

global $txt;

// Load the language file
loadLanguage('SMFPredictionLeagueAdmin');

	// TODO: clean up some of this stuff
	// You have to be able to moderate the forum to do this.
	//isAllowedTo('manage_attachments');

	// Setup the template stuff we'll probably need.
	//loadTemplate('ManageAttachments');

	// If they want to delete attachment(s), delete them. (otherwise fall through..)
	/*$subActions = array(
		'attachments' => 'ManageAttachmentSettings',
		'attachpaths' => 'ManageAttachmentPaths',
		'avatars' => 'ManageAvatarSettings',
		'browse' => 'BrowseFiles',
		'byAge' => 'RemoveAttachmentByAge',
		'bySize' => 'RemoveAttachmentBySize',
		'maintenance' => 'MaintainFiles',
		'moveAvatars' => 'MoveAvatars',
		'repair' => 'RepairAttachments',
		'remove' => 'RemoveAttachment',
		'removeall' => 'RemoveAllAttachments'
	);*/

	// Pick the correct sub-action.
	/*if (isset($_REQUEST['sa']) && isset($subActions[$_REQUEST['sa']]))
		$context['sub_action'] = $_REQUEST['sa'];
	else
		$context['sub_action'] = 'browse';*/

	// This uses admin tabs - as it should!
	$context[$context['admin_menu_name']]['tab_data'] = array(
		'title' => $txt['SMFPredictionLeague'],
		'help' => 'prediction_league',
		'description' => $txt['SMFPredictionLeague_misc']['adminBlurb'],
	);

	// Finally fall through to what we are doing.
	//$subActions[$context['sub_action']]();

	

// Check if they're allowed here
isAllowedTo('SMFPredictionLeague_admin');

//Main function
function SMFPredictionLeagueAdmin()
{ 
	global $context, $txt, $settings, $scripturl, $modSettings, $sourcedir, $db_prefix, $smcFunc;

	$context['page_title'] = $txt['SMFPredictionLeague_admin'];
	
	// Add some debug output for form variables if required
	if ($modSettings["SMFPredictionLeague_debugOn"] == 'on' && $context['user']['is_admin'] == 1) {
		getDebugInfo();
	}
	
	// We need to turn this off due to the nature of some of the queries we use. If this is not turned off then SMF thinks it could be a hacking attempt
	$modSettings['disableQueryCheck'] = 1;
	
	if (isset($_POST['formaction']) && !empty($_POST['formaction'])) {
		$context['SMFPredictionLeague']['action'] = $_POST['formaction'];
	} elseif (isset($context['current_subaction']) && !empty($context['current_subaction'])) {
		$context['SMFPredictionLeague']['action'] = $context['current_subaction'];
	} else {
		$context['SMFPredictionLeague']['action'] = DEFAULT_ACTION;
	}
	
	// If the week has been posted back, set this as it will be required
	if (isset($_POST['week'])) {
		$context['SMFPredictionLeague']['selected_week'] = $_POST['week'];
	} else {
		$context['SMFPredictionLeague']['selected_week'] = $modSettings['SMFPredictionLeague_weekId'];
	}
	

	// This is where we decide what actions need to be performed dependant on the action that was selected by the user
	switch ($context['SMFPredictionLeague']['action']) {
	
		// User is saving the prediction results
		case 'save_results' :
			
			// Save the results
			saveUpdates('results');
			
			$context['StatusUpdateText'] = $txt['SMFPredictionLeague_StatusUpdateText']['resultsSaved'];
			
			// We have stoped saving results, so set action to viewing results
			$context['SMFPredictionLeague']['action'] = 'results';
			
			break;

		// User is saving the settings
		Case 'maintenance' :
			
			// Save the setting data
			leagueMaintenance();
			
			break;
			
		// User is saving the settings
		Case 'save_settings' :
			
			// Save the setting data
			saveSettings();
			
			break;
	
		// User wants to increment the week
		case 'increment_week':

			$query = getIncrementWeekSql();
			
			$newSettings['SMFPredictionLeague_maximumPredictionScore'] = $modSettings['SMFPredictionLeague_weekId'] + 1;
			updateSettings($newSettings);
		
			// If debug is on and this is the admin output trace information
			if ($modSettings["SMFPredictionLeague_debugOn"] == 'on' && $context['user']['is_admin'] == 1) {
				$context["SMFPredictionLeague_debugOutput"] .= '<font color="blue">SMFPredictionLeagueAdmin</font> - <font color="orange">db_query</font> - <font color="green">' . $query . '</font><br/>';
			}
		
			// Insert the new week into the database
			$smcFunc['db_query']('', $query);
				
			$context['StatusUpdateText'] = $txt['SMFPredictionLeague_StatusUpdateText']['weekIncremented'];
			
			// The action is not the form action, as that would be increment week. Instead, in this case it is whatever the other action was before clicking on the button
			$context['SMFPredictionLeague']['action'] = $_REQUEST['sa'];

			break;

		// User has clicked on the update fixtures button
		case 'update_fixtures' :
		
			// Update the fixtures. Once they have been updated we need to go back to the fixtures page that was selected
			saveUpdates('fixtures');
			
			$context['StatusUpdateText'] = $txt['SMFPredictionLeague_StatusUpdateText']['updatesSaved'];
			
			break;
		
		// User has clicked on the save button when entering fixtures
		case 'save_fixtures' :
		
			// Save the fixtures. Once they have been saved we need to go back to the fixtures page that was selected
			saveFixtures();

			$context['StatusUpdateText'] = $txt['SMFPredictionLeague_StatusUpdateText']['fixturesSaved'];
			
			break;
			
		// User has clicked on the delete fixtures button when viewing the fixtures
		case 'delete_fixtures' :
			
			// Delete the fixtures. Once they have been deleted we need to go back to the fixtures page that was selected
			deleteFixtures();
			
			$context['StatusUpdateText'] = $txt['SMFPredictionLeague_StatusUpdateText']['fixturesDeleted'];
			
			break;

		// User has clicked on the add fixtures button, so we just show the template for adding fixtures, therefore not much to do here except get the week
		// data that needs to be displayed
		case 'add_fixtures' :

			// Get the week data
			getWeekData();

			break;
		
		// User has clicked on the add teams button, so we just show the template for adding teams, therefore not much to do here
		case 'add_teams':
		
			$context['StatusUpdateText'] = $txt['SMFPredictionLeague_StatusUpdateText']['add_teams'];
			break;
		
		// User has chosen to save any team updates they have made
		case 'save_teams' :
		
			saveTeams();
			break;
			
		// User has elected to show all outstanding results
		case 'outstanding_results' :

			// Get the week data
			getWeekData();

			// Get the fixture data for the selected week
			GetOutstandingFixtures();
			
			break;

		// User has chosen to detele the selected teams
		case 'remove_teams' :
		
			// Remove any selected teams from the database
			deleteTeams();
			
			break;
		
		// Any other option
		default :
			break;
	}
	
	// Additional actions here that are common
	if ($context['SMFPredictionLeague']['action'] == 'save_results' ||
		$context['SMFPredictionLeague']['action'] == 'fixtures' ||
		$context['SMFPredictionLeague']['action'] == 'results' ||
		$context['SMFPredictionLeague']['action'] == 'delete_fixtures' ||
		$context['SMFPredictionLeague']['action'] == 'save_fixtures' ||
		$context['SMFPredictionLeague']['action'] == 'update_fixtures' ||
		$context['SMFPredictionLeague']['action'] == 'increment_week'
	) {
		// Get the fixture data for the selected week
		getFixtureData();

		// Get the week data, as	this is shown in week selection dropdown
		getWeekData();
		
	}

	// More additional common actions
	if ($context['SMFPredictionLeague']['action'] == 'save_results' ||
		$context['SMFPredictionLeague']['action'] == 'fixtures' ||
		$context['SMFPredictionLeague']['action'] == 'results' ||
		$context['SMFPredictionLeague']['action'] == 'delete_fixtures' ||
		$context['SMFPredictionLeague']['action'] == 'save_fixtures' ||
		$context['SMFPredictionLeague']['action'] == 'update_fixtures' ||
		$context['SMFPredictionLeague']['action'] == 'increment_week' ||
		$context['SMFPredictionLeague']['action'] == 'outstanding_results' ||
		$context['SMFPredictionLeague']['action'] == 'remove_teams' ||
		$context['SMFPredictionLeague']['action'] == 'add_fixtures' ||
		$context['SMFPredictionLeague']['action'] == 'teams' ||
		$context['SMFPredictionLeague']['action'] == 'save_teams'
	) {
		// Get team data, as this is shown in dropdowns
		getTeamData();
	}

	// Add javascript for multiple checkbox selection
	$context['html_headers'] .= '<script type="text/javascript">
			function checkAll(selectedForm, checked) {
				for (var i = 0; i < selectedForm.elements.length; i++) {
					var e = selectedForm.elements[i];
					if (e.type==\'checkbox\') {
						e.checked = checked;
					}
				}

			}

			</script>
	';
	
	// Load the admin template to present the data
	loadTemplate('SMFPredictionLeagueAdmin');
}


function saveUpdates($saveType) {
	
	global $db_prefix, $smcFunc, $modSettings, $context, $txt;
	
	// Loop through each post item and get any that specify a delete is required
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
				break;
			case 'date' :
				$matches[$matchid]["date"] = $value;
				break;
			case 'hour' :
				$matches[$matchid]["hour"] = $value;
				break;
			case 'mins' :
				$matches[$matchid]["mins"] = $value;
				break;
			case 'HScr' :
				$matches[$matchid]["homeScore"] = $value;
				break;
			case 'AScr' :
				$matches[$matchid]["awayScore"] = $value;
				break;
			case 'mtch' :
				$matches[$matchid]["id"] = $matchid;
				break;
		}
	}
	
	$counter = 0;

	if (isset($matches) && sizeof($matches) > 0) {
		// Now insert this data into the database
		foreach ($matches as &$matchData) {		

			// If we are updating fixture data
			if ($saveType == 'fixtures') {

				// Don't insert any fixtures that appear to be incomplete
				if (!empty($matchData["id"])) {
					$query = getUpdateMatchFixtureSql($matchData);

					// If debug is on and this is the admin output trace information
					if ($modSettings["SMFPredictionLeague_debugOn"] == 'on' && $context['user']['is_admin'] == 1) {
						$context["SMFPredictionLeague_debugOutput"] .= '<font color="blue">saveUpdates</font> - <font color="orange">db_query</font> - <font color="green">' . $query . '</font><br/>';
					}

					$smcFunc['db_query']('', $query);
					
				}
			// Otherwise we are updating result data
			} elseif ($saveType == 'results') {

				// Only update those results submitted that had the checkbox checked
				if (!empty($matchData["id"]) && $matchData["homeScore"] != "-"  && $matchData["awayScore"] != "-") {

					// Update the match data
					$query = getUpdateMatchResultSql($matchData);

					// If debug is on and this is the admin output trace information
					if ($modSettings["SMFPredictionLeague_debugOn"] == 'on' && $context['user']['is_admin'] == 1) {
						$context["SMFPredictionLeague_debugOutput"] .= '<font color="blue">saveUpdates</font> - <font color="orange">db_query</font> - <font color="green">' . $query . '</font><br/>';
					}

					$smcFunc['db_query']('', $query);
					
				}
				
			}
			$counter++;
		}
	} else {
	
		//TODO: Some message about nothing to save
	
	}

	// If we have just saved some results, update the team data, as this contains data on how well they have done
	if ($saveType == 'results') {
		// Update the team data
		// TODO - Configurable points
		$winPoints = 3;
		$drawPoints = 1;
		$losePoints = 0;
		$teamQuery = getUpdateTeamResultSql($winPoints, $drawPoints, $losePoints);

		// If debug is on and this is the admin output trace information
		if ($modSettings["SMFPredictionLeague_debugOn"] == 'on' && $context['user']['is_admin'] == 1) {
			$context["SMFPredictionLeague_debugOutput"] .= '<font color="blue">saveUpdates</font> - <font color="orange">db_query</font> - <font color="green">' . $teamQuery . '</font><br/>';
		}
		
		$smcFunc['db_query']('', $teamQuery);
	}
	
	// We only need to update if results/fixtures have been entered
	if (isset($matches) && sizeof($matches) > 0) {

		// If we are entering results
		if ($saveType == 'results') {
			
			// Update prediction results now we have entered new results. We pass in the match data, as we only need to update the results of those matches selected
			updatePredictionResults($matches);
		}
		if ($_POST["SendPMs"] == 'on') {
			// Now send a PM to those users that wanted to be notified, get those users from the database
			$query = getPMUsersSql();
			
		
			// If debug is on and this is the admin output trace information
			if ($modSettings["SMFPredictionLeague_debugOn"] == 'on' && $context['user']['is_admin'] == 1) {
				$context["SMFPredictionLeague_debugOutput"] .= '<font color="blue">saveUpdates</font> - <font color="orange">db_query</font> - <font color="green">' . $query . '</font><br/>';
			}
			
			$result = $smcFunc['db_query']('', $query);
			
			// If we have users that want PMs
			if ($smcFunc['db_num_rows']($result) > 0) {
			

				// Populate an array containing these users
				$toIds = array();
				while ($row = $smcFunc['db_fetch_assoc']($result)) {
					array_push($toIds, $row["UserId"]);
				}
				$smcFunc['db_free_result']($result);

				$pmto = array(
					'to' => array(),
					'bcc' => $toIds
				);

				// Set message and subject dependant on fixture or result we have entered
				if ($saveType == 'fixtures') {
				
					$subject = $txt['SMFPredictionLeagueAdmin_Pms']['fixtures_entered_title'];
					
					$message = $txt['SMFPredictionLeagueAdmin_Pms']['fixtures_entered_body'];
					
				} elseif ($saveType == 'results') {

					$subject = $txt['SMFPredictionLeagueAdmin_Pms']['results_entered_title'];
					$message = $txt['SMFPredictionLeagueAdmin_Pms']['results_entered_body'];
					
				}
				
				// If there are members to send the message to, do so now
				if (sizeof($toIds) > 0) {
					
					// TODO: This is slow, why?
					sendPredictionLeaguePMs($pmto, $message, $subject);
				}
			}
		}
	}
}


function deleteFixtures() {
	
	global $db_prefix, $smcFunc, $modSettings, $context;
	
	// Loop through each post item and get any that specify a delete is required
	foreach($_POST as $key => $value) {
		
		// The MatchId is held as a suffix on the $key $value
		if (substr($key, 0, 4) == "mtch") {
			
			$matchId = substr($key, 4);
			
			$query = getDeleteFixturesSql($matchId);
			
			// If debug is on and this is the admin output trace information
			if ($modSettings["SMFPredictionLeague_debugOn"] == 'on' && $context['user']['is_admin'] == 1) {
				$context["SMFPredictionLeague_debugOutput"] .= '<font color="blue">deleteFixtures</font> - <font color="orange">db_query</font> - <font color="green">' . $query . '</font><br/>';
			}
			
			// Delete from database
			$smcFunc['db_query']('', $query);
			
		}
		
	}
	
	// Now delete any predictions that may be orhpaned
	$query = getDeletePredictionsForNonExistentMatchesSql($matchId);

	// If debug is on and this is the admin output trace information
	if ($modSettings["SMFPredictionLeague_debugOn"] == 'on' && $context['user']['is_admin'] == 1) {
		$context["SMFPredictionLeague_debugOutput"] .= '<font color="blue">deleteFixtures</font> - <font color="orange">db_query</font> - <font color="green">' . $query . '</font><br/>';
	}
	
	// Also delete any predictions against this fixture
	$smcFunc['db_query']('', $query);

	// Need to update the standings to reflect change
	updateStandings();

}


function deleteTeams() {
	
	global $db_prefix, $smcFunc, $modSettings, $context;
	
	// Loop through each post item and get any that specify a delete is required
	foreach($_POST as $key => $value) {
		
		// The MatchId is held as a suffix on the $key $value
		if (substr($key, 0, 4) == "team") {
			
			$teamId = substr($key, 4);
			
			// TODO: Really should do all this within a transaction
			
			// Delete the team from the team table
			$query = getDeleteTeamsSql($teamId);
			
			// If debug is on and this is the admin output trace information
			if ($modSettings["SMFPredictionLeague_debugOn"] == 'on' && $context['user']['is_admin'] == 1) {
				$context["SMFPredictionLeague_debugOutput"] .= '<font color="blue">deleteTeams</font> - <font color="orange">db_query</font> - <font color="green">' . $query . '</font><br/>';
			}
			
			// Execute query
			$smcFunc['db_query']('', $query);
			
			// Delete all matches that contain this team
			$query = getDeleteMatchesForTeamSql($teamId);

			// If debug is on and this is the admin output trace information
			if ($modSettings["SMFPredictionLeague_debugOn"] == 'on' && $context['user']['is_admin'] == 1) {
				$context["SMFPredictionLeague_debugOutput"] .= '<font color="blue">deleteTeams</font> - <font color="orange">db_query</font> - <font color="green">' . $query . '</font><br/>';
			}
			
			// Execute query
			$smcFunc['db_query']('', $query);
			
			// Delete all predictions that had this team in it, so this will now be all predictions where the referenced team id doesn't exist
			$query = getDeletePredictionsForNonExistentMatchesSql();

			// If debug is on and this is the admin output trace information
			if ($modSettings["SMFPredictionLeague_debugOn"] == 'on' && $context['user']['is_admin'] == 1) {
				$context["SMFPredictionLeague_debugOutput"] .= '<font color="blue">deleteTeams</font> - <font color="orange">db_query</font> - <font color="green">' . $query . '</font><br/>';
			}
			
			// Execute query
			$smcFunc['db_query']('', $query);			
			
		}
		
	}
	updateStandings();
	$context['SMFPredictionLeague']['action'] = 'teams';
}


function getFixtureData() {

	global $context, $db_prefix, $txt, $modSettings, $smcFunc;
	$query = getFixturesSql();

	// If debug is on and this is the admin output trace information
	if ($modSettings["SMFPredictionLeague_debugOn"] == 'on' && $context['user']['is_admin'] == 1) {
		$context["SMFPredictionLeague_debugOutput"] .= '<font color="blue">getFixtureData</font> - <font color="orange">db_query</font> - <font color="green">' . $query . '</font><br/>';
	}

	$result = $smcFunc['db_query']('', $query);
		
	$context['SMFPredictionLeague']['fixtures'] = array();
	
	while ($row = $smcFunc['db_fetch_assoc']($result)) {
		$context['SMFPredictionLeague']['fixtures'][] = $row;
	}
	$smcFunc['db_free_result']($result);

}


function GetOutstandingFixtures() {
	global $context, $db_prefix, $smcFunc, $modSettings;
	
	$query = getOutstandingFixturesSql();
	
	// If debug is on and this is the admin output trace information
	if ($modSettings["SMFPredictionLeague_debugOn"] == 'on' && $context['user']['is_admin'] == 1) {
		$context["SMFPredictionLeague_debugOutput"] .= '<font color="blue">GetOutstandingFixtures</font> - <font color="orange">db_query</font> - <font color="green">' . $query . '</font><br/>';
	}
	
	$result = $smcFunc['db_query']('', $query);
		
	$context['SMFPredictionLeague']['fixtures'] = array();
	
	while ($row = $smcFunc['db_fetch_assoc']($result)) {
		$context['SMFPredictionLeague']['fixtures'][] = $row;
		
	}
	$smcFunc['db_free_result']($result);

}

function getWeekData() {

	global $context, $db_prefix, $txt, $modSettings, $smcFunc;
	
	$query = getReturnWeeksSql();
	
	// If debug is on and this is the admin output trace information
	if ($modSettings["SMFPredictionLeague_debugOn"] == 'on' && $context['user']['is_admin'] == 1) {
		$context["SMFPredictionLeague_debugOutput"] .= '<font color="blue">getWeekData</font> - <font color="orange">db_query</font> - <font color="green">' . $query . '</font><br/>';
	}

	$result = $smcFunc['db_query']('', $query);
		
	$context['SMFPredictionLeague']['weeks'] = array();
	
	while ($row = $smcFunc['db_fetch_assoc']($result)) {
		$context['SMFPredictionLeague']['weeks'][] = $row;
	}
		
				
	if ($smcFunc['db_num_rows']($result) == 0) {
		$context['SMFPredictionLeague']['error'] = $txt['SMFPredictionLeague_errors']['noweeks'];
	}
			
	$smcFunc['db_free_result']($result);
}

function updatePredictionResults($matchData) {

	global $db_prefix, $smcFunc, $context, $modSettings;

	// Now get the full SQL for the prediction update
	$query = getPredictionResultsSql($matchData);

	// The query string may return nothing if there are no results to update - need to find a more elegant solution to this one
	if (strlen($query) > 0) {

		// If debug is on and this is the admin output trace information
		if ($modSettings["SMFPredictionLeague_debugOn"] == 'on' && $context['user']['is_admin'] == 1) {
			$context["SMFPredictionLeague_debugOutput"] .= '<font color="blue">updatePredictionResults</font> - <font color="orange">db_query</font> - <font color="green">' . $query . '</font><br/>';
		}

		// If debug is on and this is the admin output trace information
		if ($modSettings["SMFPredictionLeague_debugOn"] == 'on' && $context['user']['is_admin'] == 1) {
			$context["SMFPredictionLeague_debugOutput"] .= '<font color="blue">updatePredictionResults  (' . date('H:i:s:u') . ')</font> - <font color="orange">db_query</font> - <font color="green">' . $query . '</font><br/>';
		}
		
		// Retrieve the match results and predictions for the selected week
		$result = $smcFunc['db_query']('', $query);

		// If we have some predictions to work on
		if(mysql_num_rows($result) != 0) {
			
			while ($row = $smcFunc['db_fetch_assoc']($result)) {
			
			
				// If debug is on and this is the admin output trace information
				if ($modSettings["SMFPredictionLeague_debugOn"] == 'on' && $context['user']['is_admin'] == 1) {
					$context["SMFPredictionLeague_debugOutput"] .= '<font color="blue">updatePredictionResults  (' . date('H:i:s:u') . ')</font> - <font color="orange">Prediction Id</font> - <font color="green">' . $row["PredictionId"] . '</font><br/>';
				}

				// Default points
				$points = 0;
				
				$goalsFor = 0;
				$goalsAgainst = 0;
				
				// Default point type. This can be "W" for Win, "L" for Lose or "D" for Draw
				$pointType = 'L';
				
				// If home score matches, add points
				if ($row["HomeScore"] == $row["PredHomeScore"]) {
					
					// If debug is on and this is the admin output trace information
					if ($modSettings["SMFPredictionLeague_debugOn"] == 'on' && $context['user']['is_admin'] == 1) {
						$context["SMFPredictionLeague_debugOutput"] .= '<font color="blue">updatePredictionResults  (' . date('H:i:s:u') . ')</font> - <font color="orange">Home Score</font> - <font color="green">Matches</font><br/>';
					}
					$points = $points + $modSettings["SMFPredictionLeague_homeScorePoints"];
					
					$goalsFor = $goalsFor + $row["HomeScore"];
				} elseif ($row["HomeScore"] > $row["PredHomeScore"]) {
					$goalsFor = $goalsFor + $row["PredHomeScore"];
					$goalsAgainst = $goalsAgainst + ($row["HomeScore"] - $row["PredHomeScore"]);
					
				} elseif ($row["HomeScore"] < $row["PredHomeScore"]) {
					$goalsFor = $goalsFor + $row["HomeScore"];
					$goalsAgainst = $goalsAgainst + ($row["PredHomeScore"] - $row["HomeScore"]);
				}
				
				// If away score matches add points
				if ($row["AwayScore"] == $row["PredAwayScore"]) {
					
					// If debug is on and this is the admin output trace information
					if ($modSettings["SMFPredictionLeague_debugOn"] == 'on' && $context['user']['is_admin'] == 1) {
						$context["SMFPredictionLeague_debugOutput"] .= '<font color="blue">updatePredictionResults  (' . date('H:i:s:u') . ')</font> - <font color="orange">Away Score</font> - <font color="green">Matches</font><br/>';
					}
					$points = $points + $modSettings["SMFPredictionLeague_awayScorePoints"];
					
					$goalsFor = $goalsFor + $row["AwayScore"];
				} elseif ($row["AwayScore"] > $row["PredAwayScore"]) {
					$goalsFor = $goalsFor + $row["PredAwayScore"];
					$goalsAgainst = $goalsAgainst + ($row["AwayScore"] - $row["PredAwayScore"]);
					
				} elseif ($row["AwayScore"] < $row["PredAwayScore"]) {
					$goalsFor = $goalsFor + $row["AwayScore"];
					$goalsAgainst = $goalsAgainst + ($row["PredAwayScore"] - $row["AwayScore"]);
				}

				
				// If score is draw and matches add points
				if (($row["AwayScore"] == $row["HomeScore"]) && ($row["PredAwayScore"] == $row["PredHomeScore"])) {
					
					// If debug is on and this is the admin output trace information
					if ($modSettings["SMFPredictionLeague_debugOn"] == 'on' && $context['user']['is_admin'] == 1) {
						$context["SMFPredictionLeague_debugOutput"] .= '<font color="blue">updatePredictionResults  (' . date('H:i:s:u') . ')</font> - <font color="orange">Draw</font> - <font color="green">Matches</font><br/>';
					}
					$pointType = 'D'; // Improve point type to draw
					$points = $points + $modSettings["SMFPredictionLeague_correctResultPoints"];
				}
				
				// If score is away win and matches add points
				if (($row["AwayScore"] > $row["HomeScore"]) && ($row["PredAwayScore"] > $row["PredHomeScore"])) {
				
					// If debug is on and this is the admin output trace information
					if ($modSettings["SMFPredictionLeague_debugOn"] == 'on' && $context['user']['is_admin'] == 1) {
						$context["SMFPredictionLeague_debugOutput"] .= '<font color="blue">updatePredictionResults  (' . date('H:i:s:u') . ')</font> - <font color="orange">Away Win</font> - <font color="green">Matches</font><br/>';
					}
					$points = $points + $modSettings["SMFPredictionLeague_correctResultPoints"];
				}
				
				// If score is home win and matches add points
				if (($row["AwayScore"] < $row["HomeScore"]) && ($row["PredAwayScore"] < $row["PredHomeScore"])) {
				
					// If debug is on and this is the admin output trace information
					if ($modSettings["SMFPredictionLeague_debugOn"] == 'on' && $context['user']['is_admin'] == 1) {
						$context["SMFPredictionLeague_debugOutput"] .= '<font color="blue">updatePredictionResults  (' . date('H:i:s:u') . ')</font> - <font color="orange">Home Win</font> - <font color="green">Matches</font><br/>';
					}

					$points = $points + $modSettings["SMFPredictionLeague_correctResultPoints"];
				}
				
				// If score matches add points
				if (($row["AwayScore"] == $row["PredAwayScore"]) && ($row["HomeScore"] == $row["PredHomeScore"])) {
				
					// If debug is on and this is the admin output trace information
					if ($modSettings["SMFPredictionLeague_debugOn"] == 'on' && $context['user']['is_admin'] == 1) {
						$context["SMFPredictionLeague_debugOutput"] .= '<font color="blue">updatePredictionResults  (' . date('H:i:s:u') . ')</font> - <font color="orange">Score Match</font> - <font color="green">Matches</font><br/>';
					}

					$pointType = 'W'; // Improve point type to win
					$points = $points + $modSettings["SMFPredictionLeague_correctScorePoints"];
				}

				// If debug is on and this is the admin output trace information
				if ($modSettings["SMFPredictionLeague_debugOn"] == 'on' && $context['user']['is_admin'] == 1) {
					$context["SMFPredictionLeague_debugOutput"] .= '<font color="blue">updatePredictionResults  (' . date('H:i:s:u') . ')</font> - <font color="orange">Total Points</font> - <font color="green">' . $points . '</font><br/>';
					$context["SMFPredictionLeague_debugOutput"] .= '<font color="blue">updatePredictionResults  (' . date('H:i:s:u') . ')</font> - <font color="orange">Total For</font> - <font color="green">' . $goalsFor . '</font><br/>';
					$context["SMFPredictionLeague_debugOutput"] .= '<font color="blue">updatePredictionResults  (' . date('H:i:s:u') . ')</font> - <font color="orange">Total Against</font> - <font color="green">' . $goalsAgainst . '</font><br/>';
				}
				
				// TODO: Add bonus feature
				$Bonus = 0;
			
				// If debug is on and this is the admin output trace information
				if ($modSettings["SMFPredictionLeague_debugOn"] == 'on' && $context['user']['is_admin'] == 1) {
					$context["SMFPredictionLeague_debugOutput"] .= '<font color="blue">updatePredictionResults</font> - <font color="orange">Points Different</font> - <font color="green">Updating Database</font><br/>';
				}
				
				$predictionId = $row["PredictionId"];
				
				$query = getUpdatePredictionResultSql($points, $pointType, $goalsFor, $goalsAgainst, $predictionId);
				
				// If debug is on and this is the admin output trace information
				if ($modSettings["SMFPredictionLeague_debugOn"] == 'on' && $context['user']['is_admin'] == 1) {
					$context["SMFPredictionLeague_debugOutput"] .= '<font color="blue">updatePredictionResults</font> - <font color="orange">db_query</font> - <font color="green">' . $query . '</font><br/>';
				}

				$smcFunc['db_query']('', $query);
			}
		} 

		// Rebuild the standings table now that the prediction results have been udpated. Note that we need to do this whether an y predictions were found or not, as new members may have joined
		updateStandings();
			
		$smcFunc['db_free_result']($result);
	} else {
	
		//TODO - Return some message about there being no results to update
	}
}

function updateStandings() {

	global $context, $txt, $settings, $scripturl, $modSettings, $sourcedir, $db_prefix, $smcFunc;
	
	// We also need to get each users previous position, as on the first iteration of the following query loop we won't have that data. This can't be included in the
	// previous query, as we delete the old entries first and therefore don't have the data. An alternative to this approach is we could mark them
	// for deletion and only do that once the standings have been udpated. Need to consider this...
	$query = getUsersPreviousPositionSql();
	
	// If debug is on and this is the admin output trace information
	if ($modSettings["SMFPredictionLeague_debugOn"] == 'on' && $context['user']['is_admin'] == 1) {
		$context["SMFPredictionLeague_debugOutput"] .= '<font color="blue">updateStandings  (' . date('H:i:s:u') . ')</font> - <font color="orange">db_query</font> - <font color="green">' . $query . '</font><br/>';
	}

	$result = $smcFunc['db_query']('', $query);
	$previous = array();
	while ($row = $smcFunc['db_fetch_assoc']($result)) {
		$previous[$row["UserId"]]["Position"] = $row["Position"];
	}
	$smcFunc['db_free_result']($result);
	
	// Remove all entries from the standings table that we are now going to overwrite
	$query = getDeleteStandingsSql();
	
	// If debug is on and this is the admin output trace information
	if ($modSettings["SMFPredictionLeague_debugOn"] == 'on' && $context['user']['is_admin'] == 1) {
		$context["SMFPredictionLeague_debugOutput"] .= '<font color="blue">updateStandings  (' . date('H:i:s:u') . ')</font> - <font color="orange">db_query</font> - <font color="green">' . $query . '</font><br/>';
	}
	
	$smcFunc['db_query']('', $query);

	// This query is a monster, need to find out if we can do this more efficiently. The point here is that we are trying to pregenerate much of the table so it doesn't have to be
	// created each time someone browses
	// Notes:
	// * Ideally we would only want to execute for results in the current week, but if a modification is made to a week before the current week then all following standings will change. Therefore we do week >= selected week
	// * We have to join with smf_members to get user name which is the default sort after points
	
	$query = getStandingsToUpdateSql(2);
	
	// If debug is on and this is the admin output trace information
	if ($modSettings["SMFPredictionLeague_debugOn"] == 'on' && $context['user']['is_admin'] == 1) {
		$context["SMFPredictionLeague_debugOutput"] .= '<font color="blue">updateStandings  (' . date('H:i:s:u') . ')</font> - <font color="orange">db_query</font> - <font color="green">' . $query . '</font><br/>';
	}
	
	$result3 = $smcFunc['db_query']('', $query);
	
	
	$lastWeek = -1;
	$position = 0;
	while ($row = $smcFunc['db_fetch_assoc']($result3)) {

		// Get the week for this entry
		$week = $row["WeekId"];
		
		// If this is a new week, we need to update position 
		if ($week != $lastWeek) {
			$position = 1;
		} else {
			$position++;
		}
		$lastWeek = $week;
		
		// If there isn't a previous position, this current position is the previous. This should only happen for the first match
		if (empty($previous[$row["UserId"]]["Position"])) {
			$previous[$row["UserId"]]["Position"] = $position;
		};

		// Calculate position values
		if (sizeof($previous) > 0) {
			$previousPosition = $previous[$row["UserId"]]["Position"] == null ? 0 : $previous[$row["UserId"]]["Position"];
			$posMove = $previous[$row["UserId"]]["Position"] - $position;
		} else {
			$previousPosition = 0;
			$posMove = 0;
		}
		$previous[$row["UserId"]]["Position"] = $position;
		
		$query = getAddStandingsSql($row, $position, $previousPosition, $posMove);
		
		// If debug is on and this is the admin output trace information
		if ($modSettings["SMFPredictionLeague_debugOn"] == 'on' && $context['user']['is_admin'] == 1) {
			$context["SMFPredictionLeague_debugOutput"] .= '<font color="blue">updateStandings  (' . date('H:i:s:u') . ')</font> - <font color="orange">db_query</font> - <font color="green">' . $query . '</font><br/>';
		}
	
		$smcFunc['db_query']('', $query);
		
	}


	$smcFunc['db_free_result']($result3);

}

function saveFixtures() {
	
	global $db_prefix, $smcFunc, $context, $modSettings;

	// Array used to store the formatted match data that has been submitted for each insertion into database
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
				break;
			case 'date' :
				$matches[$matchid]["date"] = $value;
				break;
			case 'hour' :
				$matches[$matchid]["hour"] = $value;
				break;
			case 'mins' :
				$matches[$matchid]["mins"] = $value;
				break;
				
		}
	}
	
	// Now insert this data into the database
	foreach ($matches as &$matchData) {
	
		// Don't insert any fixtures that appear to be incomplete
		if (!empty($matchData["date"])) {
		

			$query = getAddMatchSql($matchData);
			
			// If debug is on and this is the admin output trace information
			if ($modSettings["SMFPredictionLeague_debugOn"] == 'on' && $context['user']['is_admin'] == 1) {
				$context["SMFPredictionLeague_debugOutput"] .= '<font color="blue">saveFixtures</font> - <font color="orange">db_query</font> - <font color="green">' . $query . '</font><br/>';
			}
			
			$smcFunc['db_query']('', $query);
		}
	}
}


function saveTeams() {
	
	global $db_prefix, $smcFunc, $context, $modSettings;

	// Array used to store the formatted match data that has been submitted for each insertion into database
	$teams = array();
	
	// Loop through all the posted data items
	foreach($_POST as $key => $value) {
		
		// Depending on the type of data this is, insert into array at appropriate place
		switch (substr($key, 0, 4)) {
			case 'team' :
				$teamId = substr($key, 4);
				$teams[$teamId]["team"] = $value;
				break;
			case 'imge' :
				$teamId = substr($key, 4);
				$teams[$teamId]["image"] = $value;
				break;
		}
	}
	
	// Now insert this data into the database
	foreach ($teams as &$teamData) {

		// Don't insert any fixtures that appear to be incomplete - check date and times
		if (!empty($teamData["team"]) && !empty($teamData["image"])) {
		
			$query = getAddTeamSql($teamData);
			
			// If debug is on and this is the admin output trace information
			if ($modSettings["SMFPredictionLeague_debugOn"] == 'on' && $context['user']['is_admin'] == 1) {
				$context["SMFPredictionLeague_debugOutput"] .= '<font color="blue">saveTeams</font> - <font color="orange">db_query</font> - <font color="green">' . $query . '</font><br/>';
			}
			
			$smcFunc['db_query']('', $query);
		}
	}
}

function getTeamData() {
	global $context, $db_prefix, $txt, $modSettings, $smcFunc;
	$query = getTeamsSql();
	
	// If debug is on and this is the admin output trace information
	if ($modSettings["SMFPredictionLeague_debugOn"] == 'on' && $context['user']['is_admin'] == 1) {
		$context["SMFPredictionLeague_debugOutput"] .= '<font color="blue">getTeamData</font> - <font color="orange">db_query</font> - <font color="green">' . $query . '</font><br/>';
	}

	$result = $smcFunc['db_query']('', $query);
		
	$context['SMFPredictionLeague']['teams'] = array();
	
	while ($row = $smcFunc['db_fetch_assoc']($result)) {
		$context['SMFPredictionLeague']['teams'][] = $row;
	}
		
	if(mysql_num_rows($result) == 0) {
		$context['SMFPredictionLeague']['error'] = $txt['SMFPredictionLeague_errors']['noTeams'];
	}
			
	$smcFunc['db_free_result']($result);
}

function leagueMaintenance() {

	global $context, $txt, $modSettings, $smcFunc;
	
	if (isset($_GET['type'])) {
		switch ($_GET['type']) {
			
			case 'cleanshoutbox':

				$query = getCleanShoutboxSql();

				// If debug is on and this is the admin output trace information
				if ($modSettings["SMFPredictionLeague_debugOn"] == 'on' && $context['user']['is_admin'] == 1) {
					$context["SMFPredictionLeague_debugOutput"] .= '<font color="blue">cleanshoutbox</font> - <font color="orange">db_query</font> - <font color="green">' . $query . '</font><br/>';
				}
				
				$smcFunc['db_query']('', $query);
				
				$context['SMFPredictionLeague']['cleanComplete'] = $txt['SMFPredictionLeague_misc']['shoutboxCleanComplete'];

				break;
			
			case 'wipeleague':
			
				$query = getDeleteMatchesSql();
				// If debug is on and this is the admin output trace information
				if ($modSettings["SMFPredictionLeague_debugOn"] == 'on' && $context['user']['is_admin'] == 1) {
					$context["SMFPredictionLeague_debugOutput"] .= '<font color="blue">getDeleteMatchesSql</font> - <font color="orange">db_query</font> - <font color="green">' . $query . '</font><br/>';
				}
				
				$smcFunc['db_query']('', $query);

				$query = getDeleteAllPredictionsSql();
				// If debug is on and this is the admin output trace information
				if ($modSettings["SMFPredictionLeague_debugOn"] == 'on' && $context['user']['is_admin'] == 1) {
					$context["SMFPredictionLeague_debugOutput"] .= '<font color="blue">getDeleteAllPredictionsSql</font> - <font color="orange">db_query</font> - <font color="green">' . $query . '</font><br/>';
				}
				
				$smcFunc['db_query']('', $query);

				$query = getDeleteAllStandingsSql();
				// If debug is on and this is the admin output trace information
				if ($modSettings["SMFPredictionLeague_debugOn"] == 'on' && $context['user']['is_admin'] == 1) {
					$context["SMFPredictionLeague_debugOutput"] .= '<font color="blue">getDeleteAllStandingsSql</font> - <font color="orange">db_query</font> - <font color="green">' . $query . '</font><br/>';
				}
				
				$smcFunc['db_query']('', $query);

				$query = getResetTeamsSql();
				// If debug is on and this is the admin output trace information
				if ($modSettings["SMFPredictionLeague_debugOn"] == 'on' && $context['user']['is_admin'] == 1) {
					$context["SMFPredictionLeague_debugOutput"] .= '<font color="blue">getResetTeamsSql</font> - <font color="orange">db_query</font> - <font color="green">' . $query . '</font><br/>';
				}
				
				$smcFunc['db_query']('', $query);

				// Reset week
				$query = getResetWeekSql();
				// If debug is on and this is the admin output trace information
				if ($modSettings["SMFPredictionLeague_debugOn"] == 'on' && $context['user']['is_admin'] == 1) {
					$context["SMFPredictionLeague_debugOutput"] .= '<font color="blue">getResetTeamsSql</font> - <font color="orange">db_query</font> - <font color="green">' . $query . '</font><br/>';
				}
				$smcFunc['db_query']('', $query);

				$query = getIncrementWeekSql();
				
				$newSettings['SMFPredictionLeague_maximumPredictionScore'] = $modSettings['SMFPredictionLeague_weekId'] + 1;
				updateSettings($newSettings);
			
				// If debug is on and this is the admin output trace information
				if ($modSettings["SMFPredictionLeague_debugOn"] == 'on' && $context['user']['is_admin'] == 1) {
					$context["SMFPredictionLeague_debugOutput"] .= '<font color="blue">SMFPredictionLeagueAdmin</font> - <font color="orange">db_query</font> - <font color="green">' . $query . '</font><br/>';
				}
			
				// Insert the new week into the database
				$smcFunc['db_query']('', $query);

				$context['SMFPredictionLeague']['wipeComplete'] = $txt['SMFPredictionLeague_misc']['wipeLeagueComplete'];
				
				break;
		}
	}
}

function saveSettings() {

	global $settings, $modSettings;

	if(!isset($_POST['enabled']))
		$_POST['enabled'] = false;
	$newSettings['SMFPredictionLeague_enabled'] = $_POST['enabled'];

	if(!isset($_POST['pmsOn']))
		$_POST['pmsOn'] = false;
	$newSettings['SMFPredictionLeague_pmsOn'] = $_POST['pmsOn'];

	if(!isset($_POST['debugOn']))
		$_POST['debugOn'] = false;
	$newSettings['SMFPredictionLeague_debugOn'] = $_POST['debugOn'];
	
	if(!isset($_POST['drawsEnabled']))
		$_POST['drawsEnabled'] = false;
	$newSettings['SMFPredictionLeague_drawsEnabled'] = $_POST['drawsEnabled'];
	
	if (isset($_POST['usersPerPage'])) {
		$newSettings['SMFPredictionLeague_usersPerPage'] = $_POST['usersPerPage'];
	}
	if (isset($_POST['homeScore'])) {
		$newSettings['SMFPredictionLeague_homeScorePoints'] = $_POST['homeScore'];
	}
	if (isset($_POST['awayScore'])) {
		$newSettings['SMFPredictionLeague_awayScorePoints'] = $_POST['awayScore'];
	}
	if (isset($_POST['correctResult'])) {
		$newSettings['SMFPredictionLeague_correctResultPoints'] = $_POST['correctResult'];
	}
	if (isset($_POST['correctScore'])) {
		$newSettings['SMFPredictionLeague_correctScorePoints'] = $_POST['correctScore'];
	}
	if (isset($_POST['firstBackground'])) {
		$newSettings['SMFPredictionLeague_firstBackground'] = $_POST['firstBackground'];
	}
	if (isset($_POST['secondBackground'])) {
		$newSettings['SMFPredictionLeague_secondBackground'] = $_POST['secondBackground'];
	}
	if (isset($_POST['thirdBackground'])) {
		$newSettings['SMFPredictionLeague_thirdBackground'] = $_POST['thirdBackground'];
	}
	
	if (isset($_POST['timeOffset'])) {
		$newSettings['SMFPredictionLeague_timeOffset'] = $_POST['timeOffset'];
	}

	if (isset($_POST['maximumPredictionScore'])) {
		$newSettings['SMFPredictionLeague_maximumPredictionScore'] = $_POST['maximumPredictionScore'];
	}

	// Save all these settings
	updateSettings($newSettings);
}
?>