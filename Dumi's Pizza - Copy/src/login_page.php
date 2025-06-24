<?php
session_start();
require_once('connection.php');

$mesaj = "";

// Procesare formular
if (isset($_POST['Login'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $mesaj = "Completează toate câmpurile.";
    } else {
        try {
            $stmt = $con->prepare("SELECT * FROM conturi WHERE username = :username");
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['username'] = $username;
				$_SESSION['usertype'] = $user['user_type'];


                if (isset($_POST['rememberme'])) {
                    setcookie("username", $username, time() + (86400 * 30), "/"); // 30 zile
                }

                header("Location: index.php");
                exit();
            } else {
                $mesaj = "Utilizator sau parolă incorecte.";
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
                            <li class="active"><a href="login_page.php">Login</a></li>
							<li><a href="produse.php">Produse</a></li>
							<li><a href="contact.php">Contact</a></li>
							<?php if (isset($_SESSION['username'])): ?>
    								<li>
        				 				User: <?= htmlspecialchars($_SESSION['username']) ?>
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
                    		<div class="alert alert-info"><?= $mesaj ?></div>
                		<?php endif; ?>

						<div class="container" style="max-width: 500px; margin: auto; padding: 40px;">
    						<h2>Autentificare</h2>
    							<form method="POST" action="login_page.php">
									
        							<div class="mb-3">
            							<label for="username" class="form-label">Utilizator</label>
           								<input type="text" name="username" class="form-control" required>
        				</div>

        				<div class="mb-3">
            				<label for="password" class="form-label">Parolă</label>
            				<input type="password" name="password" class="form-control" required>
        				</div>
						<br>
        				<div class="form-check mb-3">
            				<input type="checkbox" name="rememberme" class="form-check-input" id="rememberme">
            			<label class="form-check-label" for="rememberme">Ține-mă minte</label>
        			</div>

        			<button type="submit" name="Login" class="btn btn-primary">Login</button>
    				</form>
					<p ><b>Nu ai cont? Apasa <a href="register.php">aici</a></b></p>
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
