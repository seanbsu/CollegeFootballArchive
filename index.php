
<?php
session_start();
function generateYearDropdown($currentYear) {
    echo '<div class="dropdown">';
    echo '    <button class="dropbtn">Seasons';
    echo '        <i class="fa fa-caret-down"></i>';
    echo '    </button>';
    echo '    <div class="dropdown-content">';
    echo '        <a href="./php/Seasons.php"</a>';
    for ($i = 0; $i < 10; $i++) {
        $year = $currentYear - $i;
        echo '        <a href="./php/Seasons.php?year=' . $year . '">' . $year . '</a>';
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
<!--    <title>--><?php //echo isset($pageTitle) ? $pageTitle . ' - ' : '';  ?><!--</title>-->
    <title>HomePage</title>
    <link rel="icon" type="image/x-icon" href="/images/favicon.ico">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="./styles.css">
</head>
<header>
    <div class="topnav" id="myTopnav">
        <a href="index.php">
            <img src="./images/fb-helmet.png" alt="Logo" class="helmet-logo">
        </a>

        <div class="teams-btn" id="nav-teams">
            <button class="nav-btn"><a href="./php/teams.php">Teams</a></button>
        </div>
        <?php
        generateYearDropdown(date("Y"));
        // Check if the user is logged in (adjust this condition based on your session variable)
        if (isset($_SESSION['user_id'])) {
            // User is logged in, display "Profile" and "Log Out" buttons
            echo '<div id="profile-btn">';
            echo '    <button class="nav-btn"><a href="./php/profile.php">Profile</a></button>';
            echo '</div>';

            echo '<div id="logout-btn">';
            echo '    <button class="nav-btn"><a href="./php/logout.php">Log Out</a></button>';
            echo '</div>';
        } else {
            // User is not logged in, display "Log In" and "Sign Up" buttons
            echo '<div id="login-btn">';
            echo '    <button class="nav-btn"><a href="./php/Sign-in.php">Log In</a></button>';
            echo '</div>';

            echo '<div id="signUp-btn">';
            echo '    <button class="nav-btn"><a href="./php/signup.php">Sign Up</a></button>';
            echo '</div>';
        }
        ?>

    </div>
</header>




<body>
<div class = body-container>
    <div class = "main-home">
        <h1 class = "titles" id = "main-title">College Football Archive</h1>
        <div>
            <h2 class = "titles" id = "sub-title"> The place for College Football Scores</h2>
        </div>
        <div class = "main-btns">
        <span>
            <button class="main-body-btn" id="main-teams-btn" href="/php/teams.php">
                <a  href="./php/teams.php" >Teams</a>
            </button>
            <button class="main-body-btn"  id="main-seasons-btn" href="./php/seasons.php">
                <a href="./php/Seasons.php" >Seasons</a>
            </button>
        </span>
        </div>
    </div>
</div>
<script src="" async defer></script>
</body>
<footer>
    <div class="footer-logo">
        <a href="index.php">
            <img src="./images/fb-helmet.png" alt="Logo" class="helmet-logo">
        </a>
    </div>
    <div class="footer-links">
        <a href="./php/Contact-Us.php">Contact Us</a>
        <a href="./php/terms.php">Terms of Use</a>
    </div>
</footer>
</html>