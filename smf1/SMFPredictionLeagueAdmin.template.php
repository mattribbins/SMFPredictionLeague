<?php

if (!defined('SMF'))
	die('Hacking attempt...');
	
// Include the common template file that is used for both 1.1 and 2.x versions of SMF
global $settings;
include $settings["theme_dir"] . '/SMFPredictionLeagueCommonAdmin.template.php';

function template_main()
{
	global $modSettings, $scripturl, $context, $txt, $sourcedir, $context, $settings;
	
	// Set the page title
	//$context['page_title'] = $txt['SMFPredictionLeague_admin'].' '.$txt['settings'];

	echo '<form action="', $scripturl, '?action=SMFPredictionLeague_admin;sa=' , $context['SMFPredictionLeague']['action'] , '" method="post" name="SMFPredictionLeague_admin">
			<input type="hidden" name="formaction"/>
			<table width="100%" cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td valign="top">
	';

	// Show appropriate block of template here depending on which sub action is being taken
	switch ($context['SMFPredictionLeague']['action']) {
	
		case 'update_fixtures' :
		case 'delete_fixtures' :
		case 'save_fixtures' :
		case 'fixtures' :
			
			// Show the tab template with fixtures being selected
			template_tab('fixtures');
			
			// Show the main fixtures template
			template_fixtures();
			break;
		
		case 'add_fixtures' :
		
			// Show the tab template with fixtures being selected
			template_tab('fixtures');

			template_add_fixtures();
			break;
			
		case 'outstanding_results':
		case 'save_results':
		case 'results':

			// Show the tab template with fixtures being selected
			template_tab('results');

			template_results();
			break;
		
		case 'support':
		
			// Show the tab template with fixtures being selected
			template_tab('support');
		
			template_support();
			break;
		
		case 'maintenance':
		
			// Show the tab template with fixtures being selected
			template_tab('maintenance');
		
			template_maintenance();
			break;

		case 'save_teams' :
		case 'teams' :
		
			// Show the tab template with fixtures being selected
			template_tab('teams');
		
			template_teams();
			break;
			
		case 'add_teams' :

			// Show the tab template with fixtures being selected
			template_tab('teams');

			template_add_teams();
			
			break;

		default :

			// Show the tab template with fixtures being selected
			template_tab('settings');

			template_settings();
			break;
	}
							

	
	if (isset($context['StatusUpdateText'])) {
		echo '			<br/>
						<table border="0" cellspacing="0" cellpadding="4" align="center" width="90%" class="tborder" >
							<tr class="titlebg">
								<td>' . $context['StatusUpdateText'] . '</td>
							</tr>
						</table>
		';
	}
	echo '
					</td>
				</tr>
			</table>
		</form>
	';
		
	if ($modSettings["SMFPredictionLeague_debugOn"] == 'on') {
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
		
}


function template_tab($current_action) {

	global $txt, $scripturl, $context, $settings;
	
	// Are we using right-to-left orientation?
	if ($context['right_to_left'])
	{
		$first = 'last';
		$last = 'first';
	}
	else
	{
		$first = 'first';
		$last = 'last';
	}

	echo '
							<table border="0" cellspacing="0" cellpadding="4" align="center" width="100%" class="tborder" >
								<tr class="titlebg">
									<td><a href="', $scripturl, '?action=SMFPredictionLeague_admin;save" onclick="return reqWin(this.href);" class="help"><img src="', $settings['images_url'], '/helptopics.gif" alt="" align="top" /> ', $txt['SMFPredictionLeague'] , ' ' , $txt['SMFPredictionLeagueAdmin_Titles']['title_settings'], '</a></td>
								</tr>
								<tr class="windowbg">
									<td class="smalltext" style="padding: 2ex;">';
	switch ($current_action) {
		case 'settings' :
			echo $txt['SMFPredictionLeague_TitlesBlurb']['title_settings_blurb'];
			break;
		case 'fixtures' :
			echo $txt['SMFPredictionLeague_TitlesBlurb']['title_fixtures_blurb'];
			break;
		case 'results' :
			echo $txt['SMFPredictionLeague_TitlesBlurb']['title_results_blurb'];
			break;
	}
	echo							'</td>
								</tr>
							</table>
							<table cellpadding="0" cellspacing="0" border="0" style="margin-left: 10px;">
								<tr>
									<td class="maintab_' , $first , '">&nbsp;</td>
								
									', ($current_action == 'settings' || $context['browser']['is_ie4']) ? '<td class="maintab_active_' . $first . '">&nbsp;</td>' : '' , '
									<td valign="top" class="maintab_' , $current_action == 'settings' ? 'active_back' : 'back' , '">
									<a href="', $scripturl, '?action=SMFPredictionLeague_admin;sa=settings">' , $txt['SMFPredictionLeagueAdmin_Titles']['title_settings'] , '</a>
									</td>' , $current_action == 'settings' ? '<td class="maintab_active_' . $last . '">&nbsp;</td>' : '' ,'
									
									', ($current_action == 'fixtures' || $context['browser']['is_ie4']) ? '<td class="maintab_active_' . $first . '">&nbsp;</td>' : '' , '
									<td valign="top" class="maintab_' , $current_action == 'fixtures' ? 'active_back' : 'back' , '">
									<a href="', $scripturl, '?action=SMFPredictionLeague_admin;sa=fixtures">' , $txt['SMFPredictionLeagueAdmin_Titles']['title_fixtures'] , '</a>
									</td>' , $current_action == 'fixtures' ? '<td class="maintab_active_' . $last . '">&nbsp;</td>' : '' ,'

									', ($current_action == 'results' || $context['browser']['is_ie4']) ? '<td class="maintab_active_' . $first . '">&nbsp;</td>' : '' , '
									<td valign="top" class="maintab_' , $current_action == 'results' ? 'active_back' : 'back' , '">
									<a href="', $scripturl, '?action=SMFPredictionLeague_admin;sa=results">' , $txt['SMFPredictionLeagueAdmin_Titles']['title_results'] , '</a>
									</td>' , $current_action == 'results' ? '<td class="maintab_active_' . $last . '">&nbsp;</td>' : '' ,'

									', ($current_action == 'teams' || $context['browser']['is_ie4']) ? '<td class="maintab_active_' . $first . '">&nbsp;</td>' : '' , '
									<td valign="top" class="maintab_' , $current_action == 'teams' ? 'active_back' : 'back' , '">
									<a href="', $scripturl, '?action=SMFPredictionLeague_admin;sa=teams">' , $txt['SMFPredictionLeagueAdmin_Titles']['title_teams'] , '</a>
									</td>' , $current_action == 'teams' ? '<td class="maintab_active_' . $last . '">&nbsp;</td>' : '' ,'

									', ($current_action == 'maintenance' || $context['browser']['is_ie4']) ? '<td class="maintab_active_' . $first . '">&nbsp;</td>' : '' , '
									<td valign="top" class="maintab_' , $current_action == 'maintenance' ? 'active_back' : 'back' , '">
									<a href="', $scripturl, '?action=SMFPredictionLeague_admin;sa=maintenance">' , $txt['SMFPredictionLeagueAdmin_Titles']['title_maintenance'] , '</a>
									</td>' , $current_action == 'maintenance' ? '<td class="maintab_active_' . $last . '">&nbsp;</td>' : '' ,'

									', ($current_action == 'support' || $context['browser']['is_ie4']) ? '<td class="maintab_active_' . $first . '">&nbsp;</td>' : '' , '
									<td valign="top" class="maintab_' , $current_action == 'support' ? 'active_back' : 'back' , '">
									<a href="', $scripturl, '?action=SMFPredictionLeague_admin;sa=support">' , $txt['SMFPredictionLeagueAdmin_Titles']['title_support'] , '</a>
									</td>' , $current_action == 'support' ? '<td class="maintab_active_' . $last . '">&nbsp;</td>' : '' ,'

									<td class="maintab_' , $last , '">&nbsp;</td>
								</tr>
							</table>
							<br/>
							<table border="0" cellspacing="0" cellpadding="4" align="center" width="90%" class="tborder" >';
}

?>
