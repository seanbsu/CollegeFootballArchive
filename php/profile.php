<?php
include_once 'nav.php';
$userFullName = $_SESSION['username'];
$favoriteTeams = $_SESSION['favoriteTeams'];
$userEmail = $_SESSION['user_email'];

/**
 * Function to populate the favorite teams table
 * @param $favoriteTeams - array of favorite teams
 * @return string - html string to populate the table
 */
function populateFavoriteTeams($favoriteTeams) {
    foreach ($favoriteTeams as $team) {
        echo '<tr>';
        echo '<td>';
        echo '<a href="' . $team['team_name'] . '">';
        echo '<a href="team.php?team=' . urlencode($team['team_name']) . '"><img class="opponent-logo" src="' . $team['logo_reference'] . '" alt="' . $team['team_name'] . '-logo"></a>';
        echo $team['team_name'];
        echo '</a>';
        echo '</td>';
        echo '</tr>';
    }
}
?>
    <body>
    <div class="user-profile">
        <h1 class="profile-heading">Welcome <span id="user-full-name"><?php echo $userFullName; ?></span></h1>

        <div class="user-info">
            <h2 class="profile-heading">Info</h2>
            <p><label>Full Name:</label> <span id="user-full-name-label"><?php echo $userFullName; ?></span></p>
            <p><label>Email:</label> <span id="user-email"><?php  echo $userEmail; ?></span></p>
        </div>

        <form class="change-password-profile" action="profile-handler.php" method="POST">
            <h2>Change Password</h2>
            <div class="form-group">
                <label for="old-password">Old Password</label>
                <input type="password" id="old-password" name="old-password">
            </div>
            <span id="old-password-error">Incorrect Password</span>
            <div class="form-group">
                <label for="new-password">New Password</label>
                <input type="password" id="new-password" name="new-password">
            </div>
            <div class="form-group">
                <label for="confirm-password">Confirm Password</label>
                <input type="password" id="confirm-password" name="confirm-password">
            </div>
            <span class="passwords-match">Passwords match</span>
            <span class="password-error">Passwords don't match</span>
            <span id="password-changed">Password Changed Successfully!</span>
            <button type="submit">Reset Password</button>
        </form>

        <div class="favorite-teams">
            <h2>Favorite Teams</h2>
            <table class="fav-team-table">
                <?php echo populateFavoriteTeams($favoriteTeams); ?>
            </table>
        </div>
    </div>
    </body>


<?php
include_once 'footer.php';