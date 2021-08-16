successfully applied

<?php
require_once "../config.php";
session_start();
$userid = $_SESSION['$id'];
echo $userid;
?>
<br>
<a class="nav-link" href="JobSeeker.php">Home</a>
