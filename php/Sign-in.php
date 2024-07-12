<?php
include_once 'nav.php';
?>
    <body>
    <div class="center-container">
        <div class="login-container">
            <div class="sign-in-form">
                <h1 id="login-header">Log In</h1>
                <form id="login-form" method="post" action="sign-in-handler.php" >
                    <p>Email</p>
                    <input type="text" name="username" id="username-field" class="login-form-field" placeholder="Email">
                    <p>Password</p>
                    <input type="password" name="password" id="password-field" class="login-form-field" placeholder="Password">
                    <br>
                    <div id="forgot-password-link">
                        <a href="forgot.php">Forgot Password?</a>
                    </div>
                    <input type="submit" value="Login" id="login-form-submit" name="Login-Form-Login-Button">
                    <div id="login-error-msg-holder">
                        <p id="login-error-msg">*Invalid username <span id="error-msg-second-line">and/or password</span></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </body>

    </body>

<?php
include_once 'footer.php';
