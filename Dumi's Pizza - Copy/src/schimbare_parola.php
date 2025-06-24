<?php
session_start();
require_once('connection.php');

if (!isset($_SESSION['username'])) {
    header("Location: login_page.php");
    exit();
}

$mesaj = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_SESSION['username'];
    $veche = $_POST['veche'];
    $noua = $_POST['noua'];

    if (empty($veche) || empty($noua)) {
        $mesaj = "Completează toate câmpurile.";
    } else {
        try {
            // Ia parola curentă din DB
            $stmt = $con->prepare("SELECT password FROM conturi WHERE username = :username");
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row && password_verify($veche, $row['password'])) {
                // Hash pentru parola nouă
                $hashed = password_hash($noua, PASSWORD_DEFAULT);

                // Actualizează parola
                $update = $con->prepare("UPDATE conturi SET password = :parola WHERE username = :username");
                $update->bindParam(':parola', $hashed);
                $update->bindParam(':username', $username);

                if ($update->execute()) {
                    $mesaj = "Parola a fost schimbată cu succes.";
                } else {
                    $mesaj = "Eroare la actualizarea parolei.";
                }
            } else {
                $mesaj = "Parola veche este incorectă.";
            }
        } catch (PDOException $e) {
            $mesaj = "Eroare: " . $e->getMessage();
        }
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
						
						<?php if ($mesaj): ?>
                    		<div class="alert alert-info"><?= htmlspecialchars($mesaj) ?></div>
                		<?php endif; ?>



						<div class="container mt-5" style="max-width: 500px; margin: auto; padding: 40px;">
						<form method="POST" action="schimbare_parola.php" name="form1" class="form-register">
							<b>Schimbare parolă:</b><br>
									<div class="mb-3">
            							<label for="veche" class="form-label">Parola actuala</label>
            							<input type="password" name="veche" class="form-control" required>
        							</div>
									<div class="mb-3">
            							<label for="noua" class="form-label">Parolă</label>
            							<input type="password" name="noua" class="form-control" required>
        							</div> 
							<button type="submit" class="btn btn-warning">Schimbă parola</button>
						</form>
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

	</body>
</html>