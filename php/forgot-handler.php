<?php
include_once 'db.php';
include_once 'db-queries.php';
include_once 'User-authentication-functions.php';

$connection = dbConnect();
if (isset($_POST['reset_password'])) {
    $email = strtolower(sanitizeInput($_POST['user_email']));
    $emailHash = hash("sha256", $email);
    if (emailExists($connection, $emailHash)) {
        $token = bin2hex(random_bytes(32));
        $tokenExpiration = calculateTokenExpiration();
        if (setResetToken($connection, $token, $tokenExpiration, $emailHash)) {
            if(sendPassResetEmail($email, $token)){
               header('Location: forgot.php?email_sent=1&email='.$email);
            }
            exit();
        } else {
            header('Location: forgot.php?email_sent=0');
        }
    } else {
        header('Location: forgot.php?email_found=0');
    }
}

// check the token on the link to validate that they requested the change
if (isset($_GET["token"])) {
    $encodedToken = sanitizeInput($_GET["token"]);
    $token = urldecode($encodedToken);
    $connection = dbConnect();
    $user = getUserByToken($connection, $token);
    if ($user && !isTokenExpired($user["reset_token_expiration"])) {
        $email = $user['email'];
        header('Location: forgot.php?show_reset_form=1&email=' . $email);
    } else {
        header('Location: forgot.php?show_reset_form=0');
    }
}


// Check if the "Change Password" form was submitted and if the passwords match
// update their password in the db then send code to indicate
if (isset($_POST['password-change'])) {
    $newPassword = sanitizeInput($_POST['new-password']);
    $confirmPassword = sanitizeInput($_POST['confirm-password']);
    $email = sanitizeInput($_POST['user_email']);

    if ($newPassword === $confirmPassword) {
        if (updatePassword($connection, $email, $newPassword)) {
            header('Location: forgot.php?password_changed=1');
        } else {
            header('Location: forgot.php?password_changed=0');
        }
    } else {
        header('Location: forgot.php?password_error=1');
    }
}



