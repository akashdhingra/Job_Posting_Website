<?php
session_start();
require_once "../config.php";
$userID = $_SESSION["userID"];

$sql = "DELETE FROM User WHERE userID= ?";
$stmt = mysqli_prepare($conn,$sql);
mysqli_stmt_bind_param($stmt ,"s",$param_username);

$param_username = $userID;
if(mysqli_stmt_execute($stmt)) {
  echo "Record deleted successfully";
  session_start();
session_destroy();
header('Location: login.php');
exit;
} else {
  echo "Error deleting record: " . $conn->error;
}
?>