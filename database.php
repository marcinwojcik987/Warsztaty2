<?php

$host = "localhost";
$user = "root";
$pass = "coderslab";
$db = "tweeter";
 
try {
    $conn = new PDO(
        "mysql:host=$host;dbname=$db;charset=UTF8",
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]
    );
} catch (PDOException $e) {
    echo $e->getMessage();
}
 
//echo("Polaczenie udane.");
//$conn = null;
