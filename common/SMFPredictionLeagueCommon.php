<?php

if (!defined('SMF'))
	die('Hacking attempt...');


function GetReturnMaxWeeksAtTopStatsSql($vars) {

	global $db_prefix;
	
	$maxRows = isset($vars["maxRows"]) ? $vars["maxRows"] : 5;
	$version = isset($vars["version"]) ? $vars["version"] : 2;
	
	if ($version == 2) {
		$realname = "M.real_name";
	} else {
		$realname = "M.realName";
	}

	
	return "
		SELECT		S.UserId,
					{$realname},
					COUNT(S.StandingId) AS WeeksAtTop
		FROM 		{$db_prefix}pl_standings S
		INNER JOIN 	{$db_prefix}members M
		ON 			S.UserId = M.id_member
		WHERE 		S.Position = 1
		GROUP BY 	M.id_member
		LIMIT 		0,{$maxRows}
	";
}
	
function GetReturnAveragePointsStatsSql($vars) {

	global $db_prefix;
	
	$maxRows = isset($vars["maxRows"]) ? $vars["maxRows"] : 5;
	$sortDir = isset($vars["sortDir"]) ? $vars["sortDir"] : 'DESC';
	$version = isset($vars["version"]) ? $vars["version"] : 2;

	if ($version == 2) {
		$realname = "M.real_name";
	} else {
		$realname = "M.realName";
	}

	return "
	SELECT		P.UserId,
				{$realname},
				COUNT(P.PredictionId) AS Predictions,
				SUM(P.Points) AS TotalPoints,
				ROUND(SUM(P.Points)/COUNT(P.PredictionId),2) AS AveragePoints
	FROM    	{$db_prefix}pl_predictions P
	INNER JOIN	{$db_prefix}members M
	ON			P.UserId = M.id_member
	GROUP BY 	UserId
	ORDER BY 	AveragePoints {$sortDir}
	LIMIT 		0,{$maxRows}
	";

}	
	
function GetReturnPredictionStatsSql() {

	global $db_prefix;
	
	return "
		SELECT		COUNT(PredictionId) AS TotalPredictions,
					SUM(Points) AS TotalPoints,
					SUM(CASE WHEN PointType = 'W' THEN 1 ELSE 0 END) AS TotalWins,
					SUM(CASE WHEN PointType = 'D' THEN 1 ELSE 0 END) AS TotalDraws,
					SUM(CASE WHEN PointType = 'L' THEN 1 ELSE 0 END) AS TotalLosses,
					SUM(GoalsFor) AS TotalGoalsFor,
					SUM(GoalsAgainst) AS TotalGoalsAgainst,
					ROUND(AVG(Points),2) AS AveragePoints,
					MAX(Points) AS MaxPoints
		FROM 		{$db_prefix}pl_predictions
	";

}
	
function GetReturnTopMoversSql($vars) {

	global $db_prefix;
	
	$weekId = $vars["weekId"];
	$maxRows = isset($vars["maxRows"]) ? $vars["maxRows"] : 5;
	$sortDir = isset($vars["sortDir"]) ? $vars["sortDir"] : 'DESC';
	$version = isset($vars["version"]) ? $vars["version"] : 2;
	
	if ($version == 2) {
		$realname = "M.real_name";
	} else {
		$realname = "M.realName";
	}
	
	return "
		SELECT 		S.UserId,
					S.PosMove,
					{$realname}
		FROM 		{$db_prefix}pl_standings S
		INNER JOIN 	{$db_prefix}members M
		ON 			S.UserId = M.id_member
		WHERE 		S.WeekId = {$weekId}
		ORDER BY 	S.PosMove {$sortDir},
					{$realname} {$sortDir}
		LIMIT 		0,{$maxRows}
	";
}
	
function GetReturnNextMatchSql($vars) {

	global $db_prefix;
	
	$offsetDate = isset($vars["offsetDate"]) ? $vars["offsetDate"] : 'UNIX_TIMESTAMP()';
	
	return "
		SELECT 		M.MatchId,
					M.MatchDate,
					T1.TeamId AS HomeTeamId,
					T2.TeamId AS AwayTeamId,
					T1.Name AS HomeTeamName,
					T1.Image AS HomeTeamImage,
					T2.Name AS AwayTeamName,
					T2.Image AS AwayTeamImage
		FROM 		{$db_prefix}pl_matches M
		INNER JOIN 	{$db_prefix}pl_teams T1
		ON 			M.HomeTeamId = T1.TeamId
		INNER JOIN 	{$db_prefix}pl_teams T2
		ON 			M.AwayTeamId = T2.TeamId
		WHERE 		M.Updated = 0
		AND			M.MatchDate > {$offsetDate}
		ORDER BY 	M.MatchDate ASC
		LIMIT 		0,1
	";
}
	
