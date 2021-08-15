<?php
require_once "config.php";

$sql = "INSERT INTO User (userID, password, firstName, lastName, accountStatus, balance, statChangeDate, email, phoneNumber) VALUES ('akash123','testing','akash','dhingra','Active',10,'2018-09-21','akash40162788@gmail.com','789456789456')";

if ($conn->query($sql) === TRUE) {
  echo "New record created successfully";
} else {
  echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
