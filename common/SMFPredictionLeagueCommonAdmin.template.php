<?php

if (!defined('SMF'))
	die('Hacking attempt...');
	
$context['html_headers'] .= '<script type="text/javascript" src="'.$settings['default_theme_url'].'/scripts/pl_scripts/calendar.js"></script>';
	
	
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

function template_settings() {

	global $modSettings, $txt;

	echo '
								<tr class="titlebg">
									<td class="catbg3" colspan="3">' . $txt['SMFPredictionLeagueAdmin_Headings']['generalSettings'] . '</td>
								</tr>
								<tr>
									<td class="windowbg2"><nobr>' . $txt['SMFPredictionLeagueAdmin_Headings']['predictionLeagueEnabled'] . ':</nobr></td>
									<td class="windowbg2"><input type="checkbox" name="enabled" ' . ($modSettings['SMFPredictionLeague_enabled'] ? 'checked="checked"' : ''). '"/></td>
									<td width="100%" class="windowbg2"><span class="smalltext">' . $txt['SMFPredictionLeague_help']['enabledHelp'] . '</span></td>
								</tr>
								<tr>
									<td class="windowbg2"><nobr>' . $txt['SMFPredictionLeagueAdmin_Headings']['adminDebugEnabled'] . ':</nobr></td>
									<td class="windowbg2"><input type="checkbox" name="debugOn" ' . ($modSettings['SMFPredictionLeague_debugOn'] ? 'checked="checked"' : ''). '"/></td>
									<td width="100%" class="windowbg2"><span class="smalltext">' . $txt['SMFPredictionLeague_help']['adminDebugOnHelp'] . '<span></td>
								</tr>
								<tr>
									<td class="windowbg2"><nobr>' . $txt['SMFPredictionLeagueAdmin_Headings']['pmsEnabled'] . ':</nobr></td>
									<td class="windowbg2"><input type="checkbox" name="pmsOn" ' . ($modSettings['SMFPredictionLeague_pmsOn'] ? 'checked="checked"' : ''). '"/></td>
									<td width="100%" class="windowbg2"><span class="smalltext">' . $txt['SMFPredictionLeague_help']['pmsOnHelp'] . '<span></td>
								</tr>
								<tr>
									<td class="windowbg2"><nobr>' . $txt['SMFPredictionLeagueAdmin_Headings']['drawsEnabled'] . ':</nobr></td>
									<td class="windowbg2"><input type="checkbox" name="drawsEnabled" ' . ($modSettings['SMFPredictionLeague_drawsEnabled'] ? 'checked="checked"' : ''). '"/></td>
									<td width="100%" class="windowbg2"><span class="smalltext">' . $txt['SMFPredictionLeague_help']['drawsEnabledHelp'] . '<span></td>
								</tr>
								<tr>
									<td class="windowbg2"><nobr>' . $txt['SMFPredictionLeagueAdmin_Headings']['timeOffset'] . ':</nobr></td>
									<td class="windowbg2">
										<input type="text" name="timeOffset" size="5" maxlength="2" value="' . $modSettings['SMFPredictionLeague_timeOffset'] . '"/>
									</td>
									<td width="100%" class="windowbg2"><span class="smalltext">' . $txt['SMFPredictionLeague_help']['timeOffsetHelp'] . '</span></td>
								</tr>
								<tr>
									<td class="windowbg2"><nobr>' . $txt['SMFPredictionLeagueAdmin_Headings']['serverTime'] . ':</nobr></td>
									<td class="windowbg2" colspan="2">' , date("F j, g:i a", time()) , '</td>
								</tr>
								<tr>
									<td class="windowbg2"><nobr>' . $txt['SMFPredictionLeagueAdmin_Headings']['serverTimeAndOffset'] . ':</nobr></td>
									<td class="windowbg2"><nobr>' , date("F j, g:i a", getOffsetTime(time(),-$modSettings['SMFPredictionLeague_timeOffset'])) , '</nobr></td>
									<td width="100%" class="windowbg2"><span class="smalltext">' , $txt['SMFPredictionLeagueAdmin_Headings']['offsetMessage'] , '</td>
								</tr>
								<tr>
									<td class="windowbg2"><nobr>' . $txt['SMFPredictionLeagueAdmin_Headings']['usersPerPage'] . ':</nobr></td>
									<td class="windowbg2">
										<input type="text" name="usersPerPage" size="5" maxlength="3" value="' . $modSettings['SMFPredictionLeague_usersPerPage'] . '"/>
									</td>
									<td width="100%" class="windowbg2"><span class="smalltext">' . $txt['SMFPredictionLeague_help']['usersPerPageHelp'] . '</span></td>
								</tr>
								<tr>
									<td class="windowbg2"><nobr>' . $txt['SMFPredictionLeagueAdmin_Headings']['maximumPredictionScore'] . ':</nobr></td>
									<td class="windowbg2">
										<input type="text" name="maximumPredictionScore" size="5" maxlength="3" value="' . $modSettings['SMFPredictionLeague_maximumPredictionScore'] . '"/>
									</td>
									<td width="100%" class="windowbg2"><span class="smalltext">' , $txt['SMFPredictionLeague_help']['maximumPredictionScore'] , '</td>
								</tr>
								<tr class="titlebg">
									<td class="catbg3" colspan="3">Scoring</td>
								</tr>
								<tr>
									<td class="windowbg2"><nobr>' . $txt['SMFPredictionLeagueAdmin_Headings']['pointsForHomeScore'] . ':</nobr></td>
									<td class="windowbg2">
										<input type="text" name="homeScore" size="5" maxlength="3" value="' . $modSettings['SMFPredictionLeague_homeScorePoints'] . '"/>
									</td>
									<td width="100%" class="windowbg2"><span class="smalltext">' . $txt['SMFPredictionLeague_help']['homeScorePointsHelp'] . '</span></td>
								</tr>
								<tr>
									<td class="windowbg2"><nobr>' . $txt['SMFPredictionLeagueAdmin_Headings']['pointsForAwayScore'] . ':</nobr></td>
									<td class="windowbg2">
										<input type="text" name="awayScore" size="5" maxlength="3" value="' . $modSettings['SMFPredictionLeague_awayScorePoints'] . '"/>
									</td>
									<td width="100%" class="windowbg2"><span class="smalltext">' . $txt['SMFPredictionLeague_help']['awayScorePointsHelp'] . '</span></td>
								</tr>
								<tr>
									<td class="windowbg2"><nobr>' . $txt['SMFPredictionLeagueAdmin_Headings']['pointsForCorrectResult'] . ':</nobr></td>
									<td class="windowbg2">
										<input type="text" name="correctResult" size="5" maxlength="3" value="' . $modSettings['SMFPredictionLeague_correctResultPoints'] . '"/>
									</td>
									<td width="100%" class="windowbg2"><span class="smalltext">' . $txt['SMFPredictionLeague_help']['correctResultPointsHelp'] . '</span></td>
								</tr>
								<tr>
									<td class="windowbg2"><nobr>' . $txt['SMFPredictionLeagueAdmin_Headings']['pointsForCorrectScore'] . ':</nobr></td>
									<td class="windowbg2">
										<input type="text" name="correctScore" size="5" maxlength="3" value="' . $modSettings['SMFPredictionLeague_correctScorePoints'] . '"/>
									</td>
									<td width="100%" class="windowbg2"><span class="smalltext">' . $txt['SMFPredictionLeague_help']['correctScorePointsHelp'] . '</span></td>
								</tr>
								<tr class="titlebg">
									<td class="catbg3" colspan="3">Colouring</td>
								</tr>
								<tr>
									<td class="windowbg2"><nobr>' . $txt['SMFPredictionLeagueAdmin_Headings']['firstPlaceBackground'] . ':</nobr></td>
									<td class="windowbg2">
										<input type="text" name="firstBackground" size="15" maxlength="15" value="' . $modSettings['SMFPredictionLeague_firstBackground'] . '"/>
									</td>
									<td width="100%" class="windowbg2"><span class="smalltext">' . $txt['SMFPredictionLeague_help']['firstBackgroundHelp'] . '</span></td>
								</tr>
								<tr>
									<td class="windowbg2"><nobr>' . $txt['SMFPredictionLeagueAdmin_Headings']['secondPlaceBackground'] . ':</nobr></td>
									<td class="windowbg2">
										<input type="text" name="secondBackground" size="15" maxlength="15" value="' . $modSettings['SMFPredictionLeague_secondBackground'] . '"/>
									</td>
									<td width="100%" class="windowbg2"><span class="smalltext">' . $txt['SMFPredictionLeague_help']['secondBackgroundHelp'] . '</span></td>
								</tr>
								<tr>
									<td class="windowbg2"><nobr>' . $txt['SMFPredictionLeagueAdmin_Headings']['thirdPlaceBackground'] . ':</nobr></td>
									<td class="windowbg2">
										<input type="text" name="thirdBackground" size="15" maxlength="15" value="' . $modSettings['SMFPredictionLeague_thirdBackground'] . '"/>
									</td>
									<td width="100%" class="windowbg2"><span class="smalltext">' . $txt['SMFPredictionLeague_help']['thirdBackgroundHelp'] . '</span></td>
								</tr>
								<tr>
									<td class="windowbg2" colspan="3">
										<input type="submit" value="' . $txt['SMFPredictionLeagueAdmin_Buttons']['save'] . '" onclick="this.form.formaction.value=\'save_settings\'"/>
									</td>
								</tr>

									
	';
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

function template_teams() {
	global $modSettings, $txt, $context, $settings;
	echo '
		<tr class="titlebg">
			<td><input type="checkbox" checked="true" name="chkAll" onclick="checkAll(this.form, this.form.chkAll.checked);"/></td>
			<td align="left"><b>' . $txt['SMFPredictionLeagueAdmin_ColumnHeadings']['teamName'] . '</b></td>
			<td align="left"><b>' . $txt['SMFPredictionLeagueAdmin_ColumnHeadings']['image'] . '</b></td>
			<td>&nbsp;</td>
		</tr>
	';
	
	foreach($context['SMFPredictionLeague']['teams'] as $row) { 
		echo '				
		<tr>
			<td align="left" width="5%"><input type="checkbox" name="team' . $row['TeamId'] . '"/></td>
			<td align="left" width="40%">' . $row['Name'] . '</td>
			<td align="left" width="40%">' . $row['Image'] . '</td>
			<td align="left" width="15%">' , $row['Image'] != null ? '<img src="' . $settings['default_images_url'] . '/pl_icons/' . $row['Image'] . '"/>' : '' , '</td>
		</tr>
		';
	}
	echo '
		<tr class="titlebg">
			<td colspan="4">
				<input type="submit" value="' . $txt['SMFPredictionLeagueAdmin_Buttons']['addTeam'] . '" onclick="this.form.formaction.value=\'add_teams\';"/> 
				<input type="submit" value="' . $txt['SMFPredictionLeagueAdmin_Buttons']['removeSelectedTeams'] . '" onclick="this.form.formaction.value=\'remove_teams\';"/> 
			</td>
		</tr>
	';

}

function template_support() {
	global $modSettings;
	echo '
		<tr>
			<td class="windowbg">
				<iframe src="http://www.smfmodding.com/support/index.php?smfversion=' . $modSettings["smfVersion"] . '&mod=SMFPredictionLeague&version=' . $modSettings["SMFPredictionLeague_version"] . '" height="400" width="100%" frameborder="0">
				</iframe>
			</td>
		</tr>
		';

}

function template_maintenance() {

	global $txt, $scripturl, $context;

	echo '
								<tr class="titlebg">
									<td colspan="7">' . $txt['SMFPredictionLeagueAdmin_Headings']['databaseMaintenance'] . '
									</td>
								</tr>
								<tr>
									<td class="windowbg">
										<ul>
										<li><a href="' , $scripturl , '?action=' , $context['current_action'] , isset($context['admin_area']) ? '&area=' . $context['admin_area'] : '' , '&sa=maintenance&type=cleanshoutbox">Clean Shoutbox</a></li>
										<li><a href="' , $scripturl , '?action=' , $context['current_action'] , isset($context['admin_area']) ? '&area=' . $context['admin_area'] : '' , '&sa=maintenance&type=wipeleague">Wipe League</a></li>
										</ul>
									</td>
								</tr>
	';
	if (isset($context['SMFPredictionLeague']['cleanComplete'])) {
		echo '
							<tr>
								<td class="windowbg">
									<font color="green">' , $context['SMFPredictionLeague']['cleanComplete'] , '</font>
								</td>
							</tr>
		';
	}
	if (isset($context['SMFPredictionLeague']['wipeComplete'])) {
		echo '
							<tr>
								<td class="windowbg">
									<font color="green">' , $context['SMFPredictionLeague']['wipeComplete'] , '</font>
								</td>
							</tr>
		';
	}

}

function template_results() {

	global $context, $txt;

	// If there was an error getting the data, show it here
	if(!empty($context['SMFPredictionLeague']['error'])) {
		echo '
								<tr><td class="windowbg">' .$context['SMFPredictionLeague']['error'] . '</td></tr>
								<tr>
									<td class="windowbg">
										<input type="submit" value="Generate Week"/>
									</td>
								</tr>
		';
	} else {
		if ($context['SMFPredictionLeague']['action'] != 'outstanding_results') {
			echo '
								<tr class="titlebg">
									<td colspan="7">' . $txt['SMFPredictionLeagueAdmin_Headings']['resultsForWeek'] . ':
										' . template_week_dropdown(1) . '
									</td>
								</tr>
			';
		} else {
			echo '
								<tr class="titlebg">
									<td colspan="7">' . $txt['SMFPredictionLeagueAdmin_Headings']['outstandingResults'] . '
									</td>
								</tr>
			';
		}
		
		// If an error was returned during generating the data, show it here
		if(empty($context['SMFPredictionLeague']['fixtures'])) {
			if ($context['SMFPredictionLeague']['action'] == 'outstanding_results') {
				echo '<tr><td class="windowbg">' . $txt['SMFPredictionLeagueAdmin_Headings']['noOutstandingResults'] . '</td></tr>';
			} else {
				echo '<tr><td class="windowbg">' . $txt['SMFPredictionLeagueAdmin_Headings']['noFixtures'] . '</td></tr>';
			}
		} else {
			echo '					<tr>
										<!-- <td><input type="checkbox" checked="true" name="chkAll" onclick="checkAll(this.form, this.form.chkAll.checked);"/></td> -->
										<td><b>' . $txt['SMFPredictionLeagueAdmin_ColumnHeadings']['date'] . '</b></td>
										<td><b>' . $txt['SMFPredictionLeagueAdmin_ColumnHeadings']['home'] . '</b></td>
										<td><b>&nbsp;</b></td>
										<td><b>&nbsp;</b></td>
										<td><b>&nbsp;</b></td>
										<td><b>' . $txt['SMFPredictionLeagueAdmin_ColumnHeadings']['away'] . '</b></td>
									</tr>
			';
			$counter = 0;
			foreach($context['SMFPredictionLeague']['fixtures'] as $row) { 
			
				// We don't want the scores defaulting to zero
				if ($row['HomeScore'] == null) {
					$homeScore = -1;
				} else {
					$homeScore = $row['HomeScore'];
				}
				
				if ($row['AwayScore'] == null) {
					$awayScore = -1;
				} else {
					$awayScore = $row['AwayScore'];
				}
				echo '				<tr>
										<td><input type="hidden" name="mtch' . $row['MatchId'] . '"/>' . date("F j, Y, g:i a", $row['MatchDate']) . '</td>
										<td>' . $row['HomeTeam'] . '</td>
										<td><select name="HScr' . $row['MatchId'] . '">
											<option value="-" ', $homeScore == -1 ? 'selected="selected"' : '' , '>-</option>
												' . template_score_dropdown($homeScore) . '
											</select>
										</td>
										<td>vs.</td>
										<td><select name="AScr' . $row['MatchId'] . '">
											<option value="-" ', $awayScore == -1 ? 'selected="selected"' : '' , '>-</option>
												' . template_score_dropdown($awayScore) . '
											</select>
										</td>
										<td>' . $row['AwayTeam'] . '</td>
									</tr>
				';
				$counter++;
			}
		}
		
		echo '					<tr>
									<td class="windowbg" colspan="7">
										<input type="submit" value="' . $txt['SMFPredictionLeagueAdmin_Buttons']['saveResults'] . '" onclick="this.form.formaction.value=\'save_results\';"/> 
			';
		if ($context['SMFPredictionLeague']['action'] != 'outstanding_results') {
			echo '
										<input type="submit" value="' . $txt['SMFPredictionLeagueAdmin_Buttons']['outstandingResults'] . '" onclick="this.form.formaction.value=\'outstanding_results\';"/>
				';
		} else {
			echo '						<input type="button" value="Cancel" onclick="javascript:history.back(1)"/>';
		}
		echo '
										<input type="submit"  value="Increment Week" onclick="this.form.formaction.value=\'increment_week\';"/>
										<input type="checkbox" name="SendPMs" checked="checked"/> Send Automatic PM Messages
									</td>
								</tr>
		';
		
	}
}

function template_fixtures() {
	global $context, $form_action, $txt, $settings;
	
	// If there was an error getting the data, show it here
	if(!empty($context['SMFPredictionLeague']['error'])) {
		
		echo '
								<tr><td class="windowbg">' .$context['SMFPredictionLeague']['error'] . '</td></tr>
								<tr>
									<td class="windowbg">
										<input type="submit" value="Generate Week" onclick="this.form.formaction.value=\'increment_week\';"/>
									</td>
								</tr>
		';
	} else {
		echo '
								<tr class="titlebg">
									<td colspan="5">' . $txt['SMFPredictionLeagueAdmin_Headings']['fixturesForWeek'] . '
										' . template_week_dropdown(1) . '										
									</td>
								</tr>
		';
	
		// If an error was returned during generating the data, show it here
		if(empty($context['SMFPredictionLeague']['fixtures'])) {
			echo '<tr><td class="windowbg">' . $txt['SMFPredictionLeagueAdmin_Headings']['noFixturesForWeek'] . '</td></tr>';
		} else {
			echo '					<tr>
										<td><input type="checkbox" checked="true" name="chkAll" onclick="checkAll(this.form, this.form.chkAll.checked);"/></td>
										<td><b>' . $txt['SMFPredictionLeagueAdmin_ColumnHeadings']['date'] . '</b></td>
										<td><b>' . $txt['SMFPredictionLeagueAdmin_ColumnHeadings']['time'] . '</b></td>
										<td><b>' . $txt['SMFPredictionLeagueAdmin_ColumnHeadings']['home'] . '</b></td>
										<td><b>' . $txt['SMFPredictionLeagueAdmin_ColumnHeadings']['away'] . '</b></td>
									</tr>
			';
			$counter = 0;
			foreach($context['SMFPredictionLeague']['fixtures'] as $row) { 
				echo '				<tr>
										<td><input type="checkbox" checked="true" name="mtch' . $row['MatchId'] . '"/></td>
										<td>
											<input type="text" name="date' . $row['MatchId'] . '" value="' . date("d/m/Y", $row['MatchDate']) . '" onfocus="this.select();lcs(this,\'' . $settings['default_images_url'] . '/pl_icons\')" onclick="event.cancelBubble=true;this.select();lcs(this,\'' . $settings['default_images_url'] . '/pl_icons\')">
										</td>
										<td>
											<select name="hour' . $row['MatchId'] . '">';
				for ($i = 0; $i < 24; $i++) {
					if ($i == date("H", $row['MatchDate'])) {
						echo '					<option value="' , $i , '" selected="selected">' , $i , '</option>';
					} else {
						echo '					<option value="' , $i , '">' , $i , '</option>';
					}
				}
				echo '						</select>:
											<select name="mins' . $row['MatchId'] . '">
				';

				for ($i = 0; $i < 46; $i = $i + 15) {
					if ($i == date("i", $row['MatchDate'])) {
						echo '					<option value="' , $i , '" selected="selected">' , $i , '</option>';
					} else {
						echo '					<option value="' , $i , '">' , $i , '</option>';
					}
				}
				echo '						</select>

										</td>				
										<td><select name="home' . $row['MatchId'] . '">' . template_team_dropdown($row['HomeTeamId']) . '</select></td>
										<td><select name="away' . $row['MatchId'] . '">' . template_team_dropdown($row['AwayTeamId']) . '</select></td>
									</tr>
				';
				$counter++;
			}
		}
		
		echo '					<tr>
									<td class="windowbg" colspan="5">
										<input type="submit" value="' . $txt['SMFPredictionLeagueAdmin_Buttons']['saveUpdates'] . '" onclick="this.form.formaction.value=\'update_fixtures\';"/> 
										<input type="submit" value="Add Fixtures" onclick="this.form.formaction.value=\'add_fixtures\';"/> 
										<input type="submit" value="Delete Selected" onclick="this.form.formaction.value=\'delete_fixtures\';"/> 
										<input type="submit"  value="Increment Week" onclick="this.form.formaction.value=\'increment_week\';"/>
										<input type="checkbox" name="SendPMs" checked="checked"/> Send Automatic PM Messages										
									</td>
								</tr>
		';

	}

}

function template_add_fixtures() {
	
	global $context, $txt, $settings;
	
	echo '
								<tr class="titlebg">
									<td colspan="4">
										' . $txt['SMFPredictionLeagueAdmin_Headings']['multipleFixturesForWeek'] . ' ' . template_week_dropdown(0) . '
									</td>
								</tr>
								<tr>
									<td><nobr><b>' . $txt['SMFPredictionLeagueAdmin_ColumnHeadings']['dateWithFormat'] . '</b></nobr></td>
									<td><nobr><b>' . $txt['SMFPredictionLeagueAdmin_ColumnHeadings']['timeWithFormat'] . '</b></nobr></td>
									<td width="100%"><b>' . $txt['SMFPredictionLeagueAdmin_ColumnHeadings']['match'] . '</b></td>
								</td>
	';
								
	if(!empty($context['SMFPredictionLeague']['error'])) {
		echo '<tr><td class="windowbg" align="center">'.$context['SMFPredictionLeague']['error'].'</td></tr>';
	} else {
		
		for ($counter = 0; $counter <= 10; $counter ++) {
			echo '
								<tr>
									<td>
										<input type="text" name="date' . $counter . '" value="" onfocus="this.select();lcs(this,\'' . $settings['default_images_url'] . '/pl_icons\')" onclick="event.cancelBubble=true;this.select();lcs(this,\'' . $settings['default_images_url'] . '/pl_icons\')">
									</td>
									<td>
										<select name="hour' . $counter . '">
											<option value="00">00</option>
											<option value="01">01</option>
											<option value="02">02</option>
											<option value="03">03</option>
											<option value="04">04</option>
											<option value="05">05</option>
											<option value="06">06</option>
											<option value="07">07</option>
											<option value="08">08</option>
											<option value="09">09</option>
											<option value="10">10</option>
											<option value="11">11</option>
											<option value="12">12</option>
											<option value="13">13</option>
											<option value="14">14</option>
											<option value="15">15</option>
											<option value="16">16</option>
											<option value="17">17</option>
											<option value="18">18</option>
											<option value="19">19</option>
											<option value="20">20</option>
											<option value="21">21</option>
											<option value="22">22</option>
											<option value="23">23</option>
										</select>:
										<select name="mins' . $counter . '">
											<option value="00">00</option>
											<option value="15">15</option>
											<option value="30">30</option>
											<option value="45">45</option>
										</select>
									</td>
									<td>
										<select name="home' . $counter . '">' . template_team_dropdown(1) . '</select> vs. 
										<select name="away' . $counter . '">' . template_team_dropdown(1) . '</select>
									</td>
								</tr>
									
			';
		}
		echo '
								<tr>
									<td>
										<input type="submit" value="Save" onclick="this.form.formaction.value=\'save_fixtures\'"/>
										<input type="button" value="Cancel" onclick="javascript:history.back(1)"/>
									</td>
								</tr>
		';
	}
}


function template_add_teams() {
	
	global $context, $txt;
	
	echo '
								<tr class="titlebg">
									<td><nobr><b>' . $txt['SMFPredictionLeagueAdmin_ColumnHeadings']['teamName'] . '</b></nobr></td>
									<td width="100%"><b>' . $txt['SMFPredictionLeagueAdmin_ColumnHeadings']['image'] . '</b></td>
								</td>
	';
								
	if(!empty($context['SMFPredictionLeague']['error'])) {
		echo '<tr><td class="windowbg" align="center">'.$context['SMFPredictionLeague']['error'].'</td></tr>';
	} else {
		
		for ($counter = 0; $counter <= 10; $counter ++) {
			echo '
								<tr>
									<td>
										<input tpye="text" name="team' . $counter . '" maxlength="45" size="40"/>
									</td>
									<td>
										<input tpye="text" name="imge' . $counter . '" maxlength="400" size="80"/>
									</td>
								</tr>
									
			';
		}
		echo '
								<tr>
									<td colspan="2">
										<input type="submit" value="Save" onclick="this.form.formaction.value=\'save_teams\'"/>
										<input type="button" value="Cancel" onclick="javascript:history.back(1)"/>
									</td>
								</tr>
		';
	}
}

?>
