<?php

require_once "config.php";

$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";

if($_SERVER['REQUEST_METHOD'] == "POST")
{
    // check if username is empty
    if(empty(trim($_POST["username"])))
    {
        $username_err = "Username cannot be blank";
    }
    else{
        $sql = "SELECT id FROM users WHERE username = ?";
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
                    $username = trim($_POST['username']);  // if name does not exist already then store it
                }

            }
            else{
                echo "Something went wrong";  // if statement does not executes then show error
            }
        }
    }

    mysqli_stmt_close($stmt);

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
    $sql = "INSERT INTO users (username, password) VALUES (?,?)";
    $stmt = mysqli_prepare($conn,$sql);
    if($stmt)
    {
        mysqli_stmt_bind_param($stmt,"ss",$param_username,$param_password);

        $param_username = $username;
        $param_password = password_hash($password,PASSWORD_DEFAULT);

        if(mysqli_stmt_execute($stmt))
        {
            header("location: login.php");
        }
        else{
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
    <label for="inputEmail4" class="form-label">Username</label>
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
    <label for="inputAddress2" class="form-label">Address   </label>
    <input type="text" class="form-control" id="inputAddress2" placeholder="Apartment, studio, or floor">
  </div>
  <div class="col-md-6">
    <label for="inputCity" class="form-label">City</label>
    <input type="text" class="form-control" id="inputCity">
  </div>
  <div class="col-md-4">
    <label for="inputState" class="form-label">State</label>
    <select id="inputState" class="form-select">
      <option selected>Choose...</option>
      <option>...</option>
    </select>
  </div>
  <div class="col-md-2">
    <label for="inputZip" class="form-label">Zip</label>
    <input type="text" class="form-control" id="inputZip">
  </div>
  <div class="col-12">
    <div class="form-check">
      <input class="form-check-input" type="checkbox" id="gridCheck">
      <label class="form-check-label" for="gridCheck">
        Check me out
      </label>
    </div>
  </div>
  <div class="col-12">
    <button type="submit" class="btn btn-primary">Sign in</button>
  </div>
</form>
</div>

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