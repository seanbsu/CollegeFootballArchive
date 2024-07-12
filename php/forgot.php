<?php
include_once 'nav.php';
include_once 'User-authentication-functions.php';
?>
    <body>
    <div class="center-container">
        <div class="popup-email-notification-msg">
            <div id="email-sent-title">
                <h2>Change Password</h2>
            </div>
            <form >
                <?php
                if (isset($_GET["email"])) {
                    $userEmail = sanitizeInput(urldecode($_GET["email"]));
                    echo "<p>Check your email at $userEmail.<br><br>";
                    echo "Make sure you check your SPAM folder as well<br>";
                    echo "If you still don't receive the email, please email us at collegefbarchive@gmail.com</p>";
                } else {
                    echo "<p>Email not found.</p>";
                }
                ?>
                <div id="email-sent-footer">
                    <button class="close-button">Close</button>
                </div>
            </form>
        </div>
    </div>

    <form action="forgot-handler.php" method="POST" class="change-password-form" >
        <div class="row">
            <h1>Forgot Password</h1>
            <h6 class="information-text">Enter your registered email to reset your password.</h6>
            <div class="form-group">
                <input type="email" name="user_email" id="user_email">
                <p><label>Email</label></p>
                <button name="reset_password">Reset Password</button>
            </div>
        </div>
    </form>

    <div class="center-container">
        <div class="change-password">
            <form action="forgot-handler.php" method="POST" >
                <h2>Change Password</h2>
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
                <input type="hidden" name="user_email" value="<?php echo htmlspecialchars(sanitizeEmail( $_GET['email'])); ?>">
                <button type="submit" name ="password-change" >Reset Password</button>
            </form>
        </div>
    </div>

    <div class="center-container">
        <div class="password-changed">
            <form>
                <?php if (isset($_GET["password_changed"])) {
                    echo '<img src="../images/checkmark.png" alt="Checkmark">';
                echo '<h2>Password Changed</h2> <p>Your password has been changed successfully.</p>';
                echo '<button type="submit" formaction="Sign-in.php">Sign In</button>';
                }
                ?>
            </form>
        </div>
    </div>

    </body>
<?php
require_once 'footer.php';
