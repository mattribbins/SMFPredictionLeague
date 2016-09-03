<?php

if (!defined('SMF'))
	die('Hacking attempt...');
	
function template_welcome() {
	global $txt, $scripturl;
	
	template_topTabs();
	
	
	echo '
		<table class="bordercolor" border="0" cellpadding="4" cellspacing="1" width="100%">
			<tr class="titlebg">
				<td align="left">' , $txt["SMFPredictionLeague_text"]["welcome"] , '</td>
			</tr>
			<tr>
				<td class="windowbg2" align="left">
					' , $txt["SMFPredictionLeague_welcome"]["welcome1"] , ' <a href="' . $scripturl . '?action=SMFPredictionLeague;sa=league_table">' . $txt["SMFPredictionLeague_welcome"]["welcomeLink"] . '</a>
					' , $txt["SMFPredictionLeague_welcome"]["welcome2"] , '
					<br/><br/>
					' , $txt["SMFPredictionLeague_welcome"]["welcome3"] , '
				</td>
			</tr>
		</table>
	';
	
}

function template_topTabs() {

	global $context, $scripturl;
	
	if (isset($context['tab_links'])) {

		echo '
			<table width="100%" cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td align="right" style="padding-right: 1ex;"><table cellpadding="0" cellspacing="0">
						<tr>
				<td class="mirrortab_first">&nbsp;</td>
		';
				
		foreach ($context['tab_links'] as $link) {

			if ($link['action'] == $context['current_subaction']) {
				echo '
				<td class="mirrortab_active_first">&nbsp;</td>
				<td valign="top" class="mirrortab_active_back">
					<a href="' , $scripturl . '?action=SMFPredictionLeague' , (!empty($link['action']) ? ';sa=' . $link['action'] : '') . '">', $link['label'], '</a>
				</td>
				<td class="mirrortab_active_last">&nbsp;</td>';
			} else {
				echo '
				<td valign="top" class="mirrortab_back">
					<a href="' , $scripturl . '?action=SMFPredictionLeague' , (!empty($link['action']) ? ';sa=' . $link['action'] : '') . '">', $link['label'], '</a>
				</td>
				';
			}
		}

		echo '
				<td class="mirrortab_last">&nbsp;</td>
			</tr>
			</table>
		';
	}
}

function template_notEnabled() {
	global $txt;
	echo '<table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td>' , $txt["SMFPredictionLeague_text"]["leagueNotEnabled"] , '</td></tr></table>';
}

// Template for when a user first enters the Prediction League area and needs to set themself up
function template_noUser() {
	global $txt, $scripturl; 
	echo '
		
			<table border="0" cellspacing="0" cellpadding="6" align="center" class="tborder">

				<tbody>
					<tr class="titlebg">
						<td class="windowbg2" colspan="3">' , $txt['SMFPredictionLeague_text']['application'] , '</td>
					</tr>
					<tr>
						<td class="windowbg2" colspan="3">' , $txt['SMFPredictionLeague_text']['notenabled'] , '</td>
					</tr>
					<tr>
						<td class="windowbg2"><nobr>' , $txt["SMFPredictionLeague_text"]["receivePMNotifications"] , ':</nobr></td>
						<td class="windowbg2"><nobr>
							<select name="ReceivePMNotification">
								<option value="1">' , $txt["SMFPredictionLeague_text"]["yes"] , '</option>
								<option value="0">' , $txt["SMFPredictionLeague_text"]["no"] , '</option>
							</select></nobr>
						</td>
						<td class="windowbg2" width="100%" class="smalltext"><i>' , $txt["SMFPredictionLeague_text"]["receivePMNotificationsHelp"] , '</i></td>
					</tr>
	';
	/* TODO
					<tr>
						<td class="windowbg2"><nobr>Default Score:</nobr></td>
						<td class="windowbg2">
							<nobr><input type="text" size="2" name="HomeDefault" value="0"/> - <input type="text" size="2" name="AwayDefault" value="0"/></nobr>
						</td>
						<td class="windowbg2" width="100%" class="smalltext"><i>This will specify the default prediction score that is used in case you forget to enter predictions</i></td>
	*/
	echo '
					<tr>
						<td class="windowbg2" colspan="3"><input type="submit" name="submit" value="' , $txt['SMFPredictionLeague_labels']['join'] , '" /></td>
					</tr>
				</tbody>
			</table>
	';
}

