<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>zz</title>
    <link href="bootstrap/css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="css/dashboard.css" rel="stylesheet">
    <script src="js/dashboard.js"></script>
</head>
<body>
<div class="container">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Job Seeker Dashboard</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="index.php">Home<span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $_SERVER['PHP_SELF'] . "?tab=viewJobs"; ?>">View All Jobs</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $_SERVER['PHP_SELF'] . "?tab=viewApplications"; ?>">View Job Applications</a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $_SERVER['PHP_SELF'] . "?tab=viewAccountSettings"; ?>">Account Settings</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href=<?php echo $_SERVER['PHP_SELF'] . "?tab=signout"; ?>> Sign out</a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="p-2" id="jobSearch">
      <form  action="<?php echo $_SERVER['PHP_SELF'] ?>" method="GET">
        <div class="form-group">
          <input class="form-control" placeholder="Search for jobs" type="search" class="form-control" name="search"/>
        </div>
        <!-- <button type="submit" class="btn btn-primary">Search</button> -->
      </form>
    </div>

    <div class="row justify-content-center m-1 d-none" id ="accSettingsNavbar">
        <div class='col-8'>
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <a class="navbar-brand" href="#">Account Settings</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse"
                        data-target="#navbarSupportedContent"
                        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo $_SERVER['PHP_SELF'] . "?tab=viewContactInfo"; ?>">Contact
                                Info</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href=<?php echo $_SERVER['PHP_SELF'] . "?tab=viewPaymentInfo"; ?>>Payment
                                Info</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href=<?php echo $_SERVER['PHP_SELF'] . "?tab=viewAccBalance"; ?>>Account
                                Balance</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href=<?php echo $_SERVER['PHP_SELF'] . "?tab=viewPasswordChange"; ?>>Password</a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </div>
    <div id="viewJobs"></div>
    <div id="accountSettings" class="container"></div>
</div>
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/popper.js"></script>
<script src="bootstrap/js/bootstrap.min.js"></script>
</body>
</html>