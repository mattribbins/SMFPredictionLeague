<?php

if (!defined('SMF'))
	die('Hacking attempt...');

// Include the common template file that is used for both 1.1 and 2.x versions of SMF
global $settings;
require_once($settings["theme_dir"] . '/SMFPredictionLeagueCommonAdmin.template.php');

function template_main()
{
	global $modSettings, $scripturl, $context, $txt, $sourcedir, $context, $settings;
	
	// Set the page title
	//$context['page_title'] = $txt['SMFPredictionLeague_admin'].' '.$txt['settings'];
	
	//echo "action={$context['current_action']},area={$context['admin_area']},sa={$context['current_subaction']}";

	echo '	<form action="', $scripturl, '?action=' . $context['current_action'] . ';area=' . $context['admin_area'] . ';sa=' , $context['current_subaction'] , '" method="post" name="SMFPredictionLeagueAdmin">
			<input type="hidden" name="formaction"/>
			<table border="0" cellspacing="0" cellpadding="4" align="center" width="90%" class="tborder" >';

	// Show appropriate block of template here depending on which sub action is being taken
	switch ($context['SMFPredictionLeague']['action']) {
	
		case 'update_fixtures' :
		case 'delete_fixtures' :
		case 'save_fixtures' :
		case 'fixtures' :
			
			// Show the main fixtures template
			template_fixtures();
			break;
		
		case 'add_fixtures' :
		
			template_add_fixtures();
			break;
			
		case 'outstanding_results':
		case 'save_results':
		case 'results':

			template_results();
			break;
		
		case 'support':
		
			template_support();
			break;

		case 'maintenance':
		
			template_maintenance();
			break;
			
		case 'save_teams' :
		case 'teams' :
		
			template_teams();
			break;
			
		case 'add_teams' :

			template_add_teams();
			
			break;

		default :

			template_settings();
			break;
	}
							
	echo '	</table></form>';
	
	if (isset($context['StatusUpdateText'])) {
		echo '			
						<br/>
						<table border="0" cellspacing="0" cellpadding="4" align="center" width="90%" class="tborder" >
							<tr class="titlebg">
								<td>' . $context['StatusUpdateText'] . '</td>
							</tr>
						</table>
		';
	}
	
	if ($modSettings["SMFPredictionLeague_debugOn"] == 'on' && $context['user']['is_admin'] == 1) {
		echo '	<br/>
						<table border="0" cellspacing="0" cellpadding="6" align="center" class="tborder" width="100%">
							<tr class="titlebg">
								<td class="windowbg2">' . $txt['SMFPredictionLeague_misc']['debugTitle'] . '</td>
							</tr>
							<tr class="windowbg2">
								<td><span class="smalltext">' . $txt['SMFPredictionLeague_misc']['debugBlurb'] . '</span></td>
							</tr>
							<tr><td class="windowbg2">' . $context["SMFPredictionLeague_debugOutput"] . '</td></tr>
						</table>
		';
	}
	
	echo ' <table width="100%"><tr><td align="center"><a href="http://www.smfmodding.com" title="Free SMF Mods" target="_blank" class="smalltext">SMFPredictionLeague ' . $modSettings["SMFPredictionLeague_version"] . ' &copy; 2008, SMFModding</a></td></tr></table>';
		
}
?>