function template_home() {
	global $txt, $context, $settings, $user_settings, $scripturl, $modSettings;

	echo '
		<table width="100%">
			<tr>
				<td width="65%" valign="top">
					<table class="bordercolor" border="0" cellpadding="4" cellspacing="1" width="100%">
						<tr class="titlebg">
							<td align="left">' , $txt['SMFPredictionLeague_labels']['topPredictions'] , '</td>
						</tr>
						<tr class="windowbg">
							<td align="left">
								<table border=0>
									<tr>
										<td class="catbg3" align="left" width="5%"><b>' , $txt['SMFPredictionLeague_labels']['pos'] , '</b></td>
										<td class="catbg3" align="left" width="55%"><b>' , $txt['SMFPredictionLeague_labels']['username'] , '</b></td>
										<td class="catbg3" align="center" width="5%">&nbsp;</td>
										<td class="catbg3" align="center" width="5%"><b>' , $txt['SMFPredictionLeague_labels']['played'] , '</b></td>
										<td class="catbg3" align="center" width="5%"><b>' , $txt['SMFPredictionLeague_labels']['won'] . '</b></td>
	';
	if ($modSettings['SMFPredictionLeague_drawsEnabled'] == 'on') {
		echo '
										<td class="catbg3" align="center" width="5%"><b>' , $txt['SMFPredictionLeague_labels']['drawn'] . '</b></td>
		';
	}
	echo '								
										<td class="catbg3" align="center" width="5%"><b>' , $txt['SMFPredictionLeague_labels']['lost'] . '</b></td>
										<td class="catbg3" align="center" width="5%"><b>' , $txt['SMFPredictionLeague_labels']['points'] . '</b></td>
									</tr>
	';
	if (sizeof($context['SMFPredictionLeague']['topPositions']) > 0) {
		foreach($context['SMFPredictionLeague']['topPositions'] as $row) {
			echo '
										<tr>
											<td align="left">' , $row['Position'] , '</td>
											<td align="left" width="100%"><a href="' , $scripturl , '?action=' , $context['current_action'] , ';sa=user_info;id=' , $row['UserId']  , '"> 
										', isset($row['realName']) ? $row['realName'] : $row['real_name'] , '</a></td>

											<td align="center">' , $row['PosMove'] , '</td>
											<td align="center">' , $row['Played'] , '</td>
											<td align="center">' , $row['Won'] , '</td>
	';
	if ($modSettings['SMFPredictionLeague_drawsEnabled'] == 'on') {
		echo '
											<td align="center">' , $row['Drawn'] , '</td>
		';
	}
	echo '
											<td align="center">' , $row['Lost'] , '</td>
											<td align="center">' , $row['Points'] , '</td>
										</tr>
			';
		}
	} else {
	echo '								<tr><td colspan="8" align="left">' . $txt['SMFPredictionLeague_errors']['noMatches'] . '</td></tr>';
	}
	echo '
								</table>
							</td>
						</tr>
					</table>
					<br/>
					<table class="bordercolor" border="0" cellpadding="4" cellspacing="1" width="100%">
						<tr class="titlebg">
							<td align="left">' , $txt['SMFPredictionLeague_labels']['shoutbox'] , '</td>
						</tr>
						<tr class="windowbg">
							<td align="left">
								<div id="shoutarea" style="width:100%;height:150px;overflow:auto;"></div>
							</td>
						</tr>
						<tr class="windowbg">
							<td><nobr>
								<input type="hidden" name="shouter" value="' , !empty($user_settings['real_name']) ? $user_settings['real_name'] : $user_settings['realName'] ,  '"/>
								<input name="shouter_comment" type="text" style="width:75%" onkeypress="{if (event.keyCode==13){saveData(); return false;}}"/>
								<input type="button" name="submit" value="Shout!" onclick="saveData()"/>
								</nobr>
							</td>
						</tr>
					</table>
				</td>
				<td width="35%" valign="top">
					<table class="bordercolor" border="0" cellpadding="4" cellspacing="1" width="100%">
						<tr class="titlebg">
							<td align="left" colspan="5">' , $txt['SMFPredictionLeague_labels']['nextMatch'] , '</td>
						</tr>
						<tr class="windowbg">
							<td align="left">
								<table border="0">
	';
	
	if (sizeof($context['SMFPredictionLeague']['nextMatch']) > 0) {
		// Next match details
		foreach($context['SMFPredictionLeague']['nextMatch'] as $row) {
			echo '		
									<tr class="windowbg">
										<td align="left"><img src="' , $settings['default_images_url'] , '/pl_icons/' , $row['HomeTeamImage'] , '"/></td>
										<td align="left"><nobr><a href="' , $scripturl , '?action=' , $context['current_action'] , ';sa=team_detail;id=' , $row['HomeTeamId'] , '">' , $row['HomeTeamName'] , '</a></nobr></td>
										<td>v</td>
										<td align="left"><img src="' , $settings['default_images_url'] , '/pl_icons/' , $row['AwayTeamImage'] , '"/></td>
										<td width="100%" align="left"><a href="' , $scripturl , '?action=' , $context['current_action'] , ';sa=team_detail;id=' , $row['AwayTeamId'] , '">' , $row['AwayTeamName'] , '</a></td>
									</tr>
									<tr class="windowbg">
										<td colspan="5"><span class="smalltext">' , date("F j, Y, g:i a", $row['MatchDate']) , '</span></td>
									</tr>
			';
				
		}
		echo '
									<tr class="windowbg">
										<td width="100%" colspan="5">
											<table width="100%" border="0">
		';
		// Next match predictions
		foreach($context['SMFPredictionLeague']['nextMatchPredictions'] as $row) {
			echo '
												<tr>
													<td class="smalltext"><nobr>' , $row['Prediction'] , '</nobr></td>
													<td width="100%"><img src="' . $settings['default_images_url'] . '/pl_icons/red_dot.jpg" width="' , $row['Percentage'] , '%" height="10"/></td>
													<td class="smalltext"><nobr>' , $row['PredictionCount'] , '(' , $row['Percentage'] , '%)</nobr></td>
												</tr>
			';
		}
		echo '
											</table>
										</td>
									</tr>
		';
	} else {
		echo '<tr><td align="left">' . $txt['SMFPredictionLeague_errors']['noNextMatch'] . '</td></tr>';
	}
	echo '
								</table>
							</td>
						</tr>
					</table>
					<br/>
					<table class="bordercolor" border="0" cellpadding="4" cellspacing="1" width="100%">
						<tr class="titlebg">
							<td align="left">' , $txt['SMFPredictionLeague_labels']['topMovers'] , '</td>
						</tr>
						<tr class="windowbg">
							<td>
								<table>
	';
	// Top Movers
	if (sizeof($context['SMFPredictionLeague']['topMovers']) > 0) {
		foreach($context['SMFPredictionLeague']['topMovers'] as $row) {
			echo '
										<tr>
											<td align="left" width="100%"><a href="' , $scripturl , '?action=' , $context['current_action'] , ';sa=user_info;id=' , $row['UserId']  , '"> 
										', isset($row['realName']) ? $row['realName'] : $row['real_name'] , '</a></td>
											<td align="center">
			';
			if ($row['PosMove'] > 0) {
				echo '<font color="green">+' , $row['PosMove'] , '</font>';
			} elseif ($row['PosMove'] == 0) {
				echo '0';
			} else {
				echo '<font color="red">' , $row['PosMove'] , '</font>';
			}
			echo '
											</td>
										</tr>
			';
		}
	} else {
		echo '<tr><td align="left">' . $txt['SMFPredictionLeague_errors']['noMatches'] . '</td></tr>';
	}
		
	echo '
								</table>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	';
}

