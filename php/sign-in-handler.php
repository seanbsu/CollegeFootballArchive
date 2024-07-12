<?php
include_once 'db.php';
include_once 'db-queries.php';
include_once 'User-authentication-functions.php';
session_start();
if (isset($_POST['Login-Form-Login-Button'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $connection = dbConnect();

    $user = validateUser($connection, $username, $password);

    if ($user) {
         $_SESSION['user_id'] = $user['user_id'];
         $_SESSION['user_email'] = $username;
        header('Location: profile-handler.php');
        exit();
    }else{
        header('Location: Sign-in.php?error=invalid-credentials');
        exit();}
}



