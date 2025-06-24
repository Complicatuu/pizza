<?php
session_start();
require_once('connection.php');
require_once('clasa_Review.php');


try {
    $stmt = $con->prepare("SELECT * FROM reviewuri ORDER BY data DESC");
    $stmt->execute();
    $reviewuri = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<div class='alert alert-danger'>Eroare DB: " . $e->getMessage() . "</div>";
    $reviewuri = [];
}


$review = new Review($con);
$mesaj = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_SESSION['username'];
    $comentariu = trim($_POST['mesaj']);
    $rating = (int)$_POST['rating'];
    $imagine = null;

    if (!empty($_FILES['imagine']['name'])) {
        $folder = "uploads/";
        $filename = time() . "_" . basename($_FILES["imagine"]["name"]);
        $target = $folder . $filename;

        if (move_uploaded_file($_FILES["imagine"]["tmp_name"], $target)) {
            $imagine = $target;
        }
    }

    if ($review->adaugaReview($username, $comentariu, $rating, $imagine)) {
        $mesaj = "Review adăugat cu succes!";
    } else {
        $mesaj = "Eroare la trimitere.";
    }
}
?>


<!DOCTYPE html>
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

				<!-- Intro -->
					<div id="intro">
						<h1>Dumi's Pizza</h1>
						<p>O pizza pentru toți</p>
						<ul class="actions">
							<li><a href="#header" class="button icon solid solo fa-arrow-down scrolly">Continue</a></li>
						</ul>
					</div>

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

                    <div id="main">
    <h2>Lasă un review</h2>

    <?php if ($mesaj): ?>
        <div class="alert alert-info"><?= htmlspecialchars($mesaj) ?></div>
    <?php endif; ?>

    <?php if (isset($_SESSION['username'])): ?>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="comentariu">Comentariu</label>
            <textarea name="comentariu" class="form-control" required></textarea>
        </div>

        <div class="mb-3">
            <label for="rating">Rating</label>
            <select name="rating" class="form-select" required>
                <option value="">Alege...</option>
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <option value="<?= $i ?>"><?= $i ?> stele</option>
                <?php endfor; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="imagine">Încarcă o imagine (opțional)</label>
            <input type="file" name="imagine" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Trimite review</button>
        <?php endif; ?>

        <!--reviewuri recente-->
        <h3 class="mt-5">Reviewuri recente</h3>

            <?php foreach ($reviewuri as $rev): ?>
                <div class="border p-3 mb-3">
                        <strong><?= htmlspecialchars($rev['username']) ?></strong> (<?= $rev['rating'] ?> ⭐)<br>
                        <p><?= htmlspecialchars($rev['mesaj']) ?></p>
                <?php if ($rev['imagine']): ?>
                        <img src="<?= htmlspecialchars($rev['imagine']) ?>" style="max-width: 300px;">
                <?php endif; ?>
                    <small><?= $rev['data'] ?></small>
                </div>
                <?php if (isset($_SESSION['username']) && $_SESSION['username'] === $rev['username']): ?>
                    <a href="sterge_review.php?id=<?= $rev['id'] ?>" class="btn btn-sm btn-danger mt-2" onclick="return confirm('Ești sigur că vrei să ștergi acest review?');"> Șterge </a>
                    <a href="edit_review.php?id=<?= $rev['id'] ?>" class="btn btn-sm btn-primary mt-2">Editează</a>
                <?php endif; ?>
            <?php endforeach; ?>


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
