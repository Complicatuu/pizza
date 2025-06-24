<?php
require_once('connection.php');
session_start();
if (isset($_COOKIE['username'])){
	$_SESSION['username']=$_COOKIE['username'];
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

				<!-- Main -->

				
				<!-- Melodie fundal -->
				 <audio autoplay loop hidden>
  					<source src="music/muzica.mp3" type="audio/mpeg">
				</audio>
					<div id="main">

						<!-- Featured Post -->
							<article class="post featured">
									<h2>Cea mai bună pizza<br>
									din toată România</a></h2>
								<a  class="image main"><img src="images/imagine resetaurant 2.avif"/></a>
								<ul class="actions special">
								</ul>
							</article>

							<article class="post featured">
								<h3>Serviciu de livrare acasa</h3>
								<video width="640" height="360" controls autoplay muted loop>
  									<source src="videos/livrare.mp4" type="video/mp4">
								</video>
							</article>

							<article class="post featured">
								<h3><a href="produse.php">Comanda acum</a></h3>
								<iframe width="560" height="315" src="https://www.youtube.com/embed/sv3TXMSv6Lw?si=3ukIru3PvzaBmPSS" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
							</article>

							<article class="post featured">
								<h3><a href="review.php">Lasa-ne un review!</a></h3>
							</article>
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