<?php
session_start();
require_once('connection.php');
require_once('clasa_Produs.php');

if (!isset($_SESSION['usertype']) || $_SESSION['usertype'] !== 'admin') {
    header("Location: index.php");
    exit();
}

$produs = new Produs($con);
$mesaj = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nume = $_POST['nume'];
    $descriere = $_POST['descriere'];
    $pret = $_POST['pret'];
    $imagine = null;

    if (!empty($_FILES['imagine']['name'])) {
        $target_dir = "uploads/";
        $file_name = time() . "_" . basename($_FILES["imagine"]["name"]);
        $target_file = $target_dir . $file_name;

        if (move_uploaded_file($_FILES["imagine"]["tmp_name"], $target_file)) {
            $imagine = $target_file;
        }
    }

    if ($produs->adaugaProdus($nume, $descriere, $pret, $imagine)) {
        $mesaj = "Produs adăugat cu succes!";
    } else {
        $mesaj = "Eroare la adăugare.";
    }
}
?>


<!DOCTYPE HTML>
<>
<head>
    

    <title>Produse</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <link rel="stylesheet" href="assets/css/main.css" />
    <noscript><link rel="stylesheet" href="assets/css/noscript.css" /></noscript>
</head>
<body class="is-preload">

< id="wrapper" class="fade-in">

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

    <div id="main">

    <div class="container mt-5">

    <h2>Adaugă produs</h2>

    <?php if ($mesaj): ?>
        <div class="alert alert-info"><?= htmlspecialchars($mesaj) ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Nume</label>
            <input type="text" name="nume" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Descriere</label>
            <textarea name="descriere" class="form-control" required></textarea>
        </div>

        <div class="mb-3">
            <label>Preț</label>
            <input type="number" step="0.01" name="pret" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Imagine (opțional)</label>
            <input type="file" name="imagine" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Salvează</button>
        <a href="produse.php" class="btn btn-secondary">Înapoi</a>
    </form>

                

    </div>


     <!-- Scripts -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/jquery.scrollex.min.js"></script>
    <script src="assets/js/jquery.scrolly.min.js"></script>
    <script src="assets/js/browser.min.js"></script>
    <script src="assets/js/breakpoints.min.js"></script>
    <script src="assets/js/util.js"></script>
    <script src="assets/js/main.js"></script>
</div>

</body>
</html>