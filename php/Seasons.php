<?php
include_once './nav.php';
include_once 'db.php';
include_once 'db-queries.php';
include_once 'football_data_api.php';
include_once 'Season-handler.php';

$selectedYear = isset($_GET['year']) ? intval($_GET['year']) : date('Y');
$selectedWeek = isset($_GET['week']) ? intval($_GET['week']) : 1;
?>
<body>
<div class="body-container">
    <ul class="breadcrumb">
        <li><a href="../index.php">Home</a></li>
        <li><a href="Seasons.php">Seasons</a></li>
        <li><a id="breadcrumb-season"><?php echo $selectedYear; ?></a></li>
        <li><a id="breadcrumb-week">Week <?php echo $selectedWeek; ?></a></li>
    </ul>
    <div class="main-indiv-team">
        <h1><?php echo htmlspecialchars($selectedYear); ?> Season</h1>
    </div>

    <div class="outer-wrapper">
        <div class="inner-wrapper">
            <div class="dropdown-container">
                <div class="season-dropdown">
                    <label for="season-select">Season:</label>
                    <select id="season-select-seasons">
                        <?php
                        $currentYear = date('Y');
                        for ($year = $currentYear; $year >= $currentYear - 19; $year--) {
                            $isSelected = ($year == $selectedYear) ? 'selected' : '';

                            echo '<option value="' . $year . '" ' . $isSelected . '>' . $year . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="season-dropdown">
                    <label for="week-select">Week:</label>
                    <select id="week-select">
                        <?php
                        for ($i = 1; $i <= 15; $i++) {
                            echo '<option value="' . $i . '">Week ' . $i . '</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>

            <table class="team-opponent-table">
                <tr>
                    <th>Teams</th>
                    <th>Score</th>
                    <th>Teams</th>
                    <th>Score</th>
                </tr>
                <?php
                $apiData = fetchSeasonData($selectedYear, $selectedWeek);
                $dbConnection = dbConnect();
                echo generateGameRows( $apiData,$dbConnection);
                $dbConnection=null;
                ?>
            </table>
        </div>
    </div>
</div>
</body>
<?php
include_once 'footer.php';
?>
