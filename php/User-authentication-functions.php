<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * Validate Email
 *
 * This function checks the format of an email address to determine if it's valid.
 *
 * @param string $email The email address to validate.
 *
 * @return mixed Returns the validated email address (if valid) or `false` (if invalid).
 */
function validateEmail($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}


/**
 * Validate Password Match
 *
 * This function compares two passwords to check if they match.
 *
 * @param string $password The first password.
 * @param string $confirmPassword The second password for confirmation.
 *
 * @return bool Returns `true` if the passwords match; otherwise, returns `false`.
 */
function validatePassword($password, $confirmPassword)
{
    return $password === $confirmPassword || $password === null;
}


/**
 * Sanitize Input
 *
 * This function takes a user input and sanitizes it to prevent common security risks, such as SQL injection,
 * by removing leading/trailing whitespace and applying context-based filtering.
 *
 * @param string $input The user input to sanitize.
 *
 * @return string The sanitized input as a string.
 */
function sanitizeInput($input)
{
    $sanitizedInput = filter_var($input, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    return $sanitizedInput;
}

function sanitizeEmail($input)
{
    $sanitizedInput = filter_var($input, FILTER_SANITIZE_EMAIL);
    return $sanitizedInput;
}

/**
 * Generate Salt
 *
 * This function generates a random hexadecimal salt of the specified length for use in password hashing.
 *
 * @param int $length (optional) The length of the generated salt in bytes. Default is 16 bytes.
 *
 * @return string Returns the generated salt as a hexadecimal string.
 *
 * @throws Exception Throws an exception if random bytes cannot be generated securely.
 */
function generateSalt($length = 16){
    $salt = bin2hex(random_bytes($length));
    return $salt;
}


/**
 * Generate Validation Token
 *
 * This function generates a random validation token typically used for user account verification.
 *
 * @param int $length (optional) The length of the generated token in bytes. Default is 32 bytes.
 *
 * @return string Returns the generated validation token as a hexadecimal string.
 *
 * @throws Exception Throws an exception if random bytes cannot be generated securely.
 */
function generateValidationToken($length = 32){
    $token = bin2hex(random_bytes($length));
    return $token;
}


/**
 * Calculate Token Expiration
 *
 * This function calculates the expiration time for a token, typically used for setting a validation token's expiration.
 * The expiration time is one hour (3600 seconds) from the current time.
 *
 * @return int Returns the expiration time as a Unix timestamp (seconds since January 1, 1970).
 */
function calculateTokenExpiration(){
        $currentTime = time();
        $expirationTime = $currentTime + 3600;
        $expirationTimeDatetime = date('Y-m-d H:i:s', $expirationTime);

    return $expirationTimeDatetime;
}
/**
 * Check if Token is Expired
 *
 * This function checks if a given token expiration timestamp has passed, indicating that the token is expired.
 *
 * @param int $tokenExpirationTimestamp The timestamp representing the token's expiration time (Unix timestamp).
 *
 * @return bool Returns `true` if the token is expired; `false` if it is not expired.
 */
function isTokenExpired($tokenExpirationTimestamp) {
    $currentTime = time();
    if ($currentTime > $tokenExpirationTimestamp) {
        return true;
    } else {
        return false;
    }
}

/**
 * Send Verification Email
 *
 * This function  sends a verification email to the user,
 * and includes the verification link with the token in the email.
 *
 * @param string $recipientEmail The recipient's email address.
 * @param $verificationToken
 * @return bool Returns true if the email was sent successfully; otherwise, false.
 */
function sendVerificationEmail($recipientEmail, $verificationToken ) {
    require __DIR__ . '/../vendor/autoload.php';
    $mail = new PHPMailer(true);

    try {
        if (file_exists('C:/wamp64/www/collegefbarchive/.env')) {
            $dotenv = Dotenv\Dotenv::createImmutable('C:/wamp64/www/collegefbarchive');
            $dotenv->load();
            $mail->Host =  $_ENV['SMTP_HOST'];
            $mail->Username =  $_ENV['SMTP_USERNAME'];
            $mail->Password =  $_ENV['SMTP_PASSWORD'];
            $mail->setFrom($_ENV['SITE_EMAIL'], 'College Football Archive');
            $verificationLink = 'http://localhost:80/collegefbarchive/php/signup-handler.php?token=' . $verificationToken;
        }else{
            $mail->Host =  getenv('SMTP_HOST');
            $mail->Username =  getenv('SMTP_USERNAME');
            $mail->Password =  getenv('SMTP_PASSWORD');
            $mail->setFrom(getenv('SITE_EMAIL'), 'College Football Archive');
            $verificationLink = 'https://collegefbarchive-dd56ff4da4ce.herokuapp.com/php/signup-handler.php?token=' . $verificationToken;
        }
        // Server settings
        $mail->isSMTP();
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
        // Recipient
        $mail->addAddress($recipientEmail);
        // Email content
        $mail->isHTML(true); // Set to true for HTML emails
        $mail->Subject = 'Email Verification';
        // HTML content
        $htmlContent = '
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Email Verification</title>
            </head>
            <body style="font-family: Arial, sans-serif; padding: 20px; margin: 0;">
                <!-- Your HTML content here -->
                <p>Hello,</p>
                <p>Thank you for signing up! Please click the following link to verify your email:</p>
                <p><a href="' . $verificationLink . '" style="color: #007bff; text-decoration: none;">Verify Here</a></p>
                <p>If you didn\'t sign up for our service, please ignore this email.</p>
                <p>Best regards,<br>CollegeFBArchive Team</p>
            </body>
            </html>
        ';

        $mail->Body = $htmlContent;

        $mail->send();
        return true;
    } catch (Exception $e) {
        false;
    }
}

/**
 *  Send Password Reset Email
 *
 *  This function  sends an  email to the user,
 *  and includes the verification link with the token in the email to reset the password
 * @param $recipientEmail The recipient's email address.
 * @param $verificationToken The verification token to attach to the link
 * @return bool Returns true if the email was sent successfully; otherwise, false.
 */
function sendPassResetEmail($recipientEmail, $verificationToken ) {
    require __DIR__ . '/../vendor/autoload.php';
    $mail = new PHPMailer(true);

    try {
        if (file_exists('C:/wamp64/www/collegefbarchive/.env')) {
            $dotenv = Dotenv\Dotenv::createImmutable('C:/wamp64/www/collegefbarchive');
            $dotenv->load();
            $mail->Host =  $_ENV['SMTP_HOST'];
            $mail->Username =  $_ENV['SMTP_USERNAME'];
            $mail->Password =  $_ENV['SMTP_PASSWORD'];
            $mail->setFrom($_ENV['SITE_EMAIL'], 'College Football Archive');
            $verificationLink = 'http://localhost:80/collegefbarchive/php/forgot-handler.php?token=' . $verificationToken;
        }else{
            $mail->Host =  getenv('SMTP_HOST');
            $mail->Username =  getenv('SMTP_USERNAME');
            $mail->Password =  getenv('SMTP_PASSWORD');
            $mail->setFrom(getenv('SITE_EMAIL'), 'College Football Archive');
            $verificationLink = 'https://collegefbarchive-dd56ff4da4ce.herokuapp.com/php/forgot-handler.php?token=' . $verificationToken;
        }

        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp-relay.brevo.com';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
        $mail->SMTPAuth = true;
        // Recipient
        $mail->addAddress($recipientEmail);
        // Email content
        $mail->isHTML(true);
        $mail->Subject = 'Password Reset';
        // HTML content
        $htmlContent = '
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Password Reset Request</title>
            </head>
            <body style="font-family: Arial, sans-serif; padding: 20px; margin: 0;">
                <!-- Your HTML content here -->
                <p>Hello,</p>
                <p>We recieved a request for a password change. Please click the following link to reset your password:</p>
                <p><a href="' . $verificationLink . '" style="color: #007bff; text-decoration: none;">Reset Password</a></p>
                <p>If you didn\'t request a passowrd change, please ignore this email.</p>
                <p>Best regards,<br>CollegeFBArchive Team</p>
            </body>
            </html>
        ';

        $mail->Body = $htmlContent;

        $mail->send();
        return true;
    } catch (Exception $e) {
        false;
    }
}
