<?php
include ('config.php');

if(isset($_POST['password-reset-link']))
{
	$email = mysqli_real_escape_string($conn,$_POST['email']);
	$token = md5(rand());

	$check_email = "SELECT email FROM User WHERE email = '$email' LIMIT 1";
	$check_email_run = mysqli_query($conn, $check_email);

	if(mysqli_num_rows($check_email_run)>0)
	{
		$row = mysqli_fetch_array($check_email_run);
		$get_name = $row['firstName'];
		$get_email = $row['email'];

		$update_token

	}
	else
	{
		$_SESSION['status'] = "No Email found";
		header("Location: recover_password.php");
		exit(0);
	}
}


?>