<?php

if (!defined('SMF'))
	die('Hacking attempt...');
	
function getPMUsersSql() {
	global $db_prefix;
	
	return "
			SELECT		U.UserId
			FROM 		{$db_prefix}pl_users U
			WHERE		U.ReceivePMs = 1
	";
}	
	
function getIncrementWeekSql() {
	
	global $db_prefix;
	
	return "
			INSERT INTO {$db_prefix}pl_week 
						SELECT IF(MAX(WeekId) IS NULL, 1, MAX(WeekId)+1) 
			FROM 		{$db_prefix}pl_week
	";
}

function getUpdateMatchFixtureSql($matchData) {
	
	global $db_prefix;
	
	// Date in format of YYYY-MM-DD
	// Time in format of HH:MM
	// Best to change how date/time is obtained in future - popup would be nice
	$year = substr($matchData["date"], 6, 4);
	$month = substr($matchData["date"], 3, 2);
	$day = substr($matchData["date"], 0, 2);
	
	$matchDateTime = mktime($matchData["hour"], $matchData["mins"], 0, $month, $day, $year);
	$updatedDateTime = time();
	
	return "
		UPDATE 	{$db_prefix}pl_matches 
		SET 	HomeTeamId = {$matchData["home"]}, 
				AwayTeamId = {$matchData["away"]}, 
				MatchDate = {$matchDateTime}, 
				UpdatedDate = {$updatedDateTime} 
		WHERE 	Matchid = {$matchData["id"]}
	";
}

function getDeleteFixturesSql($matchId) {

	global $db_prefix;

	return "
		DELETE FROM {$db_prefix}pl_matches 
		WHERE		MatchId = {$matchId}
	";
}

function getDeleteTeamsSql($teamId) {

	global $db_prefix;
	
	return "
		DELETE FROM {$db_prefix}pl_teams 
		WHERE 		TeamId = {$teamId}
	";

}

function getAddTeamSql($teamData) {

	global $db_prefix;

	return "
		INSERT INTO {$db_prefix}pl_teams 
		SET 		Name = '{$teamData["team"]}', 
					Image = '{$teamData["image"]}'
	";
}

function getAddMatchSql($matchData) {
	global $db_prefix;

	// Date in format of YYYY-MM-DD
	// Time in format of HH:MM
	// Best to change how date/time is obtained in future - popup would be nice
	$year = substr($matchData["date"], 6, 4);
	$month = substr($matchData["date"], 3, 2);
	$day = substr($matchData["date"], 0, 2);
	
	$matchDateTime = mktime($matchData["hour"], $matchData["mins"], 0, $month, $day, $year);
	$updatedDateTime = time();
	
	return	"
		INSERT INTO {$db_prefix}pl_matches 
		SET 		HomeTeamId = {$matchData["home"]}, 
					AwayTeamId = {$matchData["away"]}, 
					MatchDate = {$matchDateTime}, 
					UpdatedDate = {$updatedDateTime}, 
					WeekId = {$_POST["week"]}
	";
}

function getAddStandingsSql($row, $position, $previousPosition, $posMove) {
	
	global $db_prefix;
	
	$updateTime = time();
	
	return "
		INSERT INTO {$db_prefix}pl_standings
		(
			UserId,
			WeekId,
			Position,
			PreviousPosition,
			Played,
			Won,
			Drawn,
			Lost,
			GoalsFor,
			GoalsAgainst,
			StandingDate,
			Points,
			PosMove
		) VALUES (
			{$row["UserId"]},
			{$row["WeekId"]},
			{$position},
			{$previousPosition},
			{$row["TotalPlayed"]},
			{$row["TotalWins"]},
			{$row["TotalDraws"]},
			{$row["TotalLosses"]},
			{$row["TotalGoalsFor"]},
			{$row["TotalGoalsAgainst"]},
			{$updateTime},
			{$row["TotalPoints"]},
			{$posMove}
		)
	";
}

