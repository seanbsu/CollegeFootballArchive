<?php
session_start();
function generateYearDropdown($currentYear) {
    echo '<div class="dropdown">';
    echo '    <button class="dropbtn">Seasons';
    echo '        <i class="fa fa-caret-down"></i>';
    echo '    </button>';
    echo '    <div class="dropdown-content">';

    for ($i = 0; $i < 10; $i++) {
        $year = $currentYear - $i;
        echo '        <a href="Seasons.php?year=' . $year . '">' . $year . '</a>';
    }

    echo '    </div>';
    echo '</div>';
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - ' : '';  ?></title>
    <link rel="icon" type="image/x-icon" href="/images/favicon.ico">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../styles.css">
</head>
<header>
    <div class="topnav" id="myTopnav">
        <a href="../index.php">
            <img src="../images/fb-helmet.png" alt="Logo" class="helmet-logo">
        </a>

        <div class="teams-btn" id="nav-teams">
            <button class="nav-btn"><a href="teams.php">Teams</a></button>
        </div>

        <?php
        generateYearDropdown(date("Y"));

        if (isset($_SESSION['user_id'])) {
            echo '<div id="profile-btn">';
            echo '    <button class="nav-btn"><a href="profile-handler.php">Profile</a></button>';
            echo '</div>';

            echo '<div id="logout-btn">';
            echo '    <button class="nav-btn"><a href="logout.php">Log Out</a></button>';
            echo '</div>';
        } else {
            echo '<div id="login-btn">';
            echo '    <button class="nav-btn"><a href="Sign-in.php">Log In</a></button>';
            echo '</div>';

            echo '<div id="signUp-btn">';
            echo '    <button class="nav-btn"><a href="signup.php">Sign Up</a></button>';
            echo '</div>';
        }
        ?>
    </div>
</header>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="../javascript/script.js"></script>
<script src="https://cdn.jsdelivr.net/gh/akjpro/form-anticlear/base.js"></script>