function template_statistics() {
	
	global $txt, $context, $settings, $scripturl;
		
	echo '
		<table width="100%">
			<tr>
				<td width="50%" valign="top" align="left">
					<table class="bordercolor" border="0" cellpadding="4" cellspacing="1" width="100%">
						<tr class="titlebg">
							<td align="left"><img src="' , $settings['default_images_url'] , '/stats_info.gif"/> ' , $txt['SMFPredictionLeague_labels']['overallStatistics'] , '</td>
						</tr>
						<tr class="windowbg">
							<td>
								<table border=0>
	';
	foreach($context['SMFPredictionLeague']['predictionStatsPage'] as $row){
		echo '
			<tr class="windowbg">
				<td align="left"><b>' , $txt['SMFPredictionLeague_labels']['predictionsMade'] , ':</b></td>
				<td align="left">' , $row['TotalPredictions'] , '</td>
			</tr>
			<tr class="windowbg">
				<td align="left"><b>' , $txt['SMFPredictionLeague_labels']['totalPoints'] , ':</b></td>
				<td align="left">' , $row['TotalPoints'] , '</td>
			</tr>
			<tr class="windowbg">
				<td align="left"><b>' , $txt['SMFPredictionLeague_labels']['totalWon'] , ':</b></td>
				<td align="left">' , $row['TotalWins'] , '</td>
			</tr>
			<tr class="windowbg">
				<td align="left"><b>' , $txt['SMFPredictionLeague_labels']['totalLost'] , ':</b></td>
				<td align="left">' , $row['TotalLosses'] , '</td>
			</tr>
			<tr class="windowbg">
				<td align="left"><b>' , $txt['SMFPredictionLeague_labels']['totalGoalsFor'] , ':</b></td>
				<td align="left">' , $row['TotalGoalsFor'] , '</td>
			</tr>
			<tr class="windowbg">
				<td align="left"><b>' , $txt['SMFPredictionLeague_labels']['totalGoalsAgainst'] , ':</b></td>
				<td align="left">' , $row['TotalGoalsAgainst'] , '</td>
			</tr>
			<tr class="windowbg">
				<td align="left"><b>' , $txt['SMFPredictionLeague_labels']['averagePointsPerGame'] , ':</b></td>
				<td align="left">' , $row['AveragePoints'] , '</td>
			</tr>
			<tr class="windowbg">
				<td align="left"><b>' , $txt['SMFPredictionLeague_labels']['maximumPointsScored'] , ':</b></td>
				<td align="left">' , $row['MaxPoints'] , '</td>
			</tr>

		';
	}
	echo '
								</table>
							</td>
						</tr>
					</table>
					
					
					<br/>
					<table class="bordercolor" border="0" cellpadding="4" cellspacing="1" width="100%">
						<tr class="titlebg">
							<td align="left"><img src="' , $settings['default_images_url'] , '/stats_posters.gif"/> ' , $txt['SMFPredictionLeague_labels']['topAveragePoints'] , '</td>
						</tr>
						<tr class="windowbg">
							<td>
								<table border="0">
									<tr>
										<td align="left"><b><nobr>Member</nobr></b></td>
										<td align="center"><b><nobr>Preds</nobr></b></td>
										<td align="center"><b><nobr>Tot Pts</nobr></b></td>
										<td align="center"><b><nobr>Avg Pts</nobr></b></td>
									</tr>
	';
	// Average Points
	foreach($context['SMFPredictionLeague']['averagePointsPage'] as $row) {
		echo '
									<tr>
										<td align="left" width="100%"><a href="' , $scripturl , '?action=' , $context['current_action'] , ';sa=user_info;id=' , $row['UserId']  , '"> 
									', isset($row['realName']) ? $row['realName'] : $row['real_name'] , '</a></td>
										<td align="center">
											' , $row['Predictions'] , '
										</td>
										<td align="center">
											' , $row['TotalPoints'] , '
										</td>
										<td align="center">
											' , $row['AveragePoints'] , '
										</td>
									</tr>
		';
	}
	echo '
								</table>								
							</td>
						</tr>
					</table>							
				</td>
				<td width="50%" valign="top">
					<table class="bordercolor" border="0" cellpadding="4" cellspacing="1" width="100%">
						<tr class="titlebg">
							<td align="left"><img src="' , $settings['default_images_url'] , '/stats_posters.gif"/> ' , $txt['SMFPredictionLeague_labels']['topMoversThisWeek'] , '</td>
						</tr>
						<tr class="windowbg">
							<td>
								<table border=0>
									<tr>
										<td align="left"><b>Member</b></td>
										<td align="center"><b>Move</b></td>
									</tr>
								
	';
	// Top Movers
	foreach($context['SMFPredictionLeague']['topMoversPage'] as $row) {
		echo '
									<tr>
										<td align="left" width="100%"><a href="' , $scripturl , '?action=' , $context['current_action'] , ';sa=user_info;id=' , $row['UserId']  , '"> 
									', isset($row['realName']) ? $row['realName'] : $row['real_name'] , '</a></td>
										<td align="center">
		';
		if ($row['PosMove'] > 0) {
			echo '<font color="green">+' , $row['PosMove'] , '</font>';
		} elseif ($row['PosMove'] == 0) {
			echo '0';
		} else {
			echo '<font color="red">' , $row['PosMove'] , '</font>';
		}
		echo '
										</td>
									</tr>
		';
	}
	echo '
								</table>								
							</td>
						</tr>
					</table>
					<br/>
					<table class="bordercolor" border="0" cellpadding="4" cellspacing="1" width="100%">
						<tr class="titlebg">
							<td align="left"><img src="' , $settings['default_images_url'] , '/stats_posters.gif"/> ' , $txt['SMFPredictionLeague_labels']['topLosersThisWeek'] , '</td>
						</tr>
						<tr class="windowbg">
							<td>
								<table border=0>
									<tr>
										<td align="left"><b>Member</b></td>
										<td align="center"><b>Move</b></td>
									</tr>
	';
	// Top Losers
	foreach($context['SMFPredictionLeague']['topLosersPage'] as $row) {
		echo '
									<tr>
										<td align="left" width="100%"><a href="' , $scripturl , '?action=' , $context['current_action'] , ';sa=user_info;id=' , $row['UserId']  , '"> 
									', isset($row['realName']) ? $row['realName'] : $row['real_name'] , '</a></td>
										<td align="center">
		';
		if ($row['PosMove'] > 0) {
			echo '<font color="green">+' , $row['PosMove'] , '</font>';
		} elseif ($row['PosMove'] == 0) {
			echo '0';
		} else {
			echo '<font color="red">' , $row['PosMove'] , '</font>';
		}
		echo '
										</td>
									</tr>
		';
	}
	echo '
								</table>								
							</td>
						</tr>
					</table>					
					<br/>
					<table class="bordercolor" border="0" cellpadding="4" cellspacing="1" width="100%">
						<tr class="titlebg">
							<td align="left"><img src="' , $settings['default_images_url'] , '/stats_posters.gif"/> ' , $txt['SMFPredictionLeague_labels']['weeksAtTop'] , '</td>
						</tr>
						<tr class="windowbg">
							<td>
								<table border=0>
									<tr>
										<td align="left"><b>Member</b></td>
										<td align="center"><b>Weeks</b></td>
									</tr>
	';
	// Top Losers
	foreach($context['SMFPredictionLeague']['weeksAtTopPage'] as $row) {
		echo '
									<tr>
										<td align="left" width="100%"><a href="' , $scripturl , '?action=' , $context['current_action'] , ';sa=user_info;id=' , $row['UserId']  , '"> 
									', isset($row['realName']) ? $row['realName'] : $row['real_name'] , '</a></td>
										<td align="center">
											' , $row['WeeksAtTop'] , '
										</td>
									</tr>
		';
	}
	echo '
								</table>								
							</td>
						</tr>
					</table>
				</td>
			</tr>
		
	';
	

	echo '
		</tbody>
	</table>
';
}

function template_outstanding_predictions() {
	global $txt, $context, $settings;

	echo '
			<table class="bordercolor" border="0" cellpadding="4" cellspacing="1" width="100%">
			<tbody>
					<tr>
						<td class="titlebg" colspan="7" align="left">
							' , $txt["SMFPredictionLeague_headings"]["outstandingPredictions"] , '
						</td>
					</tr>
					<tr>
						<td class="catbg3" align="left" width="5%">' , $txt["SMFPredictionLeague_labels"]["week"] , '</td>
						<td class="catbg3" align="left" width="20%">' , $txt["SMFPredictionLeague_labels"]["date"] , '</td>
						<td class="catbg3" align="left" width="30%">' , $txt["SMFPredictionLeague_labels"]["home"] , '</td>
						<td class="catbg3" align="left" width="5%">' , $txt["SMFPredictionLeague_labels"]["for"] , '</td>
						<td class="catbg3" width="5%">&nbsp;</td>
						<td class="catbg3" align="left" width="5%">' , $txt["SMFPredictionLeague_labels"]["against"] , '</td>
						<td class="catbg3" align="left" width="30%">' , $txt["SMFPredictionLeague_labels"]["away"] , '</td>
					</tr>';
	if(!empty($context['SMFPredictionLeague']['error'])) {
		echo '<tr><td colspan="11" class="windowbg" align="left">'.$context['SMFPredictionLeague']['error'].'</td></tr>';
	} elseif (isset($context['SMFPredictionLeague']['page'])) {
		
		foreach($context['SMFPredictionLeague']['page'] as $row){
			echo'	<tr>
						<td class="windowbg" valign="middle" align="left">
							', $row['WeekId'] , '
							<input type="hidden" name="week' , $row['MatchId'] , '" value="' , $row['WeekId'] , '"/>
						</td>
						<td class="windowbg" valign="middle" align="left">
							', date("F j, Y, g:i a", $row['MatchDate']) , '
						</td>
						<td class="windowbg" valign="middle" align="left">
							<img src="' , $settings['default_images_url'] , '/pl_icons/' , $row['HomeImage'] , '"/>&nbsp;
							', $row['HomeTeamName'] , '
						</td>
						<td class="windowbg" valign="middle" align="left">
							<select name="home' , $row['MatchId'] , '">' , template_score_dropdown(0) , '</select>
						</td>
						<td class="windowbg" valign="middle" align="left">
							vs.
						</td>
						<td class="windowbg" valign="middle" align="left">
							<select name="away' , $row['MatchId'] , '">' , template_score_dropdown(0) , '</select>
						</td>
						<td class="windowbg" valign="middle" align="left">
							<img src="' , $settings['default_images_url'] , '/pl_icons/' , $row['AwayImage'] , '"/>&nbsp;
							', $row['AwayTeamName'] , '
						</td>
			';
		}
		echo '		</tr>
		';
	}
	echo '
					<tr>
						<td class="titlebg" colspan="7">
							<input type="submit" value="' , $txt["SMFPredictionLeague_buttons"]["savePredictions"] , '" onclick="this.form.formaction.value=\'save_predictions\'"/> 
							<input type="submit" value="' , $txt["SMFPredictionLeague_buttons"]["cancel"] , '" onclick="this.form.formaction.value=\'predictions\'"/> 
						</td>
					</tr>
			</tbody>
		</table>
	';
}

