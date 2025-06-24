<?php
$host = 'mysql_db'; 
$db = 'proiect1';
$user = 'root';
$pass = 'toor';

try {
    $con = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Conexiunea a eșuat: " . $e->getMessage());
}
?>
