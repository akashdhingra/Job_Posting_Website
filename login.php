<?php

session_start();

if(!isset($_SESSION['username']))
{
    // header("location: welcome.php");
    exit;
}

require_once "config.php";
$username = $password = "";
$err = "";

if($_SERVER['REQUEST_METHOD'] == "POST")
{
    if(empty(trim($_POST['username'])) || empty(trim($_POST['password'])))
    {
        $err = "Please enter username and password";
        echo $err;
    }
    else{
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
    }

if(empty($err))
{
    $sql = "SELECT id, username, password FROM users WHERE username = ? ";
    $stmt = mysqli_prepare($conn,$sql);
    mysqli_stmt_bind_param($stmt,"s",$param_username);
    $param_username = $username;

    if(mysqli_stmt_execute($stmt))
    {
        mysqli_stmt_store_result($stmt);
        if(mysqli_stmt_num_rows($stmt)==1)
        {
            mysqli_stmt_bind_result($stmt,$id,$username, $hashed_password);
            if(mysqli_stmt_fetch($stmt))
            {
                if(password_verify($password,$hashed_password))
                {
                    //password is correct so allow user to login
                    session_start();
                    $_SESSION["username"] = $username;
                    $_SESSION["id"] = $id;
                    $_SESSION["loggedin"] = true;
                    // if (isset($_POST['jobseeker'])) {
                    //     header('location: Portal_pages/JobSeeker.php'); // redirect to your desired page
                    // }
                    // else{
                    //     header('location: welcome.php');
                    // }
                    

                    
                }
            }
        }
    }
}

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
    <a class="navbar-brand" href="#">Job Portal login</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="#">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="register.php">Register</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Contact us</a>
        </li>
        
      </ul>
    </div>
  </div>
</nav>

<div class="container mt-4">
<h4>Please Login Here:</h4>
<hr>

<form action = "" method="POST">
  <div class="form-group">
    <label for="exampleInputEmail1">Username</label>
    <input type="text" name = 'username' class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter username">
    <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">Password</label>
    <input type="password" name = 'password' class="form-control" id="exampleInputPassword1" placeholder="Enter Password">
  </div>
  <div class="form-check">
    <input type="checkbox" class="form-check-input" id="exampleCheck1">
    <label class="form-check-label" name = "job_seeker" value = "yes" for="exampleCheck1">Job Seeker</label>
  </div>
  <div class="form-check">
    <input type="checkbox" class="form-check-input" id="exampleCheck2">
    <label class="form-check-label" name = "employee" for="exampleCheck2">Employee</label>
  </div>
  <button type="submit" class="btn btn-primary">Submit</button>
  <button type="submit" class="btn btn-primary" formaction="Portal_pages/JobSeeker.php" >JobSeeker</button>
  <button type="submit" class="btn btn-primary" formaction="Portal_pages/Employee.php" >Employee</button>
  <button type="submit" class="btn btn-primary" formaction="Portal_pages/Admin.php" >Admin</button>
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