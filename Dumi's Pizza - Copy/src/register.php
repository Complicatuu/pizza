<?php
require_once('connection.php');
session_start();

$mesaj = "";

if (isset($_POST['Register'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];

    // Validare simplă
    if (empty($username) || empty($password) || empty($confirm)) {
        $mesaj = "Toate câmpurile sunt obligatorii.";
    } elseif ($password !== $confirm) {
        $mesaj = "Parolele nu coincid.";
    } else {
        try {
            // Verifică dacă userul există deja
            $check = $con->prepare("SELECT id FROM conturi WHERE username = :username");
            $check->bindParam(':username', $username);
            $check->execute();

            if ($check->rowCount() > 0) {
                $mesaj = "Utilizatorul există deja!";
            } else {
                // Criptează parola
                $hashed_pass = password_hash($password, PASSWORD_DEFAULT);
                
                $user_type="user";
                // Inserare în baza de date
                $insert = $con->prepare("INSERT INTO conturi (username, password, user_type) VALUES (:username, :password, :user_type)");
                $insert->bindParam(':username', $username);
                $insert->bindParam(':password', $hashed_pass);
                $insert->bindParam(':user_type',  $user_type);
                

                if ($insert->execute()) {
                    $mesaj = "Cont creat cu succes! <a href='login_page.php'>Autentifică-te</a>";
                } else {
                    $mesaj = "Eroare la creare cont.";
                }
            }
        } catch (PDOException $e) {
            $mesaj = "Eroare: " . $e->getMessage();
        }
    }
}
?>


<!DOCTYPE html>
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

    
        <!-- Register Form -->
        <div class="container mt-5" id="main">
            <h2>Creare cont</h2>

                <?php if ($mesaj): ?>
                    <div class="alert alert-info"><?= $mesaj ?></div>
                <?php endif; ?>

            <form method="POST" action="register.php" name="form1" onsubmit="return checkCaptcha();" class="form-register">
                <div class="mb-3">
                    <label for="username" class="form-label">Utilizator</label>
                    <input type="text" name="username" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Parolă</labe>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="confirm" class="form-label">Confirmare parolă</label>
                    <input type="password" name="confirm" class="form-control" required>
                </div>
                
                <div class="mb-3">
                    <label for="capcha" class="form-label">Verificare Capcha</labe>
                    <input class="form-control mb-3" type="text" id="cta" name="ct" value="####" readonly>
                    <input class="form-control mb-3" type="text" id="ci" placeholder="Captcha" required>
                </div>

                <button type="submit" name="Register" class="btn btn-success">Creează cont</button>
                <input class="btn btn-custom mt-3" name="refresh" type="button" value="Refresh" id="refreshbtn" onclick="getNewCaptcha();">
                

            </form>
        </div>
        <!-- Footer -->
        <footer class="py-5 bg-dark">
            <div class="container"><p class="m-0 text-center text-white">Copyright &copy; Your Website 2023</p></div>
        </footer>
        <!-- Bootstrap core JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Core theme JS -->
        <script src="js/scripts.js"></script>
        
        <!-- Captcha Script -->
        <script>
            var captcha, chars;

            function getNewCaptcha(){
                chars="1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
                captcha = chars[Math.floor(Math.random()*chars.length)];
                for(var i=0; i<5; i++){
                    captcha = captcha + chars[Math.floor(Math.random()*chars.length)];
                }
                form1.ct.value=captcha;
            }

            function checkCaptcha(){
                var check=document.getElementById("ci").value;
                if(captcha==check){
                    return true;
                }
                else{
                    alert("Invalid captcha");
                    return false;
                }
            }

            getNewCaptcha();
        </script>
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