function template_predictions() {
	global $txt, $context, $modSettings, $settings;
	
	echo '
			<table class="bordercolor" border="0" cellpadding="4" cellspacing="1" width="100%">
			<tbody>
					<tr>
						<td class="titlebg" colspan="9" align="left">
							' , $txt["SMFPredictionLeague_headings"]["predictionsForWeek"] , ' 
							' , template_week_dropdown(1) , '
						</td>
					</tr>
					<tr>

						<td class="catbg3" align="left" width="15%">' , $txt["SMFPredictionLeague_labels"]["date"] , '</td>
						<td class="catbg3" align="left" width="20%">' , $txt["SMFPredictionLeague_labels"]["home"] , '</td>
						<td class="catbg3" align="left" width="5%">' , $txt["SMFPredictionLeague_labels"]["for"] , '</td>
						<td class="catbg3" width="5%">&nbsp;</td>
						<td class="catbg3" align="left" width="5%">' , $txt["SMFPredictionLeague_labels"]["against"] , '</td>
						<td class="catbg3" align="left" width="20%">' , $txt["SMFPredictionLeague_labels"]["away"] , '</td>
						<td class="catbg3" align="left" width="10%">' , $txt["SMFPredictionLeague_labels"]["result"] , '</td>
						<td class="catbg3" align="left" width="5%">' , $txt["SMFPredictionLeague_labels"]["points"] , '</td>
						<td class="catbg3" align="left" width="5%">' , $txt["SMFPredictionLeague_labels"]["bonus"] , '</td>
					</tr>';
	if(!empty($context['SMFPredictionLeague']['error']))
		echo '<tr><td colspan="11" class="windowbg" align="left">'.$context['SMFPredictionLeague']['error'].'</td></tr>';
	$predictionsAvailable = 0;
	foreach($context['SMFPredictionLeague']['page'] as $row){
	
		// TODO: Do this with CSS only 
		if ($row['Points'] > 4) {
			$class = '';
			$bgcolor = ' bgcolor="' . $modSettings["SMFPredictionLeague_firstBackground"] . '"';
		} elseif ($row['Points'] > 2) {
			$class = '';
			$bgcolor = ' bgcolor="' . $modSettings["SMFPredictionLeague_secondBackground"] . '"';
		} elseif ($row['Points'] > 0) {
			$class = '';
			$bgcolor = ' bgcolor="' . $modSettings["SMFPredictionLeague_thirdBackground"] . '"';
		} else {
			$class = ' class="windowbg"';
			$bgcolor = '';
		}
		
		echo'	<tr>
					<td ' , $class , ' ' , $bgcolor , ' valign="middle" align="left">
						', date("F j, Y, g:i a", $row['MatchDate']) , '
					</td>
					<td ' , $class , ' ' , $bgcolor , ' valign="middle" align="left">
						<img src="' , $settings['default_images_url'] , '/pl_icons/' , $row['HomeImage'] , '"/>&nbsp;
						', $row['HomeTeamName'] , '
					</td>
					<td ' , $class , ' ' , $bgcolor , ' valign="middle" align="left">
						';
		$offsetTime = getOffsetTime($row['MatchDate'], $modSettings["SMFPredictionLeague_timeOffset"]);
		if (time() < $offsetTime && $row['Updated'] == 0) {
			$predictionsAvailable++;
			if ($row['PredHomeScore'] == null) {
				echo '
					<input type="hidden" name="null' , $row['MatchId'] , '"/>
				';
			}
			echo '
					<input type="hidden" name="fxwk' , $row['MatchId'] , '" value="' , $context['SMFPredictionLeague']['selected_week']  , '"/>
					<select name="home' , $row['MatchId'] , '">' , template_score_dropdown($row['PredHomeScore']) , '</select>';
		} else {
			echo $row['PredHomeScore'];
		}
		echo '
					</td>
					<td ' , $class , ' ' , $bgcolor , ' valign="middle" align="left">
						vs.
					</td>
					<td ' , $class , ' ' , $bgcolor , ' valign="middle" align="left">';
		if (time() < $offsetTime && $row['Updated'] == 0) {
			echo '<select name="away' , $row['MatchId'] , '">' , template_score_dropdown($row['PredAwayScore']) , '</select>';
		} else {
			echo $row['PredAwayScore'];
		}
		echo '
					</td>
					<td ' , $class , ' ' , $bgcolor , ' valign="middle" align="left">
						<img src="' , $settings['default_images_url'] , '/pl_icons/' , $row['AwayImage'] , '"/>&nbsp;
						', $row['AwayTeamName'] , '
					</td>
					<td ' , $class , ' ' , $bgcolor , ' valign="middle" align="left">
						', $row['HomeScore'] , ' - ' , $row['AwayScore'] , '
					</td>
					<td ' , $class , ' ' , $bgcolor , ' valign="middle" align="left">
						', $row['Points'] , '
					</td>
					<td ' , $class , ' ' , $bgcolor , ' valign="middle" align="left">
						', $row['Bonus'] , '
					</td>
				</tr>
		';
	}

	echo '	<td class="titlebg" colspan="9" align="left">';
	if ($predictionsAvailable > 0) {
		echo '	<input type="submit" value="' , $txt["SMFPredictionLeague_buttons"]["updatePredictions"] , '" onclick="this.form.formaction.value=\'update_predictions\'"/>';
	}
	echo '
				<input type="submit" value="' , $txt["SMFPredictionLeague_buttons"]["outstandingPredictions"] , '" onclick="this.form.formaction.value=\'outstanding_predictions\'"/>
			</tr>
			</tbody>
		</table>
	';
}

