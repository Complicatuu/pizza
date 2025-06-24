<?php
session_start();
require_once('connection.php');

if (!isset($_SESSION['username'])) {
    header("Location: login_page.php");
    exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: review.php");
    exit();
}

$id = $_GET['id'];
$username = $_SESSION['username'];

// Șterge doar dacă review-ul îi aparține userului
$stmt = $con->prepare("DELETE FROM reviewuri WHERE id = :id AND username = :username");
$stmt->execute([':id' => $id, ':username' => $username]);

header("Location: review.php?deleted=1");
exit();