function getStandingsToUpdateSql($version) {

	global $db_prefix, $context;
	
	$realname = 'M.real_name';
	if ($version == 1) {
		$realname = 'M.realName';
	}
	
	return "
		SELECT			{$realname},
						X.UserId,
						X.WeekId,
						SUM(IFNULL(Y.TotalPlayed, 0)) AS TotalPlayed,
						SUM(IFNULL(Y.TotalWins, 0)) AS TotalWins,
						SUM(IFNULL(Y.TotalDraws, 0)) AS TotalDraws,
						SUM(IFNULL(Y.TotalLosses, 0)) AS TotalLosses,
						SUM(IFNULL(Y.TotalGoalsFor, 0)) AS TotalGoalsFor,
						SUM(IFNULL(Y.TotalGoalsAgainst, 0)) AS TotalGoalsAgainst,
						SUM(IFNULL(Y.TotalPoints, 0)) AS TotalPoints
		FROM
		(
			SELECT 			U.UserId, 
							W.WeekID
			FROM 			{$db_prefix}pl_week W
			CROSS JOIN 		{$db_prefix}pl_users U
		) AS X
		LEFT OUTER JOIN
		(
			SELECT 			P.UserId, 
							P.WeekId,
							COUNT(P.PredictionId) AS TotalPlayed,
							SUM(CASE WHEN P.PointType = 'W' THEN 1 ELSE 0 END) AS TotalWins,
							SUM(CASE WHEN P.PointType = 'D' THEN 1 ELSE 0 END) AS TotalDraws,
							SUM(CASE WHEN P.PointType = 'L' THEN 1 ELSE 0 END) AS TotalLosses,
							SUM(P.GoalsFor) AS TotalGoalsFor,
							SUM(P.GoalsAgainst) AS TotalGoalsAgainst,
							SUM(P.Points) AS TotalPoints
			FROM 			{$db_prefix}pl_predictions P
			INNER JOIN		{$db_prefix}pl_matches M
			ON				P.MatchId = M.MatchId
			WHERE			M.Updated = 1
			GROUP BY 		UserId, 
							WeekId
		) AS Y
		ON 				X.UserId = Y.UserId
		AND 			X.WeekID >= Y.WeekID
		INNER JOIN 		{$db_prefix}members M 
		ON 				X.UserId = M.id_member
		WHERE 			X.WeekID >= {$context['SMFPredictionLeague']['selected_week']} 
		GROUP BY 		X.UserId,
						X.WeekId,
						{$realname}
		ORDER BY 		WeekId, 
						TotalPoints DESC, 
						{$realname}
	";
}

function getDeleteStandingsSql() {

	global $db_prefix, $context;
	
	return "
		DELETE FROM {$db_prefix}pl_standings 
		WHERE 		WeekId >= {$context['SMFPredictionLeague']['selected_week']}
	";
}

function getUsersPreviousPositionSql() {

	global $db_prefix, $context;
	
	return "
		SELECT 	Position, 
				UserId 
		FROM 	{$db_prefix}pl_standings 
		WHERE 	weekid = " . ($context['SMFPredictionLeague']['selected_week'] - 1);
}

function getPredictionResultsSql($matchData) {

	global $db_prefix;
	
	// Only update matches that have a score, this flag is used to see if there were any matches with scores at all
	$matchesToUpdate = false;
	
	if (isset($matchData) && sizeof($matchData) > 0) {
	
		// We want to build the SQL query for the match data selection here
		$matchesSql = 'WHERE			M.MatchId IN(';
		$arrayCount = 0;
		foreach ($matchData as &$matches) {	
			if ($matches["homeScore"] > -1 && $matches["awayScore"] > -1) {
				$matchesSql .= $matches["id"];
				$arrayCount++;
				if ($arrayCount != sizeof($matchData)) {
					$matchesSql .= ',';
				}
				$matchesToUpdate = true;
			}
		}
		$matchesSql .= ')';
	} else {
		$matchesSql = '';
	}
	
	// Need to come up with a better way to do this, not exactly an elegant solution!
	if ($matchesToUpdate == true) {
		return "SELECT 	M.HomeScore,
						M.AwayScore,
						P.PredictionId,
						P.HomeScore AS PredHomeScore,
						P.AwayScore AS PredAwayScore,
						P.Points
			FROM 		{$db_prefix}pl_matches M
			INNER JOIN 	{$db_prefix}pl_predictions P
			ON 			M.MatchId = P.MatchId
			{$matchesSql}
		";
	} else {
		return '';
	}
}