// Template for the League Table
function template_league() {
	global $txt, $context, $scripturl, $settings, $modSettings;
	
	echo '
		<table class="bordercolor" align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
			<tbody>
				<tr>
					<td width="80%" valign="top" class="windowbg2">
						<table class="bordercolor" border="0" cellpadding="4" cellspacing="1" width="100%">
							<tbody>
								<tr>
									<td class="titlebg" colspan="10" align="left">
										' , $txt['SMFPredictionLeague_labels']['league'] , ' :&nbsp;
										' , template_week_dropdown(1) , '
										<br/>
										' , template_positions_dropdown(1) , '
									</td>
								</tr>
								<tr>
									<td class="catbg3" align="left" width="5%"><b><a href="' , $scripturl , '?action=' , $context['current_action'] , ';sa=' , $context['current_subaction'] , ';sort=pos;dir=' , $context['SMFPredictionLeague']['sort_direction'] , '">', $context['SMFPredictionLeague']['sort_by'] == 'pos' ? '<img src="' . $settings['images_url'] . '/sort_' . $context['SMFPredictionLeague']['sort_direction'] . '.gif"/> ' : '' , $txt['SMFPredictionLeague_labels']['pos'] . '</a></b></td>
									<td class="catbg3" align="left" width="55%"><b><a href="' , $scripturl , '?action=' , $context['current_action'] , ';sa=' , $context['current_subaction'] , ';sort=user;dir=' , $context['SMFPredictionLeague']['sort_direction'] , '">', $context['SMFPredictionLeague']['sort_by'] == 'user' ? '<img src="' . $settings['images_url'] . '/sort_' . $context['SMFPredictionLeague']['sort_direction'] . '.gif"/> ' : '' , $txt['SMFPredictionLeague_labels']['username'] . '</a></b></td>
									<td class="catbg3" align="left" width="5%">&nbsp;</td>
									<td class="catbg3" align="left" width="5%"><b><a href="' , $scripturl , '?action=' , $context['current_action'] , ';sa=' , $context['current_subaction'] , ';sort=played;dir=' , $context['SMFPredictionLeague']['sort_direction'] , '">', $context['SMFPredictionLeague']['sort_by'] == 'played' ? '<img src="' . $settings['images_url'] . '/sort_' . $context['SMFPredictionLeague']['sort_direction'] . '.gif"/> ' : '' , $txt['SMFPredictionLeague_labels']['played'] . '</a></b></td>
									<td class="catbg3" align="left" width="5%"><b><a href="' , $scripturl , '?action=' , $context['current_action'] , ';sa=' , $context['current_subaction'] , ';sort=won;dir=' , $context['SMFPredictionLeague']['sort_direction'] , '">', $context['SMFPredictionLeague']['sort_by'] == 'won' ? '<img src="' . $settings['images_url'] . '/sort_' . $context['SMFPredictionLeague']['sort_direction'] . '.gif"/> ' : '' , $txt['SMFPredictionLeague_labels']['won'] . '</a></b></td>
	';
	if ($modSettings['SMFPredictionLeague_drawsEnabled'] == 'on') {
		echo '
									<td class="catbg3" align="left" width="5%"><b><a href="' , $scripturl , '?action=' , $context['current_action'] , ';sa=' , $context['current_subaction'] , ';sort=drawn;dir=' , $context['SMFPredictionLeague']['sort_direction'] , '">', $context['SMFPredictionLeague']['sort_by'] == 'drawn' ? '<img src="' . $settings['images_url'] . '/sort_' . $context['SMFPredictionLeague']['sort_direction'] . '.gif"/> ' : '' , $txt['SMFPredictionLeague_labels']['drawn'] . '</a></b></td>
		';
	}
	echo '
									<td class="catbg3" align="left" width="5%"><b><a href="' , $scripturl , '?action=' , $context['current_action'] , ';sa=' , $context['current_subaction'] , ';sort=lost;dir=' , $context['SMFPredictionLeague']['sort_direction'] , '">', $context['SMFPredictionLeague']['sort_by'] == 'lost' ? '<img src="' . $settings['images_url'] . '/sort_' . $context['SMFPredictionLeague']['sort_direction'] . '.gif"/> ' : '' , $txt['SMFPredictionLeague_labels']['lost'] . '</a></b></td>
									<td class="catbg3" align="left" width="5%"><b><a href="' , $scripturl , '?action=' , $context['current_action'] , ';sa=' , $context['current_subaction'] , ';sort=goalsfor;dir=' , $context['SMFPredictionLeague']['sort_direction'] , '">', $context['SMFPredictionLeague']['sort_by'] == 'goalsfor' ? '<img src="' . $settings['images_url'] . '/sort_' . $context['SMFPredictionLeague']['sort_direction'] . '.gif"/> ' : '' , $txt['SMFPredictionLeague_labels']['goalsfor'] . '</a></b></td>
									<td class="catbg3" align="left" width="5%"><b><a href="' , $scripturl , '?action=' , $context['current_action'] , ';sa=' , $context['current_subaction'] , ';sort=goalsagainst;dir=' , $context['SMFPredictionLeague']['sort_direction'] , '">', $context['SMFPredictionLeague']['sort_by'] == 'goalsagainst' ? '<img src="' . $settings['images_url'] . '/sort_' . $context['SMFPredictionLeague']['sort_direction'] . '.gif"/> ' : '' , $txt['SMFPredictionLeague_labels']['goalsagainst'] . '</a></b></td>
									<td class="catbg3" align="left" width="5%"><b><a href="' , $scripturl , '?action=' , $context['current_action'] , ';sa=' , $context['current_subaction'] , ';sort=points;dir=' , $context['SMFPredictionLeague']['sort_direction'] , '">', $context['SMFPredictionLeague']['sort_by'] == 'points' ? '<img src="' . $settings['images_url'] . '/sort_' . $context['SMFPredictionLeague']['sort_direction'] . '.gif"/> ' : '' , $txt['SMFPredictionLeague_labels']['points'] . '</a></b></td>
								</tr>';

	if(sizeof($context['SMFPredictionLeague']['page']) == 0)
		echo '<tr><td colspan="11" class="windowbg" align="left">' , $txt['SMFPredictionLeague_errors']['noStandingsForWeek'] , '</td></tr>';
		
	foreach($context['SMFPredictionLeague']['page'] as $row){
		// TODO: Do this with CSS only
		switch ($row['Position']) {
			case 1 :
				$class = '';
				$bgcolor = ' bgcolor="' . $modSettings["SMFPredictionLeague_firstBackground"] . '"';
				break;
			case 2 :
				$class = '';
				$bgcolor = ' bgcolor="' . $modSettings["SMFPredictionLeague_secondBackground"] . '"';
				break;
			case 3 :
				$class = '';
				$bgcolor = ' bgcolor="' . $modSettings["SMFPredictionLeague_thirdBackground"] . '"';
				break;
			default;
				$class = ' class="windowbg"';
				$bgcolor = '';
				break;
		}
		echo'	<tr>
					<td valign="middle" ' , $class , ' ' , $bgcolor , ' align="left">
						', $row['Position'],'
					</td>
					<td valign="middle" ' , $class , ' ' , $bgcolor , ' align="left">
						<a href="' , $scripturl , '?action=' , $context['current_action'] , ';sa=user_info;id=' , $row['UserId']  , '"> 
						', isset($row['realName']) ? $row['realName'] : $row['real_name'] , '</a>
					</td>
					<td valign="middle" ' , $class , ' ' , $bgcolor , ' align="left">';
		if ($row['PosMove'] < 0) {
			echo '<font color="red">' , $row['PosMove'] , '</td>';
		} elseif ($row['PosMove'] == 0) {
			echo '-';
		} else {
			echo '<font color="green">+' , $row['PosMove'] , '</td>';
		}
		echo '
					</td>
					<td valign="middle" ' , $class , ' ' , $bgcolor , ' align="left">
						', $row['Played'],'
					</td>
					<td valign="middle" ' , $class , ' ' , $bgcolor , ' align="left">
						', $row['Won'],'
					</td>
	';
	if ($modSettings['SMFPredictionLeague_drawsEnabled'] == 'on') {
		echo '
					<td valign="middle" ' , $class , ' ' , $bgcolor , ' align="left">
						', $row['Drawn'],'
					</td>
		';
	}
	echo '
					<td valign="middle" ' , $class , ' ' , $bgcolor , ' align="left">
						', $row['Lost'],'
					</td>
					<td valign="middle" ' , $class , ' ' , $bgcolor , ' align="left">
						', $row['GoalsFor'],'
					</td>
					<td valign="middle" ' , $class , ' ' , $bgcolor , ' align="left">
						', $row['GoalsAgainst'],'
					</td>
					<td valign="middle" ' , $class , ' ' , $bgcolor , ' align="left">
						', $row['Points'],'
					</td>

				</tr>';
						
	}

		echo '	<tr>
					<td class="titlebg" align="center" valign="middle" colspan="10">
						&nbsp;
					</td>
				</tr>
				</tbody></table>
			</td>
		</tr>
	</tbody></table>';

}

