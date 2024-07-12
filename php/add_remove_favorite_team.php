<?php
session_start();
require_once '../vendor/autoload.php';
include_once 'db.php';
include_once 'db-queries.php';

$teamName = $_POST['teamName'];
if (!isset($_SESSION['user_id'])) {
   header('Location: login.php?team=' . $teamName);
    exit;
}

if (isset($_POST['action']) && isset($_POST['teamName'])) {
    $connection = dbConnect();
    $action = $_POST['action'];
    $teamId = getTeamIdByName($connection, $teamName);

    if ($action === 'add') {
        $result = addUserFavoriteTeam($connection, $_SESSION['username'], $teamName);

        if ($result) {
            echo 'added';
        } else {
            echo 'error';
        }
    } elseif ($action === 'remove') {
        $result = removeUserFavoriteTeam($connection, $_SESSION['user_id'], $teamId);

        if ($result) {
            echo 'removed';
        } else {
            echo 'error';
        }
    }
    $_SESSION['favoriteTeams'] = getUserFavTeams($connection, $_SESSION['username']);
}
