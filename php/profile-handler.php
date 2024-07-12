<?php
include_once 'db.php';
include_once 'db-queries.php';
session_start();
$errors = [];
// Check if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$connection = dbConnect();
$user = getUserById($connection, $_SESSION['user_id']);
if(!$user){
    header('Location: login.php');
    exit;
}
$_SESSION['username'] = $user['name'];
$favoriteTeams = getUserFavTeams($connection, $user['name']);
$_SESSION['favoriteTeams'] = $favoriteTeams;
$connection = null;

if(isset($_POST["old-password"]) && isset($_POST["new-password"]) && isset($_POST["confirm-password"])){
    $connection = dbConnect();
    $user = getUserById($connection, $_SESSION['user_id']);
    $oldPassword = $_POST["old-password"];
    $newPassword = $_POST["new-password"];
    $confirmPassword = $_POST["confirm-password"];
    if($newPassword == $confirmPassword){
        $_SESSION['old-password'] = $oldPassword;
        $_SESSION['new-password'] = $newPassword;
        $_SESSION['confirm-password'] = $confirmPassword;
        $oldPassHash = hash( "sha256",$oldPassword.$user['password_salt']);
        if($oldPassHash == $user['password_hash']){
            $hashedPassword = hash( "sha256",$newPassword);
            updateUserPassword($connection, $user['user_id'], $newPassword);
            $connection = null;
            header('Location: profile.php?password_changed=1');
            exit;
        }else{
            $connection = null;
            $errors[] = "old-password-error";
            var_dump($user);
        }
    }else{
        $connection = null;
        $errors[] = "password_mismatch";
    }
}
$_SESSION['errors'] = $errors;
if (!empty($errors)) {
    $errorQueryString = '';
    foreach ($errors as $error) {
        $errorQueryString .= 'errors=' . urlencode($error) . '&';
    }

    $errorQueryString = rtrim($errorQueryString, '&');

    header("Location: profile.php?" . $errorQueryString);
    exit;
}else{
    header('Location: profile.php');
    exit;
}


