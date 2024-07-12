<?php
session_start();
unset($_SESSION['user_id']);
unset($_SESSION['user_email']);

$currentFile = $_SERVER["PHP_SELF"];
$currentPage = basename($currentFile);

$redirectURL = "../index.php";

if ($currentPage === "index.php") {
    $redirectURL = "index.php";
}

header("Location: $redirectURL");
