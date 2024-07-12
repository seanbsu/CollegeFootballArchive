<?php
session_start();
include_once 'db.php';
include_once 'db-queries.php';
include_once 'User-authentication-functions.php';


$errors = [];

// Registration Logic
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST) && isset($_POST['signup'])) {

    $email = strtolower(sanitizeInput($_POST["username"]));
    $emailHash = hash("sha256", $email);
    $fullname = sanitizeInput($_POST["fullname"]);
    $password = sanitizeInput($_POST["password"]);
    $salt = generateSalt();
    $passwordHash = hash("sha256", $password . $salt);
    $confirmPassword = sanitizeInput($_POST["password-confirm"]);

    // Check if the password and confirm password fields match
    if (!validatePassword($password, $confirmPassword)) {
        $_SESSION['form_data'] = $_POST;
        $errors[] = "password-error";
    }
    if (!isset($_POST["terms"])) {
        $_SESSION['form_data'] = $_POST;
        $errors[] = "terms-error";
    }

    // Validate email format
    if (!validateEmail($email)) {
        $_SESSION['form_data'] = $_POST;
        $errors[] = "email-error";
    }

    $connection = dbConnect();
    if($connection == null){
        echo "<div> satabase connection is null</div>";
    }
    // Check if the email is already registered
    if (emailExists($connection, $emailHash)) {
        $errors[] = "email_exists";
    }
    if(empty($errors)){
            try {
                $validationToken = generateValidationToken();
                $encodedToken = urlencode($validationToken);
            } catch (Exception $e) {
                echo "An error occurred while generating the validation token: " . $e->getMessage();
            }
            $tokenExpiration = calculateTokenExpiration();

            if (!insertUser($connection, $fullname, $emailHash, $passwordHash, $salt, $validationToken, $tokenExpiration)) {
                echo "error inserting into db";
            }

            $emailSent = sendVerificationEmail($email, $encodedToken);

            if ($emailSent) {
                $_SESSION["email_sent"] = $email;
                unset($_SESSION['form_data']);
                header("Location: signup.php?email_sent=1");
            } else {
                header("Location: signup.php?email_sent=0");
                exit;

            }
    }
}

// Handle the resend email form submission
if (isset($_POST["resend_email"])) {
    $emailToResend = isset($_POST["email-resend"]) ? strtolower(sanitizeEmail($_POST["email-resend"])) : '';
    $emailHash = hash("sha256", $emailToResend);
    $connection = dbConnect();

    // Check if the email exists
    if (emailExists($connection, $emailHash)) {
        $validationToken = generateValidationToken();
        $encodedToken = urlencode($validationToken);
        $tokenExpiration = calculateTokenExpiration();

        // Update the user's validation token and expiration in the database
        if (setVerificationToken($connection, $validationToken, $tokenExpiration, $emailHash)) {
            $emailSent = sendVerificationEmail($emailToResend, $encodedToken);

            if ($emailSent) {
                $_SESSION["email_sent"] = $emailToResend;
                header("Location: signup.php?email_resent=1");
                exit;
            } else {
                header("Location: signup.php?email_resent=0");
                exit;

            }
        }
    } else {
        $_SESSION['email_not_found'] = true;
        header("Location: signup.php?email_sent=1&error=email_not_found");
        exit;
    }

}



if (isset($_GET["token"])) {
    $encodedToken = $_GET["token"];
    $token = urldecode($encodedToken);
    $connection = dbConnect();
    $user = getUserByToken($connection, $token);

    if ($user && !isTokenExpired($user["verification_token_expiration"])) {
        if (updateUserVerification($connection, $user["email"])) {
            header("Location: Sign-in.php?valid_token=1");
            exit;
        }
    } else {
        header("Location: signup.php?error=invalid_token");
        $errors[] ="invalid_token";
        exit;
    }
}
// Handle invalid or empty form submissions
if (!empty($errors)) {
    $errorQueryString = '';
    foreach ($errors as $error) {
        $errorQueryString .= 'errors=' . urlencode($error) . '&';
    }

    $errorQueryString = rtrim($errorQueryString, '&');

    header("Location: signup.php?" . $errorQueryString);
    exit;
}
$_SESSION['errors'] = $errors;
exit;
?>