function getReturnWeeksSql() {

	global $db_prefix;

	return "
		SELECT 	W.WeekId 
		FROM 	{$db_prefix}pl_week W
	";
}

function getOutstandingFixturesSql() {
	
	global $db_prefix;
	
	return "
		SELECT 		M.MatchId,
					T.Name AS HomeTeam,
					T.TeamId AS HomeTeamId,
					T2.Name AS AwayTeam,
					T2.TeamId AS AwayTeamId,
					M.MatchDate,
					M.HomeScore,
					M.AwayScore
		FROM 		{$db_prefix}pl_matches M
		INNER JOIN	{$db_prefix}pl_teams T
		ON			M.HomeTeamId = T.TeamId
		INNER JOIN	{$db_prefix}pl_teams T2
		ON			M.AwayTeamId = T2.TeamId
		WHERE		M.Updated = 0
	";
}

function getFixturesSql() {

	global $db_prefix, $context;

	return "
		SELECT 		M.MatchId,
					T.Name AS HomeTeam,
					T.TeamId AS HomeTeamId,
					T2.Name AS AwayTeam,
					T2.TeamId AS AwayTeamId,
					M.MatchDate,
					M.HomeScore,
					M.AwayScore
		FROM 		{$db_prefix}pl_matches M
		INNER JOIN	{$db_prefix}pl_teams T
		ON			M.HomeTeamId = T.TeamId
		INNER JOIN	{$db_prefix}pl_teams T2
		ON			M.AwayTeamId = T2.TeamId
		WHERE		M.WeekId = {$context['SMFPredictionLeague']['selected_week']}
	";
}

function getDeletePredictionsForNonExistentMatchesSql() {

	global $db_prefix;

	return "
		DELETE FROM {$db_prefix}pl_predictions 
		WHERE 		MatchId NOT IN (
			SELECT MatchId 
			FROM {$db_prefix}pl_matches
		)
	";
}

function getDeleteMatchesForTeamSql($teamId) {

	global $db_prefix;
	
	return "
		DELETE FROM 	{$db_prefix}pl_matches 
		WHERE			HomeTeamId = {$teamId} OR 
						AwayTeamId = {$teamId}
	";

}

function getDeletePredictionsSql($matchId) {

	global $db_prefix;
	
	return "
		DELETE FROM {$db_prefix}pl_predictions 
		WHERE		MatchId = {$matchId}
	";
}