function template_team_details() {
	global $txt, $context, $settings;
	
	echo '
						<table class="bordercolor" border="0" cellpadding="4" cellspacing="1" width="100%">
							<tbody>
								<tr class="titlebg">
									<td align="left">' , $txt["SMFPredictionLeague_labels"]["week"] , '</td>
									<td align="left">' , $txt["SMFPredictionLeague_labels"]["date"] , '</td>
									<td>&nbsp;</td>
									<td align="left">' , $txt["SMFPredictionLeague_labels"]["home"] , '</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td align="left">' , $txt["SMFPredictionLeague_labels"]["away"] , '</td>
								</tr>
	';
	if (sizeof($context['SMFPredictionLeague']['page']) > 0) {
		foreach($context['SMFPredictionLeague']['page'] as $row){
			echo '					<tr class="windowbg">
										<td align="left">' , $row['WeekId'] , '</td>
										<td align="left"><nobr>' , date("F j, Y, g:i a", $row['MatchDate']) , '</nobr></td>
										<td align="left"><img src="' , $settings['default_images_url'] , '/pl_icons/' , $row['HomeTeamImage'] , '"/></td>
										<td align="left"><nobr>' , $row['HomeTeamName'] , '</nobr></td>
										<td align="center">' , $row['HomeScore'] , '</td>
										<td align="center">-</td>
										<td align="center">' , $row['AwayScore'] , '</td>
										<td align="left"><img src="' , $settings['default_images_url'] , '/pl_icons/' , $row['AwayTeamImage'] , '"/></td>
										<td width="100%" align="left"><nobr>' , $row['AwayTeamName'] , '</nobr></td>
										
										
									</tr>';
		}
	} else {
		echo ' 						<tr class="windowbg"><td colspan="9" align="left">' , $txt['SMFPredictionLeague_errors']['noMatches'] , '</td></tr>';
	}
	echo '
							</tbody>
						</table>
	';
}

function template_user_info() {
	global $txt, $context, $settings, $modSettings;
	
	// Determine whether this is for another user or the current user
	$otherUser = false;
	if (isset($_GET['id'])) {
		$otherUser = true;
	} 
	
	// TODO: constant/configurable
	$scale = 5; // Number of scale points
	$offset = 12; // Offset for trying to line up bar to axis

	// Only generate the position subpage output if this user has some data
	$positionHtml = '';
	if (sizeof($context['SMFPredictionLeague']['subpage']) > 0) {
	
		// Get the maximum number of users in the league, this is used for determining the maximum on the axis
		$maxUsers = $context['SMFPredictionLeague']['maxUsers'];
		
		// If there are less than 5 users in the league, set the default axis to 10 with intervals of 2
		if ($maxUsers < $scale) {
			$endPoint = 10;
			$axisPoint = 2;
		} else {
		
			// Otherwise get the endpoint, which is either the maximum users if the modulus is nothing 
			if (($maxUsers % $scale) == 0) {
				$endPoint = $maxUsers;
			// Or the next scale above the maximum users
			} else {
				$endPoint = ((floor($maxUsers / $scale)+1)*5);
			}
			
			// Set the axis points to be the maximum divided by the scale we have selected
			$axisPoint = $endPoint / $scale;
		}
		
		// Build the position HTML first, this will be inserted later
		$positionHtml = '<table border="0" cellpadding="5" cellspacing="1" class="tborder" width="100%"><tr><td bgcolor="white" class="titlebg2">Position History</td></tr><tr><td bgcolor="white"><table border="0" cellpadding="0" cellspacing="1"><tr><td align="right" valign="bottom" height="20px"><span class="smalltext">1-</span></td>';
		foreach ($context['SMFPredictionLeague']['subpage'] as $row) {
		
			// Calculate the height for this bar
			if ($row["Position"] != 1) {
				$barHeight = 100 - ((100 / $endPoint) * $row["Position"]);
			} else {
				$barHeight = 100;
			}
			
			// Build the HTML for this bar
			$positionHtml .= '<td width="14px" valign="bottom" rowspan="6" align="center"><img src="' . $settings['default_images_url'] . '/pl_icons/red_dot.jpg" width="10" height="' . ($barHeight + $offset) . '"/></td>';
		} 
		$positionHtml .= '</tr>';
		
		// This next section builds the axis
		$i = 1;
		while ($i < $scale+1) {
			$positionHtml .= '<tr><td valign="bottom" height="20px" align="right"><span class="smalltext">' . ($i * $axisPoint) . '-</span></td></tr>';
			$i++;
		}
		$positionHtml .= '<tr><td>&nbsp;</td>';
		foreach ($context['SMFPredictionLeague']['subpage'] as $row) {
			$positionHtml .= '<td align="center"><span class="smalltext">' . $row["WeekId"] . '</span></td>';
		}

		$positionHtml .= '</tr></table></td></tr></table>';
	} 
	
	// Should only leep the once, othewise there is a problem
	foreach($context['SMFPredictionLeague']['page'] as $row){
		echo '
						<table class="bordercolor" border="0" cellpadding="4" cellspacing="1" width="100%">
							<tbody>
								<tr class="titlebg">
									<td colspan="3" align="left">
										<img src="' , $settings['default_images_url'] , '/icons/profile_sm.gif"/> ' , $txt['SMFPredictionLeague_headings']['userInformationFor'] , '	 ' , isset($row['realName']) ? $row['realName'] : $row['real_name'] , '
									</td>
								</tr>
								<tr class="windowbg">
									<td align="left"><nobr><b>' , $txt["SMFPredictionLeague_labels"]["lastPrediction"] , ':</nobr></b></td>
									<td align="left" width="100%" colspan="2">
										' , empty($row['LastPredictionDate']) ? 'Never' : date("F j, Y, g:i a", $row['LastPredictionDate']) , '
									</td>
								</tr>
								<tr class="windowbg">
									<td align="left"><nobr><b>' , $txt["SMFPredictionLeague_labels"]["predictionsMade"] , ':</nobr></b></td>
									<td align="left" width="100%" colspan="2">
										' , empty($row['Predictions']) ? '0' : $row['Predictions'] , '
									</td>
								</tr>
		';
		// Only show member settings if selected member is current user
		if ($otherUser == false) {
			echo '
								<tr class="windowbg">
									<td align="left"><nobr><b>' , $txt["SMFPredictionLeague_labels"]["pmNotifications"] , ':</nobr></b></td>
									<td align="left" colspan="2">
										<select name="PMNotification">';
			
			if ($row['ReceivePMs'] == 1) {
				echo '
												<option value="1" selected="selected">Yes</option>
												<option value="0">No</option>
				';
			} else {
				echo '
												<option value="1">Yes</option>
												<option value="0" selected="selected">No</option>
				';
			}
			echo '									
											
										</select>
									</td>
								</tr>
			';
		}
		// Only show the save button if viewing own user details
		if ($otherUser == false) {
			echo '
								<tr class="windowbg">
									<td colspan="3" align="left">
										<input type="submit" value="' , $txt["SMFPredictionLeague_buttons"]["save"] , '" onclick="this.form.formaction.value=\'update_userInfo\'"/>
									</td>
								</tr>
				';
		}
		echo '
								<tr class="titlebg">
									<td colspan="3" align="left">
										<img src="' , $settings['default_images_url'] , '/stats_posters.gif"/> ' , $txt['SMFPredictionLeague_headings']['userStatistics'] , '
									</td>
									
								</tr>
								<tr class="windowbg">
									<td align="left"><b>' , $txt["SMFPredictionLeague_labels"]["currentPosition"] , ':</b></td>
									<td align="left"><nobr>' , empty($row['Position']) ? '0' :  $row['Position'] , '</nobr></td>
									<td rowspan="8" width="100%" align="left" valign="top">' , $positionHtml , '</td>
								</tr>
								<tr class="windowbg">
									<td align="left"><b>' , $txt["SMFPredictionLeague_labels"]["totalPlayed"] , ':</b></td>
									<td align="left"><nobr>' , empty($row['Played']) ? '0' : $row['Played'] , ' ' , $txt["SMFPredictionLeague_misc"]["games"] , '</nobr></td>
								</tr>
								<tr class="windowbg">
									<td align="left"><b>' , $txt["SMFPredictionLeague_labels"]["totalWon"] , ':</b></td>
									<td align="left"><nobr>'  , empty($row['Won']) ? '0 ' : $row['Won'] , ' ' , $txt["SMFPredictionLeague_misc"]["matches"] , '&nbsp;&nbsp;&nbsp;</nobr></td>
								</tr>
	';
	if ($modSettings['SMFPredictionLeague_drawsEnabled'] == 'on') {
		echo '
								<tr class="windowbg">
									<td align="left"><b>' , $txt["SMFPredictionLeague_labels"]["totalDrawn"] , ':</b></td>
									<td align="left"><nobr>'  , empty($row['Drawn']) ? '0 ' : $row['Drawn'] ,  ' ' , $txt["SMFPredictionLeague_misc"]["matches"] , '</nobr></td>
								</tr>
		';
	}
	echo '
								<tr class="windowbg">
									<td align="left"><b>' , $txt["SMFPredictionLeague_labels"]["totalLost"] , ':</b></td>
									<td align="left"><nobr>'  , empty($row['Lost']) ? '0 ' : $row['Lost'] ,  ' ' , $txt["SMFPredictionLeague_misc"]["matches"] , '</nobr></td>
								</tr>
								<tr class="windowbg">
									<td align="left"><b>' , $txt["SMFPredictionLeague_labels"]["totalGoalsFor"] , ':</b></td>
									<td align="left"><nobr>'  , empty($row['GoalsFor']) ? '0 ' : $row['GoalsFor'] ,  ' ' , $txt["SMFPredictionLeague_misc"]["goals"] , '</nobr></td>
								</tr>
								<tr class="windowbg">
									<td align="left"><nobr><b>' , $txt["SMFPredictionLeague_labels"]["totalGoalsAgainst"] , ':</b></nobr></td>
									<td align="left"><nobr>'  , empty($row['GoalsAgainst']) ? '0 ' : $row['GoalsAgainst'] ,  ' ' , $txt["SMFPredictionLeague_misc"]["goals"] , '</nobr></td>
								</tr>
								<tr class="windowbg">
									<td align="left"><b>' , $txt["SMFPredictionLeague_labels"]["totalPoints"] , ':</b></td>
									<td align="left"><nobr>'  , empty($row['Points']) ? '0 ' : $row['Points'] , ' ' , $txt["SMFPredictionLeague_misc"]["points"]  , '</nobr></td>
								</tr>
							</tbody>
						</table>
		';
	}

}

