<?php
require_once '../vendor/autoload.php';
if (file_exists('C:/wamp64/www/collegefbarchive/.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable('C:/wamp64/www/collegefbarchive');
    $dotenv->load();
}
function dbConnect()
{
    if (file_exists('C:/wamp64/www/collegefbarchive/.env')) {
        $dbHost = $_ENV['JAWS_HOST'];
        $dbPort = $_ENV['JAWS_PORT'];
        $dbName = $_ENV['JAWS_DBNAM'];
        $dbUser = $_ENV['JAWS_USERNAME'];
        $dbPassword = $_ENV['JAWS_PASSWORD'];
    }else {
        $dbHost = getenv('JAWS_HOST');
        $dbPort = getenv('JAWS_PORT');
        $dbName = getenv('JAWS_DBNAM');
        $dbUser = getenv('JAWS_USERNAME');
        $dbPassword = getenv('JAWS_PASSWORD');
    }

    $dsn = "mysql:host=$dbHost;port=$dbPort;dbname=$dbName";
    try {
        $conn = new PDO($dsn, $dbUser, $dbPassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch (PDOException $e) {
        $message= "Connection failed: " . $e->getMessage()."\n";
        echo $message;
        return null;
    }
}
?>