<?php
/**********************************************************************************
* SMFPredictionLeague.template.php                                                              *
***********************************************************************************
*                                                                                 *
*  Template file for "First Mod" modification.                                    *
*                                                                                 *
**********************************************************************************/
if (!defined('SMF'))
	die('Hacking attempt...');
	
// Include the common template file that is used for both 1.1 and 2.x versions of SMF
global $settings;
require_once($settings["theme_dir"] . '/SMFPredictionLeagueCommon.template.php');
	
function template_main()
{
	global $context, $txt, $user_info, $scripturl, $modSettings, $user_settings;

	echo '
	<form action="' . $scripturl . '?action=' . $context['current_action'] . ';sa=' . $context['current_subaction'] . '" method="post">
		<input type="hidden" name="formaction"/>
		<div id="mypopup" name="mypopup" style="position: absolute; width: 250px; height: 200px; display: none; background: #ddd; border: 1px solid #000; right: 0px; top: 500px">
			<p>My content that will be displayed in the popup</p>
			<input type="button" value=" Close me! " onClick="document.getElementById(\'mypopup\').style.display = \'none\'"> 
		</div> 
	';
	
	if ($modSettings['SMFPredictionLeague_enabled'] == 'on' && $user_settings['pl_enabled'] == 1) {
		
		echo '
			<table width="100%" cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td align="right" style="padding-right: 1ex;"><table cellpadding="0" cellspacing="0">
						<tr>
				<td class="mirrortab_first">&nbsp;</td>
		';
				
		foreach ($context['tab_links'] as $link) {

			if ($link['action'] == $context['SMFPredictionLeague']['action']) {
				echo '
				<td class="mirrortab_active_first">&nbsp;</td>
				<td valign="top" class="mirrortab_active_back">
					<a href="' . $scripturl . '?action=SMFPredictionLeague' . (!empty($link['action']) ? ';sa=' . $link['action'] : '') . '">', $link['label'], '</a>
				</td>
				<td class="mirrortab_active_last">&nbsp;</td>';
			} else {
				echo '
				<td valign="top" class="mirrortab_back">
					<a href="' . $scripturl . '?action=SMFPredictionLeague' . (!empty($link['action']) ? ';sa=' . $link['action'] : '') . '">', $link['label'], '</a>
				</td>
				';
			}
		}

		echo '
				<td class="mirrortab_last">&nbsp;</td>
			</tr>
			</table>
		';
		
	
		switch ($context['SMFPredictionLeague']['action']) {
			case 'league_table' :
				template_league();
				break;
			case 'predictions' :
				template_predictions();
				break;
			case 'user_info' :
				template_user_info();
				break;
			case 'teams' :
				template_teams();
				break;
			case 'statistics' :
				template_statistics();
				break;
			case 'outstanding_predictions' :
				template_outstanding_predictions();
				break;
			case 'team_details' :
				template_team_details();
				break;
			default :
				template_home();
				break;
		}	
	} elseif ($user_settings['pl_enabled'] == 0 && $_POST['submit'] == 'Join') {
		template_welcome();
	} elseif ($user_settings['pl_enabled'] == 0) {
		template_noUser();
	} else {
		template_notEnabled();
	}
	
	echo '</form>';

	if ($modSettings["SMFPredictionLeague_debugOn"] == 'on' && $context['user']['is_admin'] == 1		) {
		echo '	<br/>
						<table border="0" cellspacing="0" cellpadding="6" align="center" class="tborder" width="100%">
							<tr class="titlebg">
								<td class="windowbg2" align="left">' . $txt["SMFPredictionLeague_misc"]["debugTitle"] . '</td>
							</tr>
							<tr class="windowbg2">
								<td align="left"><span class="smalltext">' . $txt["SMFPredictionLeague_misc"]["debugBlurb"] . '</span></td>
							</tr>
							<tr><td class="windowbg2" align="left">' . $context["SMFPredictionLeague_debugOutput"] . '</td></tr>
						</table>
		';
	}
	echo ' <table width="100%"><tr><td align="center"><a href="http://www.smfmodding.com" title="Free SMF Mods" target="_blank" class="smalltext">SMFPredictionLeague ' , isset($modSettings["SMFPredictionLeague_version"]) ? $modSettings["SMFPredictionLeague_version"] : '' , ' &copy; 2008, SMFModding</a></td></tr></table>';

}

?>