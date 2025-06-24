<?php
session_start();
require_once('connection.php');
require_once('clasa_Produs.php');

if (!isset($_SESSION['username']) || $_SESSION['usertype'] !== 'admin') {
    header("Location: login_page.php");
    exit();
}

$produs = new Produs($con);
$mesaj = "";

$id = $_GET['id'] ?? null;
if (!$id) {
    die("ID produs lipsă.");
}

// Obține datele produsului
$stmt = $con->prepare("SELECT * FROM produse WHERE id = :id");
$stmt->bindParam(':id', $id);
$stmt->execute();
$dateProdus = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$dateProdus) {
    die("Produsul nu există.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nume = $_POST['nume'];
    $descriere = $_POST['descriere'];
    $pret = $_POST['pret'];
    $imagine = null;

    if (!empty($_FILES['imagine']['name'])) {
        $target_dir = "images/";
        $file_name = time() . "_" . basename($_FILES["imagine"]["name"]);
        $target_file = $target_dir . $file_name;

        if (move_uploaded_file($_FILES["imagine"]["tmp_name"], $target_file)) {
            $imagine = $target_file;
        }
    }

    if ($produs->editeazaProdus($id, $nume, $descriere, $pret, $imagine)) {
        $mesaj = "Produs actualizat cu succes.";
        // Refresh date produs
        $stmt->execute();
        $dateProdus = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        $mesaj = "Eroare la actualizare.";
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <style>
        .img-produs {
            height: 200px;
            object-fit: cover;
            border-bottom: 1px solid #ddd;
        }
    </style>

    <title>Produse</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <link rel="stylesheet" href="assets/css/main.css" />
    <noscript><link rel="stylesheet" href="assets/css/noscript.css" /></noscript>
</head>
<body class="is-preload">

<div id="wrapper" class="fade-in">

    <!-- Header -->
    <header id="header">
        <a href="index.php" class="logo">Dumi's Pizza</a>
    </header>

    <!-- Nav -->
    <nav id="nav">
        <ul class="links">
            <li><a href="index.php">Despre noi</a></li>
            <li><a href="login_page.php">Login</a></li>
            <li class="active"><a href="produse.php">Produse</a></li>
            <li><a href="contact.php">Contact</a></li>
            <?php if (isset($_SESSION['username'])): ?>
                <li><a href="zona_securizata.php">User: <?= htmlspecialchars($_SESSION['username']) ?></a></li>
                <li><a href="logout.php">Logout</a></li>
            <?php endif; ?>
        </ul>
        <ul class="icons">
            <li><a href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fparse.com" target="_blank" rel="noopener" class="icon brands fa-facebook-f"><span class="label">Facebook</span></a></li>
            <li><a href="#" class="icon brands fa-github"><span class="label">GitHub</span></a></li>
        </ul>
    </nav>
    <div id="main" class="container mt-5" >

    <h2>Editare produs</h2>

        <?php if ($mesaj): ?>
            <div class="alert alert-info"><?= htmlspecialchars($mesaj) ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label>Nume</label>
                <input type="text" name="nume" class="form-control" value="<?= htmlspecialchars($dateProdus['nume']) ?>" required>
            </div>

            <div class="mb-3">
                <label>Descriere</label>
                <textarea name="descriere" class="form-control" required><?= htmlspecialchars($dateProdus['descriere']) ?></textarea>
            </div>

            <div class="mb-3">
                <label>Preț</label>
                <input type="number" step="0.01" name="pret" class="form-control" value="<?= $dateProdus['pret'] ?>" required>
            </div>

            <?php if ($dateProdus['imagine']): ?>
                <p>Imagine curentă:</p>
                <img src="<?= htmlspecialchars($dateProdus['imagine']) ?>" style="max-width: 200px;" class="mb-3">
            <?php endif; ?>

            <div class="mb-3">
                <label>Înlocuiește imaginea (opțional)</label>
                <input type="file" name="imagine" class="form-control">
            </div>

            <button type="submit" class="btn btn-primary">Salvează modificările</button>
        </form>
        </div>
        </body>
</html>
