<?php
require_once('connection.php');
session_start();

if (isset($_POST['Login'])) {
    if (empty($_POST['username']) || empty($_POST['password'])) {
        header("Location: login_page.php"); // Dacă nu sunt completate câmpurile
        exit();
    }

    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        // Verificăm utilizatorul în baza de date
        $query = "SELECT * FROM conturi WHERE username = :username";
        $stmt = $con->prepare($query);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            if ($password === $user['password']) { // Verificare parolă exact cum o ai în DB
                $_SESSION['username'] = $username;
                $_SESSION['usertype'] = $user['user_type'];

                // Setăm cookie dacă a bifat "Remember Me"
                if (isset($_POST['rememberme'])) {
                    setcookie('username', $username, time() + (86400 * 30), "/");
                }

                header("Location: index.php"); // Redirect după login reușit
                exit();
            } else {
                echo "Parola este greșită!";
            }
        } else {
            echo "Utilizatorul nu există!";
        }
    } catch (PDOException $e) {
        echo " Eroare de conexiune: " . $e->getMessage();
    }
} else {
    echo "Introduceți datele de autentificare!";
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
                            <li class="active"><a href="login_page.php">Login</a></li>
							<li><a href="produse.php">Produse</a></li>
							<li><a href="contact.php">Contact</a></li>
						</ul>
						<ul class="icons">
							<li><a href="#" class="icon brands fa-twitter"><span class="label">Twitter</span></a></li>
							<li><a href="#" class="icon brands fa-facebook-f"><span class="label">Facebook</span></a></li>
							<li><a href="#" class="icon brands fa-instagram"><span class="label">Instagram</span></a></li>
							<li><a href="#" class="icon brands fa-github"><span class="label">GitHub</span></a></li>
						</ul>
					</nav>

				<!-- Main -->
					<div id="main">

						

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