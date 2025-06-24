<?php
session_start();
require_once('connection.php');

// Verificare permisiune
if (!isset($_SESSION['usertype']) || $_SESSION['usertype'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// Verificare dacă a venit un id valid
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: produse.php");
    exit();
}

$id = (int)$_GET['id'];

// Șterge produsul
try {
    $stmt = $con->prepare("DELETE FROM produse WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    header("Location: produse.php?deleted=1");
    exit();
} catch (PDOException $e) {
    echo "Eroare la ștergere: " . $e->getMessage();
}
?>
