<?php
$host = 'db'; 
$dbname = 'pizza';
$username = 'root'; 
$password = 'toor'; 
try {
    $con = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Conexiunea a eșuat: " . $e->getMessage());
}