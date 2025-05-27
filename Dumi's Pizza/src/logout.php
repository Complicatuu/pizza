<?php
session_start();
$_SESSION = [];
session_destroy();

if (isset($_COOKIE['username'])) {
    setcookie('username', '', time() - 3600, '/');
    unset($_COOKIE['username']);
}
if (isset($_COOKIE['password'])) {
    setcookie('password', '', time() - 3600, '/');
    unset($_COOKIE['password']);
}

header("Location: login_form.php?logout=1");
exit;
?>