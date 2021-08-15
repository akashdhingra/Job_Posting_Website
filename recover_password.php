<!doctype html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
   
      <title>RESET PASSWORD</title>
       <!-- CSS -->
       <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
   </head>
   <body>
      <div class="container">
          <div class="card">
            <?php

            if(isset($_SESSION['status']))
            {
              ?>
              <div class="alert alert-success">
                <h5><? = $_SESSION['status']; ?></h5>
              </div>
              <?php
              unset($_SESSION['status'])''
            }

            ?>
            <div class="card-header text-center">
                RESET PASSWORD
            </div>
            <div class="card-body">
              <form action="password-reset-code.php" method="post">
                <div class="form-group">
                  <label for="exampleInputEmail1">Email address</label>
                  <input type="email" name="email" class="form-control" id="email" aria-describedby="emailHelp">
                  <small id="emailHelp" class="form-text text-muted"></small>
                </div>
                <button type="submit" name="password-reset-link" class="btn btn-primary">Reset Password </button>
              </form>
            </div>
          </div>
      </div>
 
   </body>
</html>