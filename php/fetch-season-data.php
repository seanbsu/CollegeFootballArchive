<?php
include_once 'db.php';
include_once 'db-queries.php';
include_once 'Season-handler.php';
include_once 'football_data_api.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['year']) && isset($_GET['week'])) {
    $selectedYear = intval($_GET['year']);
    $selectedWeek = intval($_GET['week']);

    $data = fetchSeasonData($selectedYear, $selectedWeek);

    if ($data !== false) {
        $connection = dbConnect();
       echo generateGameRows($data, $connection);

    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Data not found']);
    }
} else {
    // Handle invalid requests
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request']);
}
