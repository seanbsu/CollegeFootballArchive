<?php
include_once ('db.php');
include_once ('db-queries.php');

function generateTeamTable($teams) {
    $teamsPerColumn = ceil(count($teams) / 4);
    $numColumns = 4;

    for ($i = 0; $i < $teamsPerColumn; $i++) {
        echo '<tr>';
        for ($j = 0; $j < $numColumns; $j++) {
            $index = $i + $j * $teamsPerColumn;
            if ($index < count($teams)) {
                $teamName = $teams[$index];
                $teamPageLink = "team.php?team=" . urlencode($teamName);
                echo '<td><a href="' . $teamPageLink . '">' . $teamName . '</a></td>';
            } else {
                echo '<td></td>';
            }
        }
        echo '</tr>';
    }
}

function generateTeamList($searchTerm) {
    $connection = dbConnect();
    if ($connection) {
        $teams = fetchTeamNames($connection, $searchTerm);
        generateTeamTable($teams);
        $connection = null;
    } else {
        echo("Database connection failed. Check wil database admin and make sure you have the correct credentials.");
    }
}
$searchTerm = isset($_GET['search']) ? $_GET['search'] : null;
generateTeamList($searchTerm);

