<?php
$host = 'localhost';
$dbname = 'techassist';
$user = 'root';
$pass = ''; // Byt om du har lösenord

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Databasanslutning misslyckades: " . $e->getMessage());
}
?>
