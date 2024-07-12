<?php
require '../vendor/autoload.php';

if (file_exists('C:/wamp64/www/collegefbarchive/.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable('C:/wamp64/www/collegefbarchive');
    $dotenv->load();
}

/** Function that initiales an api call and returns the results of the call
 * @param string $endpoint The API endpoint URL.
 * @param array $headers An array of HTTP headers.
 * @return bool|string The API response or false on failure.
 */
function initiateAPICall($endpoint, $headers) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $endpoint);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo 'cURL Error: ' . curl_error($ch);
    }

    curl_close($ch);
    return $response;
}


function createHeader(){

    if (file_exists('C:/wamp64/www/collegefbarchive/.env')) {
        $api_key = $_ENV['API_KEY'];
    }else{
        $api_key = getenv('API_KEY');
    }
    $headers = array(
        "accept: application/json",
        "Authorization: bearer " . $api_key
    );
    return $headers;
}
/**
 * Function to make an API call to get data on a team's opponents.
 *
 * @param string $teamName The name of the team.
 * @param int $year The year for which you want to fetch data.
 * @return mixed|null The API response data or null on failure.
 */
function fetchOpponentData($teamName, $year) {
    //special case to handle hawaii's football team name
    if($teamName == 'Hawaii' || $teamName == 'hawaii'){
        $teamName = 'Hawai\'i';
    }
    $endpoint = "https://api.collegefootballdata.com/games?year={$year}&team={$teamName}" ;
    $headers = createHeader();

    $response = initiateAPICall($endpoint, $headers);

    $data = json_decode($response);

    if ($data === null) {
        echo "\nError decoding JSON\n\n";
        return null;
    } else {
        return $data;
    }
}

/**
 * Fetches college football season data from the College Football Data API based on the selected year and week.
 *
 * @param int $year The year for which to fetch the data.
 * @param int $week The week of the season (1-18) for which to fetch the data.
 *
 * @return array|null An array containing the fetched data or null on failure.
 */
/**
 * Function to fetch season data from the API.
 *
 * @param int $year The year for which you want to fetch data.
 * @param int $week The week for which you want to fetch data.
 * @return array|false The API response data as an array, or false on failure.
 */
function fetchSeasonData($year, $week) {
    $endpoint = "https://api.collegefootballdata.com/games?year={$year}&week={$week}&seasonType=regular";
    $headers = createHeader();
    $response = initiateAPICall($endpoint, $headers);
    $data = json_decode($response,true);

    if ($data === null) {
        echo "Error decoding JSON";
        return false;
    } else {
        return $data;
    }
}

/**
 * Function to retrieve the team's nickname from an API call.
 *
 * @param string $teamName The name of the team to search for.
 * @return string|false The team's mascot (nickname) or false on failure.
 */
function getTeamNicknameFromAPI($teamName)
{
    if (strcasecmp($teamName, 'Hawaii') === 0) {
        $teamName = 'Hawai\'i';
    }

    $apiEndpoint = "https://api.collegefootballdata.com/teams";

    $headers = createHeader();
    $response = initiateAPICall($apiEndpoint, $headers);

    if (!$response) {
        return false;
    }
    $data = json_decode($response, true);

    foreach ($data as $team) {
        if (strcasecmp($team['school'], $teamName) === 0) {
            return $team['mascot'];
        }
    }
    return false;
}