function getUpdateTeamResultSql($winPoints, $drawPoints, $losePoints) {

	global $db_prefix;
	
	return "
		UPDATE 	{$db_prefix}pl_teams T1, 
				(
					SELECT		T.Name,
								T.TeamId,
								(
									(IFNULL(Y.HomeWin,0) * {$winPoints}) + (IFNULL(Y.HomeDraw,0) * {$drawPoints}) + (IFNULL(Y.HomeLoss,0) * {$losePoints}) +
									(IFNULL(X.AwayWin,0) * {$winPoints}) + (IFNULL(X.AwayDraw,0) * {$drawPoints}) + (IFNULL(X.AwayLoss,0) * {$losePoints})
								) AS TotalPoints,
								(IFNULL(Y.HomeWin,0) + IFNULL(X.AwayWin,0)) AS TotalWins,
								(IFNULL(Y.HomeDraw,0) + IFNULL(X.AwayDraw,0)) AS TotalDraws,
								(IFNULL(Y.HomeLoss,0) + IFNULL(X.AwayLoss,0)) AS TotalLosses,
								(IFNULL(Y.HomeFor,0) + IFNULL(X.AwayFor,0)) AS TotalGoalsFor,
								(IFNULL(Y.HomeAgainst,0) + IFNULL(X.AwayAgainst,0)) AS TotalGoalsAgainst
					FROM   		{$db_prefix}pl_teams T
					LEFT JOIN
					(
						SELECT    M.HomeTeamId,
						          COUNT(*) AS HomePlayed,
						          HomeScore AS TEST,
						          SUM(CASE WHEN M.HomeScore > M.AwayScore THEN 1 ELSE 0 END) AS HomeWin,
						          SUM(CASE WHEN M.HomeScore < M.AwayScore THEN 1 ELSE 0 END) AS HomeLoss,
						          SUM(CASE WHEN M.HomeScore = M.AwayScore THEN 1 ELSE 0 END) AS HomeDraw,
						          SUM(M.HomeScore) AS HomeFor,
						          SUM(M.AwayScore) AS HomeAgainst
						FROM      {$db_prefix}pl_matches M
						WHERE     M.Updated = 1
						GROUP BY  M.HomeTeamId
					) AS Y
					ON 			T.TeamId = Y.HomeTeamId
					LEFT JOIN
					(			
						SELECT    M.AwayTeamId,
						          COUNT(*) AS AwayPlayed,
						          SUM(CASE WHEN M.AwayScore > M.HomeScore THEN 1 ELSE 0 END) AS AwayWin,
						          SUM(CASE WHEN M.AwayScore < M.HomeScore THEN 1 ELSE 0 END) AS AwayLoss,
						          SUM(CASE WHEN M.AwayScore = M.HomeScore THEN 1 ELSE 0 END) AS AwayDraw,
						          SUM(M.AwayScore) AS AwayFor,
						          SUM(M.HomeScore) AS AwayAgainst
						FROM      {$db_prefix}pl_matches M
						WHERE     M.Updated = 1
						GROUP BY  M.AwayTeamId
					) AS X
					ON 			T.TeamId = X.AwayTeamId
				) AS T2
		SET 	T1.Won = T2.TotalWins,
				T1.Drawn = T2.TotalDraws,
				T1.Lost = T2.TotalLosses,
				T1.GoalsFor = T2.TotalGoalsFor,
				T1.GoalsAgainst = T2.TotalGoalsAgainst,
				T1.Points = T2.TotalPoints
		WHERE 	T1.TeamId = T2.TeamId	
	";
}

function getUpdateMatchResultSql($matchData) {

	global $db_prefix;
	
	return "
		UPDATE 	{$db_prefix}pl_matches 
		SET 	HomeScore = {$matchData["homeScore"]}, 
				AwayScore = {$matchData["awayScore"]}, 
				Updated=1 
		WHERE 	Matchid = {$matchData["id"]}";
}

function getUpdatePredictionResultSql($points, $pointType, $goalsFor, $goalsAgainst, $predictionId) {

	global $db_prefix;
	return "
		UPDATE {$db_prefix}pl_predictions 
		SET 	Points = {$points}, 
				PointType = '{$pointType}', 
				GoalsFor={$goalsFor}, 
				GoalsAgainst={$goalsAgainst} 
		WHERE 	PredictionId = {$predictionId}
	";
}

function getTeamsSql() {

	global $db_prefix;
	
	return "
		SELECT 		T.TeamId,
					T.Name,
					T.Image
		FROM 		{$db_prefix}pl_teams T
		ORDER BY	T.Name
	";

}

function getCleanShoutboxSql() {

	global $db_prefix;
	
	return "
		DELETE
		FROM		{$db_prefix}pl_shoutbox
	";
}

function getDeleteMatchesSql() {

	global $db_prefix;
	
	return "
		DELETE
		FROM		{$db_prefix}pl_matches
	";
}

function getDeleteAllPredictionsSql() {

	global $db_prefix;
	
	return "
		DELETE
		FROM		{$db_prefix}pl_predictions
	";
}

function getDeleteAllStandingsSql() {

	global $db_prefix;
	
	return "
		DELETE
		FROM		{$db_prefix}pl_standings
	";
}

function getResetTeamsSql() {

	global $db_prefix;
	
	return "
		UPDATE		{$db_prefix}pl_teams
		SET			Won = 0,
					Drawn = 0,
					Lost = 0,
					GoalsFor = 0,
					GoalsAgainst = 0,
					Points = 0
	";
}

function getResetWeekSql() {

	global $db_prefix;
	
	return "
		DELETE		FROM {$db_prefix}pl_week;
	";
}
?>