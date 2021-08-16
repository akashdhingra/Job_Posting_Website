<?php
session_start();
require_once "../config.php";
$userID = $_SESSION["userID"];
// $FirstName 
$balance = 0;

if($_SERVER['REQUEST_METHOD'] == "POST")
{
    if(isset($_POST['radio']))
    {  //  Displaying Selected Value
          if($_POST['radio'] == "Basic")
            $balance = 0;
          elseif($_POST['radio'] == "Prime")
            $balance = 10;
          else
            $balance = 20;
      }
    else
      {
        echo "please select any option";
      }    

  // if no error then insert data into database

      $sql = "UPDATE User SET balance=$balance WHERE userID = '$userID'";
      //$stmt = mysqli_query($conn,$sql);
      if(mysqli_query($conn,$sql))
      {
            echo "information updated";
            header("location: JobSeeker.php");
      }
      else{
          echo "Error: " . $sql . "<br>" . $conn->error;
          echo "Something went wrong. Cannot redirect!";
      }        
      //mysqli_stmt_close($stmt); 
      

 } 
  //mysqli_close($conn); 
?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">

<div class="container mt-4">
<h4>Update details Here:</h4>
<hr>
<form action="" method="POST">
  <div class="form-check">
    <input type="radio" name="radio" value="Basic">Basic [Free]
    <input type="radio" name="radio" value="Prime">Prime [Charges = 10$]
    <input type="radio" name="radio" value="Gold">Gold [Charges = 20$]
    <br>
    <button type="submit" value="Result" name="Result" class="btn btn-primary">Submit</button>
  <div>
  

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js" integrity="sha384-eMNCOe7tC1doHpGoWe/6oMVemdAVTMs2xqW4mwXrXsW0L84Iytr2wi5v2QjrP/xp" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.min.js" integrity="sha384-cn7l7gDp0eyniUwwAZgrzD06kc/tftFf19TOAs2zVinnD/C7E91j9yyk5//jjpt/" crossorigin="anonymous"></script>
    -->
  </body>
</html>