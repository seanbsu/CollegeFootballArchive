<?php

function generateGameRows($apiData, $connection) {
    if (is_array($apiData)) {
        $gameRows = '';

        for ($i = 0; $i < count($apiData); $i += 2) {
            $game1 = $apiData[$i];
            $game2 = isset($apiData[$i + 1]) ? $apiData[$i + 1] : null;

            $gameRows .= '<tr>';
            $gameRows .= '<td>';
            $gameRows .= '<div class="team1">';
            // Add an anchor tag with a link to the team page on the logo
            $gameRows .= '<a class="team-link" href="team.php?team=' . urlencode($game1['home_team']) . '">';
            $homeTeamLogo = getImageRef($connection, $game1['home_team']);
            $gameRows .= '<img class="opponent-logo" src="' . $homeTeamLogo . '" alt="' . $game1['home_team'] . '-logo">';
            $gameRows .= '</a>';
            $gameRows .= $game1['home_team'];
            $gameRows .= '</div>';
            $gameRows .= '<div class="team2">';
            // Add an anchor tag with a link to the team page on the logo
            $gameRows .= '<a class="team-link" href="team.php?team=' . urlencode($game1['away_team']) . '">';
            $awayTeamLogo = getImageRef($connection, $game1['away_team']);
            $gameRows .= '<img class="opponent-logo" src="' . $awayTeamLogo . '" alt="' . $game1['away_team'] . '-logo">';
            $gameRows .= '</a>';
            $gameRows .= $game1['away_team'];
            $gameRows .= '</div>';
            $gameRows .= '</td>';
            $gameRows .= '<td class="scorecard">';
            $gameRows .= '<div class="score1">' . $game1['home_points'] . '</div>';
            $gameRows .= '<div class="score2">' . $game1['away_points'] . '</div>';
            $gameRows .= '</td>';

            if ($game2 !== null) {
                $gameRows .= '<td>';
                $gameRows .= '<div class="team1">';
                // Add an anchor tag with a link to the team page on the logo
                $gameRows .= '<a class="team-link" href="team.php?team=' . urlencode($game2['home_team']) . '">';
                $homeTeamLogo2 = getImageRef($connection, $game2['home_team']);
                $gameRows .= '<img class="opponent-logo" src="' . $homeTeamLogo2 . '" alt="' . $game2['home_team'] . '-logo">';
                $gameRows .= '</a>';
                $gameRows .= $game2['home_team'];
                $gameRows .= '</div>';
                $gameRows .= '<div class="team2">';
                // Add an anchor tag with a link to the team page on the logo
                $gameRows .= '<a class="team-link" href="team.php?team=' . urlencode($game2['away_team']) . '">';
                $awayTeamLogo2 = getImageRef($connection, $game2['away_team']);
                $gameRows .= '<img class="opponent-logo" src="' . $awayTeamLogo2 . '" alt="' . $game2['away_team'] . '-logo">';
                $gameRows .= '</a>';
                $gameRows .= $game2['away_team'];
                $gameRows .= '</div>';
                $gameRows .= '</td>';
                $gameRows .= '<td class="scorecard">';
                $gameRows .= '<div class="score1">' . $game2['home_points'] . '</div>';
                $gameRows .= '<div class "score2">' . $game2['away_points'] . '</div>';
                $gameRows .= '</td>';
            } else {
                $gameRows .= '<td></td><td></td>';
            }

            $gameRows .= '</tr>';
        }
        $connection = null;
        return $gameRows;
    }

    return '';
}


function generateSeasonOptions($selectedYear){
    $currentYear = date('Y');
    for ($i = $currentYear; $i >= $currentYear - 20; $i--) {
        $selected = ($i == $selectedYear) ? 'selected' : '';
        echo '<option value="' . $i . '" ' . $selected . '>' . $i . '</option>';
    }
}

