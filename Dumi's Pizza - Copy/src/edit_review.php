<?php
session_start();
require_once('connection.php');
require_once('clasa_Review.php');

if (!isset($_SESSION['username'])) {
    header("Location: login_page.php");
    exit();
}

$review = new Review($con);
$id = $_GET['id'] ?? null;
$mesaj = "";

if (!$id) die("ID review lipsă.");

$data = $review->getReview($id);
if (!$data || $data['username'] !== $_SESSION['username']) {
    die("Nu ai permisiunea să editezi acest review.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mesaj = $_POST['mesaj'];
    $rating = $_POST['rating'];
    $imagine = null;

    if (!empty($_FILES['imagine']['name'])) {
        $filename = time() . "_" . basename($_FILES["imagine"]["name"]);
        $target = "uploads/" . $filename;
        if (move_uploaded_file($_FILES["imagine"]["tmp_name"], $target)) {
            $imagine = $target;
        }
    }

    if ($review->editeazaReview($id, $_SESSION['username'], $mesaj, $rating, $imagine)) {
        $mesaj = "Review actualizat.";
        $data = $review->getReview($id);
    } else {
        $mesaj = "Eroare la actualizare.";
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
			<id="wrapper" class="fade-in">

				<!-- Header -->
					<header id="header">
						<a href="index.php" class="logo">Dumi's Pizza</a>
					</header>

				<!-- Nav -->
					<nav id="nav">
						<ul class="links">
							<li class="active"><a href="index.php">Despre noi</a></li>
                            <li><a href="login_page.php">Login</a></li>
							<li><a href="produse.php">Produse</a></li>
							<li><a href="contact.php">Contact</a></li>
								<?php if (isset($_SESSION['username'])): ?>
    								<li> <a href="zona_securizata.php">
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
                <div id="main" >

                <h2>Editare Review</h2>

                <?php if ($mesaj): ?>
                    <div class="alert alert-info"><?= htmlspecialchars($mesaj) ?></div>
                <?php endif; ?>

                <form method="POST" >
                    <div class="mb-3">
                        <label>Mesaj</label>
                        <textarea name="mesaj" class="form-control" required><?= htmlspecialchars($data['mesaj']) ?></textarea>
                    </div>
                    <div class="mb-3">
                    <label>Rating</label>
                        <select name="rating" class="form-select" required>
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <option value="<?= $i ?>" <?= $data['rating'] == $i ? 'selected' : '' ?>><?= $i ?> stele</option>
                        <?php endfor; ?>
                    </select>
                    </div>
                    <?php if ($data['imagine']): ?>
                    <p>Imagine curentă:</p>
                        <img src="<?= $data['imagine'] ?>" style="max-width: 200px;" class="mb-3">
                    <?php endif; ?>
                    <div class="mb-3">
                        <label>Înlocuiește imaginea (opțional)</label>
                        <input type="file" name="imagine" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary">Salvează modificările</button>
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
