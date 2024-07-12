<?php
include_once 'db.php';
include_once 'db-queries.php';
include_once 'football_data_api.php';


$teamName = isset($_GET['team']) ? $_GET['team'] : "Default Team";
$encodedTeamName = urlencode($teamName);
$currentYear = date('Y');
$opponentData = fetchOpponentData($encodedTeamName, $currentYear);

if (!$opponentData) {
    echo "Error fetching opponent data from the API.";
    exit;
}

$connection = dbConnect();

if ($connection) {
    $opponents = [];
        if($teamName == 'hawaii' || $teamName == 'Hawaii'){
            $teamName = 'Hawai\'i';
        }
    foreach ($opponentData as $game) {
        if ($game->home_team === $teamName) {
            $opponent = $game->away_team;
            $teamScore = $game->home_points;
            $opponentScore = $game->away_points;
        } elseif ($game->away_team === $teamName) {
            $opponent = $game->home_team;
            $teamScore = $game->away_points;
            $opponentScore = $game->home_points;
        } else {
            continue;
        }

        if ($teamScore === null || $opponentScore === null) {
            $result = " ";
        } elseif ($teamScore > $opponentScore) {
            $result = 'W';
        } else {
            $result = 'L';
        }

        $opponentLink = "team.php?team=" . urlencode($opponent);
        $opponentImageRef = getImageRef($connection, $opponent);

        $formattedScore = $teamScore !== null ? $teamScore . '-' . $opponentScore : '';


        $opponents[] = [
            'logo' => $opponentImageRef,
            'name' => $opponent,
            'result' => $result,
            'score' => $formattedScore,
            'link' => $opponentLink
        ];
    }
    $teamLogoPath = getImageRef($connection,$teamName);
    $nickName = getTeamNickName($connection,$teamName);
    $connection = null;
} else {
    echo "Database connection failed.";
}

include_once'team-template.php';
?>