// Template for the Team Listing
function template_teams() {
	global $txt, $context, $settings, $scripturl, $modSettings;
	
	echo '
		<table class="bordercolor" align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
			<tbody>
				<tr>
					<td width="80%" valign="top" class="windowbg2">
						<table class="bordercolor" border="0" cellpadding="4" cellspacing="1" width="100%">
							<tbody>
								<tr>
									<td class="titlebg" align="left" valign="middle" colspan="10">
										'.$txt['SMFPredictionLeague_labels']['teams'].'
									</td>
								</tr>
								<tr>
									<!-- <td class="catbg3" align="left" width="5%">'.$txt['SMFPredictionLeague_labels']['pos'].'</td> -->
									<td class="catbg3" align="left" width="5%">&nbsp;</td>
									<td class="catbg3" align="left" width="5%">&nbsp;</td>
									<td class="catbg3" align="left" width="5%"><b><a href="' , $scripturl , '?action=' , $context['current_action'] , ';sa=' , $context['current_subaction'] , ';sort=teamname;dir=' , $context['SMFPredictionLeague']['sort_direction'] , '">', $context['SMFPredictionLeague']['sort_by'] == 'teamname' ? '<img src="' . $settings['images_url'] . '/sort_' . $context['SMFPredictionLeague']['sort_direction'] . '.gif"/> ' : '' , $txt['SMFPredictionLeague_labels']['teamname'] . '</a></b></td>
									<td class="catbg3" align="left" width="5%"><b><a href="' , $scripturl , '?action=' , $context['current_action'] , ';sa=' , $context['current_subaction'] , ';sort=played;dir=' , $context['SMFPredictionLeague']['sort_direction'] , '">', $context['SMFPredictionLeague']['sort_by'] == 'played' ? '<img src="' . $settings['images_url'] . '/sort_' . $context['SMFPredictionLeague']['sort_direction'] . '.gif"/> ' : '' , $txt['SMFPredictionLeague_labels']['played'] . '</a></b></td>
									<td class="catbg3" align="left" width="5%"><b><a href="' , $scripturl , '?action=' , $context['current_action'] , ';sa=' , $context['current_subaction'] , ';sort=won;dir=' , $context['SMFPredictionLeague']['sort_direction'] , '">', $context['SMFPredictionLeague']['sort_by'] == 'won' ? '<img src="' . $settings['images_url'] . '/sort_' . $context['SMFPredictionLeague']['sort_direction'] . '.gif"/> ' : '' , $txt['SMFPredictionLeague_labels']['won'] . '</a></b></td>
	';
	if ($modSettings['SMFPredictionLeague_drawsEnabled'] == 'on') {
		echo '
									<td class="catbg3" align="left" width="5%"><b><a href="' , $scripturl , '?action=' , $context['current_action'] , ';sa=' , $context['current_subaction'] , ';sort=drawn;dir=' , $context['SMFPredictionLeague']['sort_direction'] , '">', $context['SMFPredictionLeague']['sort_by'] == 'drawn' ? '<img src="' . $settings['images_url'] . '/sort_' . $context['SMFPredictionLeague']['sort_direction'] . '.gif"/> ' : '' , $txt['SMFPredictionLeague_labels']['drawn'] . '</a></b></td>
		';
	}
	echo '
									<td class="catbg3" align="left" width="5%"><b><a href="' , $scripturl , '?action=' , $context['current_action'] , ';sa=' , $context['current_subaction'] , ';sort=lost;dir=' , $context['SMFPredictionLeague']['sort_direction'] , '">', $context['SMFPredictionLeague']['sort_by'] == 'lost' ? '<img src="' . $settings['images_url'] . '/sort_' . $context['SMFPredictionLeague']['sort_direction'] . '.gif"/> ' : '' , $txt['SMFPredictionLeague_labels']['lost'] . '</a></b></td>
									<td class="catbg3" align="left" width="5%"><b><a href="' , $scripturl , '?action=' , $context['current_action'] , ';sa=' , $context['current_subaction'] , ';sort=goalsfor;dir=' , $context['SMFPredictionLeague']['sort_direction'] , '">', $context['SMFPredictionLeague']['sort_by'] == 'goalsfor' ? '<img src="' . $settings['images_url'] . '/sort_' . $context['SMFPredictionLeague']['sort_direction'] . '.gif"/> ' : '' , $txt['SMFPredictionLeague_labels']['goalsfor'] . '</a></b></td>
									<td class="catbg3" align="left" width="5%"><b><a href="' , $scripturl , '?action=' , $context['current_action'] , ';sa=' , $context['current_subaction'] , ';sort=goalsagainst;dir=' , $context['SMFPredictionLeague']['sort_direction'] , '">', $context['SMFPredictionLeague']['sort_by'] == 'goalsagainst' ? '<img src="' . $settings['images_url'] . '/sort_' . $context['SMFPredictionLeague']['sort_direction'] . '.gif"/> ' : '' , $txt['SMFPredictionLeague_labels']['goalsagainst'] . '</a></b></td>
									<td class="catbg3" align="left" width="5%"><b><a href="' , $scripturl , '?action=' , $context['current_action'] , ';sa=' , $context['current_subaction'] , ';sort=points;dir=' , $context['SMFPredictionLeague']['sort_direction'] , '">', $context['SMFPredictionLeague']['sort_by'] == 'points' ? '<img src="' . $settings['images_url'] . '/sort_' . $context['SMFPredictionLeague']['sort_direction'] . '.gif"/> ' : '' , $txt['SMFPredictionLeague_labels']['points'] . '</a></b></td>
								</tr>';

	if(!empty($context['SMFPredictionLeague']['error']))
		echo '<tr><td colspan="2" class="windowbg" align="left">'.$context['SMFPredictionLeague']['error'].'</td></tr>';
	
	$teamPosition = 1;
	foreach($context['SMFPredictionLeague']['page'] as $row){
		echo'	<tr>
					<td class="windowbg" valign="middle" align="left">
						<nobr>' , $teamPosition , '</nobr>
					</td>
					<td class="windowbg" valign="middle" align="left">
						<img src="' , $settings['default_images_url'] , '/pl_icons/' , $row['Image'] , '"/>
					</td>
					<td class="windowbg" valign="middle" align="left">
						<nobr><a href="' , $scripturl , '?action=' , $context['current_action'] , ';sa=team_detail;id=' , $row['TeamId'] , '">' , $row['Name'] , '</a></nobr>
					</td>
					<td class="windowbg" valign="middle" align="left">
						<nobr>' , $row['Played'] , '</nobr>
					</td>
					<td class="windowbg" valign="middle" align="left">
						<nobr>' , $row['Won'] , '</nobr>
					</td>
	';
	if ($modSettings['SMFPredictionLeague_drawsEnabled'] == 'on') {
		echo '
					<td class="windowbg" valign="middle" align="left">
						<nobr>' , $row['Drawn'] , '</nobr>
					</td>
		';
	}
	echo '
					<td class="windowbg" valign="middle" align="left">
						<nobr>' , $row['Lost'] , '</nobr>
					</td>
					<td class="windowbg" valign="middle" align="left">
						<nobr>' , $row['GoalsFor'] , '</nobr>
					</td>
					<td class="windowbg" valign="middle" align="left">
						<nobr>' , $row['GoalsAgainst'] , '</nobr>
					</td>
					<td class="windowbg" valign="middle" align="left">
						<nobr>' , $row['Points'] , '</nobr>
					</td>
				</tr>';
		$teamPosition++;
	}

	echo '	<tr>
					<td class="titlebg" align="center" valign="middle" colspan="10">
						&nbsp;
					</td>
				</tr>
				</tbody></table>
			</td>
		</tr>
	</tbody></table>';

}

