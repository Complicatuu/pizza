<?php
require_once('connection.php');
require_once('clasa_Produs.php');
session_start();

$produs = new Produs($con);
$mesaj = "";

// Afișare produse
try {
    $stmt = $con->prepare("SELECT * FROM produse");
    $stmt->execute();
    $produse = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Eroare la interogare: " . $e->getMessage());
}
?>

<!DOCTYPE HTML>
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

    <!-- Main -->
    <div id="main" class="container mt-5">
        <h2>Produsele Noastre</h2> <h3><a href="cautare_produse.php">Cautare produse</a></h3>

        <?php if (isset($_GET['deleted'])): ?>
            <div class="alert alert-success">Produsul a fost șters cu succes.</div>
        <?php endif; ?>

		

		<?php if (isset($_SESSION['usertype']) && $_SESSION['usertype'] === 'admin'): ?>
    		<div class="mb-4 text-end">
        		<a href="adauga_produs.php" class="btn btn-success">Adaugă produs</a>
    		</div>
		<?php endif; ?>


        <div class="row">
            <?php foreach ($produse as $produs): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <?php if ($produs['imagine']): ?>
                            <img src="<?= htmlspecialchars($produs['imagine']) ?>" class="card-img-top img-produs" alt="<?= htmlspecialchars($produs['nume']) ?>">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($produs['nume']) ?></h5>
                            <p class="card-text"><?= htmlspecialchars($produs['descriere']) ?></p>
                            <p class="card-text fw-bold"><?= number_format($produs['pret'], 2) ?> RON</p>

                            <?php if (isset($_SESSION['usertype']) && $_SESSION['usertype'] === 'admin'): ?>
                                <a href="editare_produs.php?id=<?= $produs['id'] ?>" class="btn btn-outline-primary mt-2">Editează</a>
                                <a href="sterge_produs.php?id=<?= $produs['id'] ?>" class="btn btn-outline-danger mt-2"
                                   onclick="return confirm('Ești sigur că vrei să ștergi acest produs?');">Șterge</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
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
