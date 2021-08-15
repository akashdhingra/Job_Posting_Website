<?php
$DATABASE_HOST = '127.0.0.1';
$DATABASE_USER = 'ajc55311';
$DATABASE_PASS = '5531ajkr';
$DATABASE_NAME = 'ajc55311';
$DATABASE_PORT = '3306';
// Try and connect using the info above.
$conn = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME, $DATABASE_PORT);

if($conn == false)
{
    dir("Error : cannot connect");
}

?>
