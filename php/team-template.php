
<?php
$pageTitle = isset($teamName) ? $teamName . ' Team Page' : 'Team Page';
include_once("nav.php");
?>
<div class = body-container>
<ul class="breadcrumb">
    <li><a href="../index.php">Home</a></li>
    <li><a href="teams.php">Teams</a></li>
    <li><a id="breadcrumb-team"><?php echo isset($teamName) ? $teamName : ''; ?></a></li>
</ul>

<div class="main-indiv-team">
    <div class="center-content">
        <div class="logo-name-star">
            <?php
            if (isset($teamLogoPath) && !empty($teamLogoPath)) {
                echo '<img  src="' . $teamLogoPath . '">';
            }
            ?>
            <div class="name-nickname">
                <h1><?php echo isset($teamName) ? $teamName : ''; ?></h1>
                <p><?php echo isset($nickName) ? 'Nick Name: ' . $nickName : ''; ?></p>
            </div>
            <?php
            $isFavorite = false;
            if (isset($_SESSION['favoriteTeams'])) {
                $favoriteTeams = $_SESSION['favoriteTeams'];
                foreach ($_SESSION['favoriteTeams'] as $favoriteTeam) {
                    if ($favoriteTeam['team_name'] === $teamName) {
                        $isFavorite = true;
                        break;
                    }
                }
            }
            echo '<label class="favorite-star">';
            echo '<input type="checkbox" ' . ($isFavorite ? 'checked' : '') . '>';
            echo '<span>&#9733;</span>'; // Star icon
            echo '</label>';
            ?>
        </div>
    </div>
</div>

<div class="outer-wrapper">
    <div class="inner-wrapper">
        <div class="dropdown-container">
            <div class="season-dropdown">
                <label for="season-select">Season:</label>
                <select id="season-select-team">
                   <?php $currentYear = date('Y');
                    for ($year = $currentYear; $year >= $currentYear - 19; $year--) {
                    $isCurrentSeason = ($year == $currentYear) ? 'selected' : '';

                    echo '<option value="' . $year . '" ' . $isCurrentSeason . '>' . $year . '</option>';
                    }
                    ?>
                </select>
            </div>
        </div>

        <table class="team-opponent-table">
            <tr>
                <th>Opponent</th>
                <th>W/L</th>
                <th>Score</th>
            </tr>
            <?php
            if (isset($opponents) && is_array($opponents)) {
                foreach ($opponents as $opponent) {
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
            }
            ?>
        </table>
    </div>
</div>
</div>
<?php
include_once("footer.php");
?>

