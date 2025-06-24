<?php
require_once('connection.php');
session_start();

// Asigură-te că userul este logat
if (!isset($_SESSION['username'])) {
    header("Location: login_page.php");
    exit();
}

$mesaj = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirm'])) {
    $username = $_SESSION['username'];

    try {
        $stmt = $con->prepare("DELETE FROM conturi WHERE username = :username");
        $stmt->bindParam(':username', $username);
        if ($stmt->execute()) {
            session_destroy();
            setcookie("rememberme", "", time() - 3600, "/");
            header("Location: index.php");
            exit();
        } else {
            $mesaj = "Eroare la ștergerea contului.";
        }
    } catch (PDOException $e) {
        $mesaj = "Eroare: " . $e->getMessage();
    }
}
?>



<!DOCTYPE HTML>

<html>
	<head>
		<title>Dumitru's Pizza</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="assets/css/main.css" />
		<noscript><link rel="stylesheet" href="assets/css/noscript.css" /></noscript>
	</head>
	<body class="is-preload">
			

		<!-- Wrapper -->
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
							<li><a href="produse.php">Produse</a></li>
							<li><a href="contact.php">Contact</a></li>
								<?php if (isset($_SESSION['username'])): ?>
    								<li class="active"> <a href="zona_securizata.php">
        				 				User: <?= htmlspecialchars($_SESSION['username']) ?> </a>
									</li>
									<li>
										<a href="logout.php">Logout</a>
									</li>
								<?php endif; ?>
						</ul>
						<ul class="icons">
							<li><a href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fparse.com" target="_blank" rel="noopener" class="icon brands fa-facebook-f"><span class="label">Facebook</span></a></li>
							<li><a href="#" class="icon brands fa-github"><span class="label">GitHub</span></a></li>
						</ul>
					</nav>

				<!-- Main -->
					<div id="main">
						<h3>Schimbare parola:</h3> 
						<form action="schimbare_parola.php" method="get">
    						<button type="submit" class="btn btn-warning">Apasa aici</button>
						</form>


    				<h3>Ștergere cont</h3>

    				<?php if ($mesaj): ?>
        				<div class="alert alert-danger"><?= htmlspecialchars($mesaj) ?></div>
    				<?php endif; ?>

    				<div class="alert alert-warning">
        				<strong>Atenție!</strong> Această acțiune este permanentă. Contul tău va fi șters definitiv.
    				</div>

    					<form method="POST">
        					<button type="submit" name="confirm" class="btn btn-danger">Șterge contul</button>
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

	</body>
</html>