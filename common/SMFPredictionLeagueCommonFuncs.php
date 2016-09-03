<?php
if (!defined('SMF'))
	die('Hacking attempt...');

// Returns the time using the specified hour offset	
function getOffsetTime($time, $offset) {
	$hour = date('H', $time);
	$minute = date('i', $time);
	$day = date('d', $time);
	$month = date('m', $time);
	$year = date('y', $time);
	return mktime($hour + $offset, $minute, 0, $month, $day, $year);
}
	
// Handles PM's
function sendPredictionLeaguePMs($pmTo, $pmMsg, $pmSubject) {
	
	global $scripturl, $context, $sourcedir, $txt, $modSettings;
	
	// Only send if the PMs are enabled in the settings
	if ($modSettings['SMFPredictionLeague_pmsOn'] == 'on') {
	
		include_once($sourcedir . '/Subs-Post.php');
		
		// Add Prediction League footer to PM
		$pmMsg .= '

[url]' . $scripturl . '?action=SMFPredictionLeague[/url]';
		
		// Who the PM will come from		 
		$pmfrom = array(
			'id' => $context['user']['id'],
			'name' => $context['user']['name'],
			'username' => $context['user']['username']
		);


		// Send message
		sendpm($pmTo, $pmSubject, $pmMsg, 0, $pmfrom);
	}
}

function getDebugInfo() {
	global $context, $txt, $modSettings, $user_settings, $settings;
	
	if (!isset($context["SMFPredictionLeague_debugOutput"])) {
		$context["SMFPredictionLeague_debugOutput"] = "";
	}
	$context["SMFPredictionLeague_debugOutput"] .= "<b>Form Post Values</b><br/>";
	if (sizeof($_POST) == 0) {
		$context["SMFPredictionLeague_debugOutput"] .= '<font color="red">No Form Variables</font><br/>';
	} else {
		foreach($_POST as $key => $value) {
			$context["SMFPredictionLeague_debugOutput"] .= '<font color="blue">' . $key . '</font>=<font color="green">' . $value . "</font><br/>";
		}
	}
	$context["SMFPredictionLeague_debugOutput"] .= "<br/><b>Mod Settings Values</b><br/>";
	if (sizeof($modSettings) == 0) {
		$context["SMFPredictionLeague_debugOutput"] .= '<font color="red">No ModSettings Variables</font><br/>';
	} else {
		foreach($modSettings as $key => $value) {
			$context["SMFPredictionLeague_debugOutput"] .= '<font color="blue">' . $key . '</font>=<font color="green">' . $value . "</font><br/>";
		}
	}
	$context["SMFPredictionLeague_debugOutput"] .= "<br/><b>Context Values</b><br/>";
	if (sizeof($context) == 0) {
		$context["SMFPredictionLeague_debugOutput"] .= '<font color="red">No Context Variables</font><br/>';
	} else {
		foreach($context as $key => $value) {
			if ($key != 'SMFPredictionLeague_debugOutput') {
				$context["SMFPredictionLeague_debugOutput"] .= '<font color="blue">' . $key . '</font>=<font color="green">' . $value . "</font><br/>";
			}
		}
	}
	$context["SMFPredictionLeague_debugOutput"] .= "<br/><b>User Settings Values</b><br/>";
	if (sizeof($user_settings) == 0) {
		$context["SMFPredictionLeague_debugOutput"] .= '<font color="red">No User Settings Variables</font><br/>';
	} else {
		foreach($user_settings as $key => $value) {
			if ($key != 'SMFPredictionLeague_debugOutput') {
				$context["SMFPredictionLeague_debugOutput"] .= '<font color="blue">' . $key . '</font>=<font color="green">' . $value . "</font><br/>";
			}
		}
	}
	$context["SMFPredictionLeague_debugOutput"] .= "<br/><b>Settings Values</b><br/>";
	if (sizeof($settings) == 0) {
		$context["SMFPredictionLeague_debugOutput"] .= '<font color="red">No Settings Variables</font><br/>';
	} else {
		foreach($settings as $key => $value) {
			if ($key != 'SMFPredictionLeague_debugOutput') {
				$context["SMFPredictionLeague_debugOutput"] .= '<font color="blue">' . $key . '</font>=<font color="green">' . $value . "</font><br/>";
			}
		}
	}
	$context["SMFPredictionLeague_debugOutput"] .= "<br/><b>SMF Prediction League Tracing</b><br/>";
}
?>