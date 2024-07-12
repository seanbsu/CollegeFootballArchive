<?php
include_once 'db.php';
include_once 'db-queries.php';
include_once 'football_data_api.php';
if (isset($_GET['team']) && isset($_GET['year'])) {
    $teamName = $_GET['team'];
    $encodedTeamName = urlencode($teamName);
    $selectedYear = $_GET['year'];
    $opponentData = fetchOpponentData($encodedTeamName, $selectedYear);

    if ($opponentData) {
        $connection = dbConnect();
        if ($connection) {
            $opponents = [];
            if ($teamName == 'hawaii' || $teamName == 'Hawaii') {
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
                

            }foreach ($opponents as $opponent) {
                echo '<tr>';
                echo '    <td>';
                echo '        <a class="opponent-link" href="' . $opponent['link'] . '">
                                        <img class="opponent-logo" src="' . $opponent['logo'] . '" alt="' . $opponent['name'] . '-logo">
                                  </a>';
                echo '        ' . $opponent['name'];
                echo '    </td>';
                echo '    <td>' . $opponent['result'] . '</td>';
                echo '    <td>' . $opponent['score'] . '</td>';
                echo '</tr>';
            }
        } else {
            echo "Error fetching opponent data from the API.";
        }
    } else {
        echo "Invalid request.";
    }
}
?>
