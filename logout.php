signout
<?php
 unset($_SESSION['logged_in']);
session_start();
session_destroy();
header('Location: login.php');
header("Cache-Control", "no-store, no-cache, must-revalidate");
exit;
?>