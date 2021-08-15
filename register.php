<?php

require_once "config.php";
// $FirstName 
$username = $password = $confirm_password = $FirstName = $LastName = $EmailID = $phonenumber = $statdate = $accountstatus = "";
$balance = 0;
$username_err = $password_err = $confirm_password_err = "";

if($_SERVER['REQUEST_METHOD'] == "POST")
{
    // check if username is empty
    if(empty(trim($_POST['username'])))
    {
        $username_err = "Username cannot be blank";
        echo $username_err;
    }
    else{
        $sql = "SELECT userID FROM User WHERE userID = ?";
        $stmt = mysqli_prepare($conn,$sql); // preparing the sql command
        if($stmt)
        {
            mysqli_stmt_bind_param($stmt ,"s",$param_username); // binding the sql command with username

            $param_username = trim($_POST['username']); // assigning the entered username to param variable

            if(mysqli_stmt_execute($stmt))  // if statement executes successfully
            {
                mysqli_stmt_store_result($stmt); // store result in stmt variable
                if(mysqli_stmt_num_rows($stmt)==1)  // check if name is already in the row
                {
                    $username_err = "This username already exists";  // if exist then show error
                }
                else{
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
        
                    $username = trim($_POST['username']);  // if name does not exist already then store it
                    $FirstName = $_POST['FirstName'];
                    $LastName = $_POST['LastName']; 
                    $EmailID = $_POST['EmailID']; 
                    $phonenumber = $_POST['phonenumber'];
                    $statdate = date('Y-m-d');
                    $accountstatus = "Activated";
                    
                  }
            }

            else{
                echo "Error: " . $sql . "<br>" . $conn->error;
                echo "Something went wrong";  // if statement does not executes then show error
                }
        
        }
        mysqli_stmt_close($stmt);
        }

    

// check for password

  if(empty(trim($_POST['password'])))
  {
      $password_err = "Password cannot be blank";
  }

  elseif(strlen(trim($_POST['password']))<5)
  {
      $password_err = "Password cannot be less than 5 characters";
  }

  else{
      $password = trim($_POST['password']);
  }

  // check for confirmed password field

  if(trim($_POST['password'])!= trim($_POST['confirm_password']))
  {
      $password_err = "passwords should match";
  }

  // if no error then insert data into database

  if(empty($username_err) && empty($password_err) && empty($confirm_password_err))
    {
      $sql = "INSERT INTO User (userID, password, firstName, lastName, accountStatus, balance, statChangeDate, email, phoneNumber) VALUES (?,?,?,?,?,?,?,?,?)";
      $stmt = mysqli_prepare($conn,$sql);
      if($stmt)
      {
          mysqli_stmt_bind_param($stmt,"sssssisss",$param_username,$param_password, $param_firstname, $param_lastname, $param_accountstatus, $param_balance, $param_statdate, $param_email, $param_phonenumber);

          $param_username = $username;
          $param_password = $password;
          //$param_password = password_hash($password,PASSWORD_DEFAULT);
          $param_firstname = $FirstName;
          $param_lastname = $LastName;
          $param_accountstatus= $accountstatus;
          $param_balance = $balance; 
          $param_statdate = $statdate;
          $param_email = $EmailID;
          $param_phonenumber = $phonenumber;

          if(mysqli_stmt_execute($stmt))
          {
            echo "information entered";
            header("location: welcome.php");
          }
          else{
              echo "Error: " . $sql . "<br>" . $conn->error;
              echo "Something went wrong. Cannot redirect!";
          }        
      }
      mysqli_stmt_close($stmt); 

    } 
    mysqli_close($conn);

}
?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">

    <title>Job Portal</title>
  </head>
  <body>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Job Portal [Register]</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="#">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">About</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Contact us</a>
        </li>
        
      </ul>
    </div>
  </div>
</nav>

<div class="container mt-4">
<h4>Please Register Here:</h4>
<hr>
<form action="" method="POST">
  <div class="col-md-6">
    <label for="inputEmail4" class="form-label">UserID</label>
    <input type="text" class="form-control" name="username" placeholder="user name">
  </div>
  <div class="col-md-6">
    <label for="inputPassword4" class="form-label">Password</label>
    <input type="password" class="form-control" name="password" placeholder="password">
  </div>
  <div class="col-md-6">
    <label for="inputPassword4" class="form-label">Confirm Password</label>
    <input type="password" class="form-control" name="confirm_password" placeholder="confirm password">
  </div>
  <div class="col-md-6">
    <label for="inputFirstName" class="form-label">First Name   </label>
    <input type="text" class="form-control" name="FirstName">
  </div>
  <div class="col-md-6">
    <label for="inputLastName" class="form-label">Last Name</label>
    <input type="text" class="form-control" name="LastName">
  </div>
  <div class="col-md-6">
    <label for="inputEmailID" class="form-label">Email ID</label>
    <input type="text" class="form-control" name="EmailID">
  </div>
  <div class="col-md-6">
    <label for="inputPhoneNumber" class="form-label">Phone Number</label>
    <input type="text" class="form-control" name="phonenumber">
  </div>
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