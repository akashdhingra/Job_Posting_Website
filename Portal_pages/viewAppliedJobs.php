<?php
require_once "../config.php";
session_start();
$userid = $_SESSION['$id'];
$sql = "SELECT * FROM Job WHERE jobID = $userid";	

$result = mysqli_query($conn,$sql);

$jobs = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>


<div>
  <h4 class = "centre grey-text">Applied Jobs</h4>
  <form action = "" method="POST">
	<div class = "container">
		<div class="column">
			<?php foreach($jobs as $jobs):?>

			<div class= "col s6 md3">
				<div class = "card z-depth-0">
					<div class = "mx-auto">
						<h6> <?php echo "Job ID :" .htmlspecialchars($jobs['jobID']);  ?></h6>
			            <h6> <?php echo "Job title :" . htmlspecialchars($jobs['title']);  ?></h6>
			            <h6> <?php echo "Category :" . htmlspecialchars($jobs['category']);  ?></h6>
			            <h6> <?php echo "PostDate :" . htmlspecialchars($jobs['postDate']);  ?></h6>
			            <h6> <?php echo "No of employees needed :" . htmlspecialchars($jobs['empNeed']);  ?></h6>
			            <h6> <?php echo "No of employees Applied :" . htmlspecialchars($jobs['empApplied']);  ?></h6>
			            <h6> <?php echo "Accepted Offers :" . htmlspecialchars($jobs['acceptedOffer']);  ?></h6>
			            <h6> <?php echo "Status of Job :" . htmlspecialchars($jobs['statusOpenClose']);  ?></h6>
			            <h6> <?php echo "User ID :" . htmlspecialchars($jobs['userID']);  ?></h6>

					</div>
			</div>
		</div>

	<?php endforeach; ?>
		
	    </div>
    </div>
    </form>
</div>
<br>
<a class="nav-link" href="JobSeeker.php">Home</a>