function GetReturnMatchPredictionsSql($vars)  {

	global $db_prefix;
	
	$matchId = $vars['matchId'];
	
	return "
		SELECT		IFNULL(CONCAT(P.HomeScore, ' - ', P.AwayScore),'') AS Prediction,
					IFNULL(COUNT(*),0) AS PredictionCount,
					IFNULL(ROUND(COUNT(*)/(SELECT COUNT(*) FROM {$db_prefix}pl_predictions WHERE MatchId = {$matchId})*100),0) AS Percentage
		FROM		{$db_prefix}pl_predictions P
		WHERE		P.MatchId = {$matchId}
		GROUP BY 	Prediction
		ORDER BY	PredictionCount DESC
	";
					
}
	
function GetReturnMaxUsersSql() {
	global $db_prefix;
	
	return "
		SELECT		COUNT(*) AS MaxUsers
		FROM		{$db_prefix}pl_users
	";
}

function GetReturnMaxWeeksSql() {
	global $db_prefix;
	
	return "
		SELECT		MAX(WeekId) AS MaxWeek
		FROM		{$db_prefix}pl_week
	";
}
	
function GetReturnUserPositionsSql($vars) {

	global $db_prefix;
	
	$userId = $vars['userId'];
	$startWeek = $vars['startWeek'];
	$max = $vars['max'];
	
	return "
		SELECT		S.Position,
					S.WeekId
		FROM		{$db_prefix}pl_standings S
		WHERE		S.UserId = {$userId}
		ORDER BY	S.WeekId
		LIMIT		{$startWeek},{$max}
	";

}
	
function GetUpdateUserInfoSql($userId, $receivePMs) {

	global $db_prefix;
	
	return "
		UPDATE	{$db_prefix}pl_users U
		SET		ReceivePMs = {$receivePMs}
		WHERE	U.UserId = {$userId}
	";

}
	
function GetReturnUserInfoSql($vars) {
	global $db_prefix;
	
	$userId = $vars['userId'];
	$version = isset($vars["version"]) ? $vars["version"] : 2;
	
	if ($version == 2) {
		$realname = "M.real_name";
	} else {
		$realname = "M.realName";
	}


	return "
		SELECT		S.Position,
					S.PreviousPosition,
					S.Played,
					S.Won,
					S.Drawn,
					S.Lost,
					S.GoalsFor,
					S.GoalsAgainst,
					S.Points,
					S.PosMove,
					{$realname},
					U.ReceivePMs,
			        (SELECT MAX(P1.UpdatedDate) FROM {$db_prefix}pl_predictions P1 WHERE P1.UserId = {$userId}) AS LastPredictionDate,
			        (SELECT COUNT(P1.PredictionId) FROM {$db_prefix}pl_predictions P1 WHERE P1.UserId = {$userId}) AS Predictions
		FROM 		{$db_prefix}pl_users U 
		INNER JOIN  {$db_prefix}members M 
		ON 			U.UserId = M.id_member 
		LEFT JOIN 	{$db_prefix}pl_standings S 
		ON 			S.userId = U.UserId 
		WHERE 		U.UserId = {$userId}
		GROUP BY 	S.Position,
					S.PreviousPosition,
					S.Played,
					S.Won,
					S.Drawn,
					S.Lost,
					S.GoalsFor,
					S.GoalsAgainst,
					S.Points,
					S.PosMove,
					{$realname},
					U.ReceivePMs
		ORDER BY	S.WeekId DESC
		LIMIT		0,1
	";
}
	
// Define the SQL queries we use across both SMF versions
function GetOutstandingPredictionsSql($vars) {

	global $db_prefix;
	
	$userId = $vars['userId'];
	$time = $vars['time'];

	return "
		SELECT 		M.MatchId,
					M.WeekId,
					M.MatchDate,
					T1.Name AS HomeTeamName,
					T1.Image AS HomeImage,
					T2.Name AS AwayTeamName,
					T2.Image AS AwayImage
		FROM 		{$db_prefix}pl_matches M
		INNER JOIN 	{$db_prefix}pl_teams T1
		ON 			M.HomeTeamId = T1.TeamId
		INNER JOIN 	{$db_prefix}pl_teams T2
		ON 			M.AwayTeamId = T2.TeamId
		WHERE 		M.MatchId NOT IN (SELECT MatchId FROM {$db_prefix}pl_predictions P WHERE P.UserId = {$userId} )
		AND 		MatchDate > {$time}
		AND			M.Updated = 0
		ORDER BY 	M.WeekId, MatchDate 
	";
}

function GetUpdatePredictionSql($matchData, $userId) {

	global $db_prefix;
	
	$updatedDateTime = time();

	return "
		UPDATE 	{$db_prefix}pl_predictions 
		SET 	HomeScore = {$matchData["home"]}, 
				AwayScore = {$matchData["away"]}, 
				UpdatedDate = {$updatedDateTime} 
		WHERE 	MatchId = {$matchData["matchid"]}
		AND		UserId = {$userId}
	";
}

function GetJoinLeagueSql($receivePMs, $userId) {

	global $db_prefix;
	
	return	"
		INSERT INTO {$db_prefix}pl_users (
			UserId, 
			ReceivePMs
		) 
		VALUES (
			{$userId}, 
			{$receivePMs}
		)
	";

}

function GetReturnUserCountSql() {

	global $db_prefix;
	
	return "
		SELECT COUNT(UserId) AS UserCount 
		FROM {$db_prefix}pl_users
	";
}