function template_week_dropdown($autoChange = 0) {

	global $context;

	if ($autoChange == 0) {
		$dropdownlist = '<select name="week">';
	} else {
		$dropdownlist = '<select name="week" onchange="this.form.formaction.value=\'' . $context['current_subaction'] . '\';this.form.submit();">';
	}
	if (isset($context['SMFPredictionLeague']['weeks'])) {
		foreach($context['SMFPredictionLeague']['weeks'] as $row) { 
			if (isset($context['SMFPredictionLeague']['selected_week']) && $context['SMFPredictionLeague']['selected_week'] == $row['WeekId']) {
				$dropdownlist .= '<option selected="selected"> ' . $row['WeekId'] . '</option>';
			} else {
				$dropdownlist .= '<option> ' . $row['WeekId'] . '</option>';
			}
		}
	}
	$dropdownlist .= '</select>';
	
	return $dropdownlist;

}

function template_team_dropdown($selectedTeamId) {
	global $context;
	$dropdownlist = '';
	
	//  Output dropdown containing team selection
	if (isset($context['SMFPredictionLeague']['teams'])) {
		foreach($context['SMFPredictionLeague']['teams'] as $row) { 
			if ($row['TeamId'] == $selectedTeamId) {
				$dropdownlist .= '<option selected="selected" value="' . $row['TeamId'] . '"> ' . $row['Name'] . '</option>';
			} else {
				$dropdownlist .= '<option value="' . $row['TeamId'] . '"> ' . $row['Name'] . '</option>';
			}
		}
	}
	return $dropdownlist;
}	

function template_score_dropdown($selectedScore) {
	global $context, $modSettings;
	$dropdownlist = '';
	
	$maxScore = isset($modSettings['SMFPredictionLeague_maximumPredictionScore']) ? $modSettings['SMFPredictionLeague_maximumPredictionScore'] : 10;
	
	//  Output dropdown containing team selection
	for ($counter = 0; $counter < $maxScore + 1; $counter++) {
		if ($counter == $selectedScore) {
			$dropdownlist .= '<option selected="selected" value="' . $counter . '"> ' . $counter . '</option>';
		} else {
			$dropdownlist .= '<option value="' . $counter . '"> ' . $counter . '</option>';
		}
	}
	return $dropdownlist;
}	

function template_positions_dropdown($selectedPositions) {
	global $context, $modSettings, $txt;
	$dropdownlist = '';
	
	// How many users to show per page
	$split = $modSettings["SMFPredictionLeague_usersPerPage"];
	
	if (isset($context['SMFPredictionLeague']['userCount']) && $context['SMFPredictionLeague']['userCount'] > 0 && $context['SMFPredictionLeague']['userCount'] >= $split) {

		$dropdownlist = $txt['SMFPredictionLeague_labels']['showingPositions'] . ': <select name="positions" onchange="this.form.submit();">';
	
		//  Output dropdown containing positions selection
		$firstValue = 1;
		for ($counter = 1; $counter <= $context['SMFPredictionLeague']['userCount']; $counter++) {
			if ($counter >= $split || $counter == $context['SMFPredictionLeague']['userCount']) {
				$context["SMFPredictionLeague_debugOutput"] .= '<br/>' . $counter;
				$secondValue = $counter;
				$key = $firstValue . '_' . $secondValue;
				if ($context['SMFPredictionLeague']['positions'] == $key) {
					$dropdownlist .= '<option value="' . $key . '" selected="selected">' . $firstValue . ' - ' . $secondValue . '</option>';
				} else {
					$dropdownlist .= '<option value="' . $key . '">' . $firstValue . ' - ' . $secondValue . '</option>';
				}
				$firstValue = $counter + 1;
				$split = $split + $modSettings["SMFPredictionLeague_usersPerPage"];
			} 
		}
		
		$dropdownlist .= '</select>';
	}
	return $dropdownlist;
}
?>
