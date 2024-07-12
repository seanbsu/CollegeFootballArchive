<?php
include 'nav.php';
if (isset($_SESSION['email_not_found'])) {
    echo '<script>var emailNotFound = true;</script>';
    unset($_SESSION['email_not_found']);
} else {
    echo '<script>var emailNotFound = false;</script>';
}
?>
    <body>
    <div class="center-container">
        <div class="popup-signup-init">
            <div id="signup-title-init">
                <h2>Sign Up for CollegeFB-Archive</h2>
                <form name="signup" id="signUp-init" method="post" action="signup-handler.php" value ="1">
                    <input type="hidden" name="signup" value="1">
                    <p>Email</p>
                    <input type="text" name="username" id="email" class="entry" placeholder="Email"
                            value="<?php echo isset($_SESSION['form_data']['username']) ? $_SESSION['form_data']['username'] : ''; ?>">
                    <p  id ="email-error">* Email invalid</p>
                    <p  id ="email_exists">* Email is already associated with an account</p>
                    <p>Full Name</p>
                    <input type="text" name="fullname" id="fullname" class="entry" placeholder="Full Name"
                           value="<?php echo isset($_SESSION['form_data']['fullname']) ? $_SESSION['form_data']['fullname'] : ''; ?>">
                    <p>Password</p>
                    <input type="password" name="password" id="password" class="entry" placeholder="Password">
                    <p>Confirm Password</p>
                    <input type="password" name="password-confirm" id="password-confirm" class="entry" placeholder="Confirm Password">
                    <p  id="password-error">* Password Mismatch</p>
                    <div class="terms-container">
                        <input type="checkbox" id="terms" name="terms">
                        <label for="terms">I agree to the </label><a id="terms-link" href="#">Terms of Service</a>
                    </div>
                    <div  id="terms-error">
                        <p>* You must accept the Terms of Service to continue</p>
                    </div>
                    <input type="submit" value="Continue" id="signup-footer-init">
                </form>
            </div>
        </div>
    </div>

    <div class="center-container">
        <div class="popup-signup">
            <div id="email-sent-title-signup">
                <h2>Sign Up for CollegeFB-Archive</h2>
            </div>
            <div id="email-sent-body-signup">
                <?php
                if (isset($_SESSION["email_sent"])) {
                    $userEmail = htmlspecialchars($_SESSION["email_sent"]);
                    echo "<p>We sent an account verification email to $userEmail.<br><br>";
                    echo "Please click the link in the email in order to verify your account and continue.<br><br>";
                    echo "You can close this page now.</p>";
                } else {
                    echo "<p>Email not found.</p>";
                }
                ?>
            </div>
            <div id="email-sent-footer-signup">
                <p>Did not get the email? </p><a id="resend-email-link" href="#">[Click here]</a>
            </div>
        </div>
    </div>

    <div class="center-container">
        <div class="popup-signup-resend">
            <div id="email-resend-title-signup">
                <h2>Resend Verification Email</h2>
            </div>
            <form method="POST" action="signup-handler.php" name="resend-input">
                <input type="hidden" name="resend" value="1">
                <div id="email-resend-body-signup">
                    <p>Did not receive a verification email? <br> Let's try sending it again!<br><br>
                    </p>
                    <label for="email">Email:</label><br>
                    <input class="entry" type="email" id="email-resend" name="email-resend" required
                           value="<?php echo isset($_SESSION['form_data']['email-resend']) ? $_SESSION['form_data']['email-resend'] : ''; ?>">
                    <p  id ="resend-email-error">* Email invalid</p>
                </div>
                <div id="email-resend-footer-signup">
                    <a href="../index.php">Go Back</a>
                    <input id="resend-email-submit" type="submit" name="resend_email" value="Resend">
                </div>
            </form>
        </div>
    </div>

    <div class="center-container">
        <div class="popup-email-resent">
            <div id="email-resent-title-signup">
                <h2>Sign Up for CollegeFB-Archive</h2>
            </div>
            <div id="email-resent-body-signup">
                <p>Check your email at <?php echo isset($_SESSION['form_data'] ) ? $_SESSION['form_data']['email-resend'] :''; ?>.<br><br>
                    Make sure your check your SPAM folder as well!<br><br>
                    If you still don't receive the email, please email us at [collegefbarchive@gmail.com].</p>
            </div>
            <div id="email-resent-footer-signup">
                <input id="resent-email-close" type="submit" value="Close">
            </div>
        </div>
    </div>

    <div class="center-container">
        <div class="sign-in-form-signup">
            <div id="signup-login-title">
                <h2>Please Log In to Complete Email Verification</h2>
            </div>
            <form method="POST" action="signup-handler.php">
                <div id="signup-login-body">
                    <label for="email" require>Email:</label><br>
                    <input class="entry" type="email" id="email-login-signup" name="email-login-signup" required>
                    <label for="password" require>Password:</label><br>
                    <input class="entry" type="password" id="password-login-signup" name="password-login-signup" required>
                </div>
                <div id="login-error-msg-signup">
                    <p id="login-error-msg">*Invalid username <span id="error-msg-second-line">and/or password</span></p>
                </div>
                <div id="signup-login-footer">
                    <input id="login" type="submit" name="login" value="Log In">
                </div>
            </form>
        </div>
    </div>

    </body>
<?php include 'footer.php';
?>