function getReturnTeamMatchesSql($vars) {
	
	global $db_prefix;
	
	$teamId = $vars['teamId'];

	return "
		SELECT 		M.MatchDate,
					T1.Name AS HomeTeamName,
					T2.Name AS AwayTeamName,
					T1.Image AS HomeTeamImage,
					T2.Image AS AwayTeamImage,
					M.WeekId,
					M.HomeScore,
					M.AwayScore
		FROM 		{$db_prefix}pl_matches M
		INNER JOIN	{$db_prefix}pl_teams T1	
		ON 			M.HomeTeamId = T1.TeamId
		INNER JOIN 	{$db_prefix}pl_teams T2
		ON 			M.AwayTeamId = T2.TeamId
		WHERE 		M.HomeTeamId = {$teamId} OR M.AwayTeamId = {$teamId}
		ORDER BY 	WeekId ASC
	";

}

function GetReturnTeamsSql($vars) {

	global $db_prefix;
	
	$orderBy = $vars['orderBy'];

	return "
		SELECT 		T.TeamId,
					T.Name,
					T.Image,
					T.Won,
					T.Drawn,
					T.Lost,
					T.GoalsFor,
					T.GoalsAgainst,
					T.Points,
					(T.Won + T.Lost + T.Drawn) AS Played,
					(CAST(T.GoalsFor AS SIGNED) - CAST(T.GoalsAgainst AS SIGNED)) AS GoalDiff
		FROM		{$db_prefix}pl_teams T
		ORDER BY 	{$orderBy}
	";}

function GetReturnWeeksSql() {

	global $db_prefix;

	return "
		SELECT 		W.WeekId 
		FROM 		{$db_prefix}pl_week W
		ORDER BY	W.WeekId DESC
	";
}

function GetInsertPredictionSql($matchData, $userId) {

	global $db_prefix;
	
	$updatedDateTime = time();

	return "
		INSERT INTO {$db_prefix}pl_predictions (
			MatchId, 
			UserId, 
			WeekId, 
			HomeScore, 
			AwayScore, 
			UpdatedDate
		) 
		VALUES (
			{$matchData["matchid"]}, 
			{$userId}, 
			{$matchData["week"]}, 
			{$matchData["home"]}, 
			{$matchData["away"]}, 
			{$updatedDateTime})
	";
}

function GetPredictionLeagueSql($vars) {

	global $db_prefix;
	
	$limit = $vars['limit'];
	$version = isset($vars["version"]) ? $vars["version"] : 2;
	$sort = $vars['sort'];
	$dir = $vars['dir'];
	$week = $vars['week'];
	
	// We could get version from settings, but want to be explicit here. This column name has
	// changed in the versions, so deal with this here
	if ($version == 1) {
		$realName = 'realName';
	} else {
		$realName = 'real_name';
	}
	
	if ($sort == 'M.realName' && $version != 1) {
		$sort = 'real_name';
	}

	return "
		SELECT 		S.UserId,
					S.Position,
					S.Played,
					S.Won,
					S.Drawn,
					S.Lost,
					S.GoalsFor,
					S.GoalsAgainst,
					S.Points,
					S.PosMove,
					M.{$realName}
		FROM		{$db_prefix}pl_standings S
		INNER JOIN	{$db_prefix}members M
		ON			S.UserId = M.id_member
		WHERE		WeekId = {$week}
		ORDER BY 	{$sort} {$dir}
		{$limit}
	";
}


function GetPredictionsSql($vars) {

	global $db_prefix;
	
	$userId = $vars['userId'];
	$weekId = $vars['weekId'];

	return "
		SELECT 	Y.MatchId,
				Y.Updated,
				T1.Name AS HomeTeamName,
				T1.Image AS HomeImage,
				T2.Name AS AwayTeamName,
				T2.Image AS AwayImage,
				Y.MatchDate,
				Y.HomeScore,
				Y.AwayScore,
				P.HomeScore AS PredHomeScore,
				P.AwayScore AS PredAwayScore,
				P.UpdatedDate,
				P.Points,
				P.Bonus,
				P.PointType
		FROM
		(
			SELECT 		M.MatchID, 
						U.UserID,
						M.WeekId,
						M.MatchDate, 
						M.HomeScore, 
						M.AwayScore, 
						M.HomeTeamId, 
						M.AwayTeamId,
						M.Updated
			FROM 		{$db_prefix}pl_matches M
			CROSS JOIN 	{$db_prefix}pl_users U
		) AS Y
		LEFT OUTER JOIN {$db_prefix}pl_predictions P
		ON 				(P.MatchID = Y.MatchID AND P.UserID = Y.UserID)
		INNER JOIN 		{$db_prefix}pl_teams T1
		ON 				Y.HomeTeamId = T1.TeamId
		INNER JOIN 		{$db_prefix}pl_teams T2
		ON 				Y.AwayTeamId = T2.TeamId
		WHERE 			Y.UserId = {$userId}
		AND 			Y.WeekId = {$weekId}
		ORDER BY 		Y.MatchDate
	";
}

?>