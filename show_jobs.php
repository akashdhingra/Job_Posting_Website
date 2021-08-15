<?php /** @noinspection SqlResolve */
include_once "config.php";

session_start();

// first check login status, if not logged in, go to login page
if (!isset($_SESSION['isLogin']) || $_SESSION['isLogin'] == false) {
    header("location: login.php");
}

/*********** Data models variables ***********************************************************************/

$username = $_SESSION['username']; // currently logged in user
$accountType = $_SESSION['accountType']; // current user account type, (job seeker, employer, admin)
$userCategory = getUserCategory($username); // current user's category, (basic, prime, gold)
$accountStatus = getAccountStatus($username); // get account status, true(active), false(not active)
$accountBalance = getAccountBalance($username); // get account balance
$monthlyCharge = getMonthlyCharge($userCategory);
$jobCategories = getAllJobCategories();
// $numOfAppliedJobs = getNumOfAppliedJobs($username);
$paymentInfo = getPaymentInfo();
$autoPay = getAutoOrManual($username); // auto payment or maunal payment, true for auto.
$autoPayString = $autoPay ? "auto" : "manual";

echo "username: $username &nbsp&nbsp&nbsp&nbsp";
echo "accountType: $accountType &nbsp&nbsp&nbsp&nbsp";
echo "category: $userCategory&nbsp&nbsp&nbsp&nbsp";
echo "autoPayment: $autoPayString&nbsp&nbsp&nbsp&nbsp";
echo "accountStatus: $accountStatus&nbsp&nbsp&nbsp&nbsp";
echo "numOfAppliedJobs: $numOfAppliedJobs<br>";
echo "<br>";
/************** End of data models ************************************************************************/

/*********************** Controllers *********************************************************************/
if ($_SERVER['REQUEST_METHOD'] == "GET") {

    require_once "../GUI/view/seekerDashView.php";

    if (isset($_GET['tab'])) {

        $tab = $_GET['tab'];

        echo "$tab<br>";

        switch ($tab) { //Make Account Settings navbar visible

            case "viewAccountSettings":
            case "viewContactInfo":
            case "viewPaymentInfo":
            case "viewAccBalance":
            case "viewPasswordChange":
                echo "<script>document.getElementById('accSettingsNavbar').classList.remove('d-none');</script>";
                echo "<script>document.getElementById('jobSearch').classList.add('d-none');</script>";

                break;
        }

        switch ($tab) {
            case "signout":
                session_destroy();
                goToPage("/GUI/index.php");
                break;
            case "viewJobs": // view posted jobs
                if ($accountStatus) {
                    if (isset($_GET['jobCategory'])) {
                        $jobCategory = $_GET['jobCategory'];
                        $jobsOfCategory = getJobsOfCategory($jobCategory);
                        showPostedJobs($jobsOfCategory);
                    } else {
                        $postedJobsData = getPostedJobsData();
                        showPostedJobs($postedJobsData);
                    }
                } else {
                    echo "<script>alert('Your account has been deactivated, please go to Account Settings to reactive!')</script>";
                }
                break;
            case "viewApplications":
                if ($accountStatus) {
                    $appliedJobsData = getAppliedJobsData($username);
                    showApplications($appliedJobsData);
                } else {
                    echo "<script>alert('Your account has been deactivated, please go to Account Settings to reactive!')</script>";
                }
                break;
            case "viewContactInfo":
                showContactInfo();
                break;
            case "viewPaymentInfo":
                showPaymentInfo($paymentInfo); // show payment info
                break;
            case "viewAccBalance":
                showAccBalance();
                break;
            case "viewPasswordChange":
                showPasswordChange();
                break;
        }
    }

    if (isset($_GET['empContactInfo'])) {
        $empUserName = $_GET['empContactInfo'];
        echo "employer username: $empUserName<br>";
        $data = getEmpContInfo($empUserName);
        viewEmpContInfo($data);
    }

    if (isset($_GET['search'])) {
        $searchedJob = getSearchedJobs($_GET['search']);
        searchJob($searchedJob, $_GET['search']);
    }

}

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $tab = $_REQUEST['tab'];

    switch ($tab) {
        case "applyjob":
            $jobID = $_POST['applyJobID'];
            echo "apply job ID: " . $jobID . "<br>";
            if (applyJob($jobID, $username)) {
                echo "operation success<br>";
            } else {
                echo "operation failed<br>";
            }

            echo "<a href='seekerDash.php?tab=viewApplications'>view applications</a>";
            break;

        case "withdrawapp":
            $jobID = $_POST['withdrawJobID'];
            echo "withdraw application ID: " . $_POST['withdrawJobID'] . "<br>";
            echo "Applicant Username: " . $username . "<br>";
            if (withdrawApplication($jobID, $username)) {
                echo "operation success<br>";
            } else {
                echo "operation failed<br>";
            }

            echo "<a href='seekerDash.php?tab=viewApplications'>view applications</a>";
            break;

        case "acceptoffer":
            $jobID = $_POST['acceptJobID'];
            echo "accept offer application ID: " . $_POST['acceptJobID'] . "<br>";
            echo "Applicant Username: " . $username . "<br>";
            if (acceptApplication($jobID, $username)) {
                echo "operation success<br>";
            } else {
                echo "operation failed<br>";
            }

            echo "<a href='seekerDash.php?tab=viewApplications'>view applications</a>";
            break;

        case "changeContactInfo":
            $firstName = $_POST['firstName'];
            $lastName = $_POST['lastName'];
            $email = $_POST['email'];
            $number = $_POST['number'];
            echo "firstName: " . $firstName . "<br>";
            echo "lastName: " . $lastName . "<br>";
            echo "email: " . $email . "<br>";
            echo "number: " . $number . "<br>";
            if (changeContactInfo($username, $firstName, $lastName, $email, $number)) {
                echo "operation success. <br>";
            } else {
                echo "operation failed. <br>";
            }
            echo "<a href='seekerDash.php?tab=viewContactInfo'>view contact info</a>";
            break;

        case "addCreditCard":
            $ccNumber = $_POST['ccNumber'];
            $ccbNumber = $_POST['ccbNumber'];
            $ccExpiration = $_POST['ccExpiration'];
            echo "ccNumber: " . $_POST['ccNumber'] . "<br>";
            echo "ccbNumber: " . $_POST['ccbNumber'] . "<br>";
            echo "ccExpiration: " . $ccExpiration . "<br>";
            if (insertCreditCard($username, $ccNumber, $ccbNumber, $ccExpiration)) {
                echo "operation success<br>";
            } else {echo "operation failed";}
            echo "<a href='seekerDash.php?tab=viewPaymentInfo'>view payment info</a>";
            break;

        case "addDebitCard":
            $baNumber = $_POST['baNumber'];
            $instituteNumber = $_POST['instituteNumber'];
            $branchNumber = $_POST['branchNumber'];
            echo "baNumber: " . $_POST['baNumber'] . "<br>";
            echo "instituteNumber: " . $_POST['instituteNumber'] . "<br>";
            echo "branchNumber: " . $_POST['branchNumber'] . "<br>";
            if (insertDebitCard($username, $baNumber, $instituteNumber, $branchNumber)) {
                echo "operation success<br>";
            } else {echo "operation failed";}
            echo "<a href='seekerDash.php?tab=viewPaymentInfo'>view payment info</a>";
            break;

        case "changeDebitStatus":
            $op = $_POST['op'];
            $accountNumber = $_REQUEST['accountNumber'];
            echo "account number: " . $_REQUEST['accountNumber'] . "<br>";
            echo "operation: " . $_POST['op'] . "<br>";
            if (changeDebitStatus($username, $op, $accountNumber)) {
                echo "operation success<br>";
            } else {echo "operation failed";}
            echo "<a href='seekerDash.php?tab=viewPaymentInfo'>view payment info</a>";
            break;

        case "changeCreditStatus":
            $op = $_POST['op'];
            $ccNumber = $_REQUEST['ccNumber'];
            $ccExpiry = $_REQUEST['ccExpiry'];
            echo "credit card number: " . $_REQUEST['ccNumber'] . "<br>";
            echo "credit card expiration date: " . $_REQUEST['ccExpiry'] . "<br>";
            echo "operation: " . $_POST['op'] . "<br>";
            if (changeCreditStatus($username, $op, $ccNumber, $ccExpiry)) {
                echo "operation success<br>";
            } else {echo "operation failed";}
            echo "<a href='seekerDash.php?tab=viewPaymentInfo'>view payment info</a>";
            break;

        case "makePayment":
            $amount = $_POST['amount'];
            echo "payment Amount: " . $_POST['amount'] . "<br>";
            if (makePayment($amount)) {
                echo "operation success<br>";
            } else {
                echo "operation failed<br>";
            }
            echo "<a href='seekerDash.php?tab=viewAccBalance'>view account balance</a>";
            break;

        case "changeAccBalance":
            if (isset($_POST['upgrade'])) {
                echo "upgrade to: " . $_POST['upgrade'] . "<br>";
                $category = $_POST['upgrade'];
                if (changeUserCategory($category)) {
                    echo "operation success<br>";
                } else {
                    echo "operation failed<br>";
                }

            }
            if (isset($_POST['downgrade'])) {
                echo "downgrade to: " . $_POST['downgrade'] . "<br>";
                $category = $_POST['downgrade'];
                if (changeUserCategory($category)) {
                    echo "operation success<br>";
                } else {
                    echo "operation failed<br>";
                }

            }
            if (isset($_POST['basic'])) {
                echo "downgrade to: " . $_POST['basic'] . "<br>";
                $category = $_POST['basic'];
                if (changeUserCategory($category)) {
                    echo "operation success<br>";
                } else {
                    echo "operation failed<br>";
                }

            }
            if (isset($_POST['auto'])) {
                echo "Change auto payment to auto? : " . $_POST['auto'] . "<br>";
                $isAuto = $_POST['auto'];
                $defaultPayment = getDefaultPayment();
                if (changeAutoManual($defaultPayment, $isAuto)) {
                    echo "operation success<br>";
                } else {
                    echo "operation failed<br>";
                }

            }
            echo "<br><br><a href='/GUI/seekerDash.php?tab=viewAccBalance'>view account balance</a>";
            break;

        case "passwordChange":
            $prevPass = $_POST['prevPass'];
            $newPass = $_POST['newPass'];
            echo "previous Password: " . $_POST['prevPass'] . "<br>";
            echo "new Password: " . $_POST['newPass'] . "<br>";
            if (changePassword($prevPass, $newPass)) {
                echo "operation success<br>";
            } else {
                echo "operation failed<br>";
            }

            echo "<a href='seekerDash.php?tab=viewPasswordChange'>change password page</a>";
            break;
    }
}

/*********************** End of Controllers ******************************************************/

/************* Data access part *****************************************************************************/
// Get user's category, gold/prime
function getUserCategory($username)
{
    $conn = connectDB();
    $sql = "select Category from applicant where UserName = '$username'";
    $result = $conn->query($sql);
    return $result->fetch_assoc()['Category'];
}

//  get user's payment method, auto or manual, return true for auto, false for manual.
function getAutoOrManual($username)
{
    if (getDefaultPayment()['autoManual'] == 1) {
        return true;
    } else {
        return false;
    }

}

// Get user's account status, true for active, false for freeze
function getAccountStatus($username)
{
    $balance = getAccountBalance($username);
    return $balance >= 0;
}

// Get user's account balance
function getAccountBalance($username)
{
    $conn = connectDB();
    $sql = "select Balance from applicant where UserName = '$username'";
    $result = $conn->query($sql);
    return $result->fetch_assoc()['Balance'];
}

// get monthly payment for different user category
function getMonthlyCharge($userCategory)
{
    if ($userCategory === 'gold') {
        return 20;
    } else if ($userCategory === 'prime') {
        return 10;
    } else {
        return 0;
    }
}

// get posted jobs data from database
function getPostedJobsData()
{
    $data = array();

    $conn = connectDB();
    $sql = "select * from job limit 20";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $job = array("jobID" => $row["JobID"], "title" => $row["Title"], "datePosted" => $row["DatePosted"], "category" => $row["Category"],
                "description" => $row["Description"], "numOfOpenings" => $row["EmpNeeded"], "employerUserName" => $row["EmployerUserName"]);
            $jobStatus = ($row["JobStatus"] == 1) ? "open" : "closed";
            $employerName = getEmployerName($row["EmployerUserName"]);
            $job["jobStatus"] = $jobStatus;
            $job["employerName"] = $employerName;
            array_push($data, $job);
        }
    }
    return $data;
}

// get posted jobs of one category
function getJobsOfCategory($jobCategory)
{
    $data = array();

    $conn = connectDB();
    $sql = "select * from job where Category = '$jobCategory' limit 20";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $job = array("jobID" => $row["JobID"], "title" => $row["Title"], "datePosted" => $row["DatePosted"], "category" => $row["Category"],
                "description" => $row["Description"], "numOfOpenings" => $row["EmpNeeded"], "employerUserName" => $row["EmployerUserName"]);
            $jobStatus = ($row["JobStatus"] == 1) ? "open" : "closed";
            $employerName = getEmployerName($row["EmployerUserName"]);
            $job["jobStatus"] = $jobStatus;
            $job["employerName"] = $employerName;
            array_push($data, $job);
        }
    }
    return $data;
}

//Fetching all job based on the search string
function getSearchedJobs($searchString)
{
    $data = array();

    $conn = connectDB();
    $sql = "SELECT * FROM job WHERE JOBSTATUS = 1 AND title LIKE '%$searchString%'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $job = array("jobID" => $row["JobID"], "title" => $row["Title"], "datePosted" => $row["DatePosted"], "category" => $row["Category"],
                "description" => $row["Description"], "numOfOpenings" => $row["EmpNeeded"], "employerUserName" => $row["EmployerUserName"]);
            $jobStatus = ($row["JobStatus"] == 1) ? "open" : "closed";
            $employerName = getEmployerName($row["EmployerUserName"]);
            $job["jobStatus"] = $jobStatus;
            $job["employerName"] = $employerName;
            array_push($data, $job);
        }
    }
    return $data;

}

// get employer name by employerUserName
function getEmployerName($username)
{
    $conn = connectDB();
    $result = mysqli_query($conn, "select EmployerName from employer where UserName = '$username'");
    if ($result->num_rows > 0) {
        return $result->fetch_assoc()["EmployerName"];
    }
    return "";
}

// get employer contact info
function getEmpContInfo($empUserName)
{
    $data = array();
    $conn = connectDB();
    $result = mysqli_query($conn, "select EmployerName from employer where UserName = '$empUserName'");
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $data["empName"] = $row["EmployerName"];
    }
    $conn2 = connectDB();
    $result2 = mysqli_query($conn, "select * from user where UserName = '$empUserName'");
    if ($result2->num_rows > 0) {
        $row2 = $result2->fetch_assoc();
        $data["empRepFirstName"] = $row2["FirstName"];
        $data["empRepLastName"] = $row2["LastName"];
        $data["empRepEmail"] = $row2["Email"];
        $data["empRepContactNumber"] = $row2["ContactNumber"];
    }
    return $data;
}

// get applied jobs data from database
function getAppliedJobsData($username)
{
    $data = array();
    $conn = connectDB();
    $sql = "select * from application where ApplicantUserName = '$username'";
    $result = mysqli_query($conn, $sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $job = getJobByID($row["JobID"]);
            $app = array("jobID" => $job["jobID"], "title" => $job["title"], "datePosted" => $job["datePosted"], "category" => $job["category"],
                "description" => $job["description"], "numOfOpenings" => $job["numOfOpenings"], "jobStatus" => $job["jobStatus"],
                "employerName" => $job["employerName"], "employerUserName" => $job["employerUserName"]);
            $app["appStatus"] = $row["ApplicationStatus"];
            $app["appDate"] = $row["ApplicationDate"];
            array_push($data, $app);
        }
    }
    return $data;
}

// get job by jobID, select * from Job where jobID = $jobID
/**
 * @param $jobID
 * @return array, job information
 * {
 *  "jobID": 1,
 *  "title": "abc",
 *  "datePosted": "2020-5-10",
 *  "category": "cat1",
 *  "description": "description...",
 *  "numOfOpenings": 3,
 *  "numOfApplications": 3
 * }
 */
function getJobByID($jobID)
{
    $conn = connectDB();
    $result = mysqli_query($conn, "select * from job where JobID = $jobID");
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $job = array("jobID" => $row["JobID"], "title" => $row["Title"], "datePosted" => $row["DatePosted"], "category" => $row["Category"],
            "description" => $row["Description"], "numOfOpenings" => $row["EmpNeeded"], "employerUserName" => $row["EmployerUserName"]);
        $jobStatus = ($row["JobStatus"] == 1) ? "open" : "closed";
        $employerName = getEmployerName($row["EmployerUserName"]);
        $job["jobStatus"] = $jobStatus;
        $job["employerName"] = $employerName;
    }
    return $job;
}

function withdrawApplication($jobID, $username)
{
    $conn = connectDB();
    $sql = "delete from application where ApplicantUserName = '$username' and JobID = $jobID";
    if (mysqli_query($conn, $sql)) {
        return true;
    }

    return false;
}

function acceptApplication($jobID, $username)
{
    $conn = connectDB();
    $sql = "update application set ApplicationStatus = 'accepted'
            where ApplicantUserName = '$username' and JobID = $jobID";
    if (mysqli_query($conn, $sql)) {
        return true;
    } else {
        return false;
    }

}

function applyJob($jobID, $username)
{
    $conn = connectDB();
    $sql = "insert into application (ApplicantUserName, JobID, ApplicationStatus, ApplicationDate)
            values ('$username', $jobID, 'sent', current_date())";
    if (mysqli_query($conn, $sql)) {
        return true;
    } else {
        return false;
    }

}

function changeContactInfo($username, $firstName, $lastName, $email, $number)
{
    $conn = connectDB();
    $sql = "update user set FirstName = '$firstName', LastName = '$lastName', Email = '$email', ContactNumber = '$number'
            where UserName = '$username'";
    if (mysqli_query($conn, $sql)) {
        return true;
    }

    return false;
}

function getPaymentInfo()
{
    global $username;
    $creditCardInfo = getCreditCardInfo($username);
    $debitCardInfo = getDebitCardInfo($username);
    return [$creditCardInfo, $debitCardInfo];
}

// get credit card info
function getCreditCardInfo($username)
{
    $creditCardInfo = array();

    $conn = connectDB();
    $sql = "select *
            from
            (select UserName, CCNumber
            from applicant, applicantcc
            where applicant.UserName = applicantcc.ApplicantUserName) as T natural join creditcardinfo
            where UserName = '$username'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $cci = array("CCNumber" => $row["CCNumber"], "CCExpiry" => $row["ExpireDate"], "CCBNumber" => $row["CCBNumber"],
                "isDefault" => $row["IsDefault"], "autoManual" => $row["Auto_Manual"]);
            array_push($creditCardInfo, $cci);
        }
    }
    return $creditCardInfo;
}

// get debit card info
function getDebitCardInfo($username)
{
    $debitCardInfo = array();

    $conn = connectDB();
    $sql = "select * from
            (select UserName, AccountNumber
                from applicant, applicantpad
                where applicant.UserName = applicantpad.ApplicantUserName) as T natural join padinfo
            where UserName = '$username'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $dci = array("accountNumber" => $row["AccountNumber"], "instituteNumber" => $row["InstituteNumber"],
                "branchNumber" => $row["BranchNumber"], "isDefault" => $row["IsDefault"], "autoManual" => $row["Auto_Manual"]);
            array_push($debitCardInfo, $dci);
        }
    }
    return $debitCardInfo;
}

// get default payment info
function getDefaultPayment()
{
    global $paymentInfo;
    $creditInfo = $paymentInfo[0];
    $debitInfo = $paymentInfo[1];

    for ($i = 0; $i < count($creditInfo); $i++) {
        if ($creditInfo[$i]['isDefault']) {
            $ccNumber = $creditInfo[$i]['CCNumber'];
            $ccExpiry = $creditInfo[$i]['CCExpiry'];
            $isAuto = $creditInfo[$i]['autoManual'];
            return array("type" => "credit", "ccNumber" => $ccNumber, "ccExpiry" => $ccExpiry, "autoManual" => $isAuto);
        }
    }
    for ($i = 0; $i < count($debitInfo); $i++) {
        if ($debitInfo[$i]['isDefault']) {
            $accountNumber = $debitInfo[$i]['accountNumber'];
            $isAuto = $debitInfo[$i]['autoManual'];
            return array("type" => "debit", "accountNumber" => $accountNumber, "autoManual" => $isAuto);
        }
    }
}

function insertCreditCard($username, $ccNumber, $ccbNumber, $ccExpiration)
{
    $month = substr($ccExpiration, 0, 2);
    $year = substr($ccExpiration, 2, 4);
    $expDate = $year . "-" . $month . "-1";

    $flag = false;
    $conn = connectDB();
    $sql = "insert into creditcardinfo (CCNumber, ExpireDate, CCBNumber, IsDefault, Auto_Manual)
            VALUES ($ccNumber, '$expDate', $ccbNumber, 0, 0)";
    if (mysqli_query($conn, $sql)) {
        $flag = true;
    }

    if ($flag) {
        if (insertApplicantCC($ccNumber, $username)) {
            return true;
        }

    }
    return false;
}

function insertApplicantCC($ccNumber, $username)
{
    $conn = connectDB();
    $sql = "insert into applicantcc (ApplicantUserName, CCNumber) values ('$username', '$ccNumber')";
    if (mysqli_query($conn, $sql)) {
        return true;
    }
    return false;
}

// insert debit card info
function insertDebitCard($username, $baNumber, $instituteNumber, $branchNumber)
{
    $conn = connectDB();
    $flag = false;
    $sql = "insert into padinfo (AccountNumber, InstituteNumber, BranchNumber, IsDefault, Auto_Manual)
            values ('$baNumber', '$instituteNumber', '$branchNumber', 0, 0)";
    if (mysqli_query($conn, $sql)) {
        $flag = true;
    }

    if ($flag) {
        if (insertApplicantPad($username, $baNumber)) {
            return true;
        }

    }
    return false;
}

// insert into employerpad table
function insertApplicantPad($username, $baNumber)
{
    $conn = connectDB();
    $sql = "insert into applicantpad (ApplicantUserName, AccountNumber) values ('$username', '$baNumber')";
    if (mysqli_query($conn, $sql)) {
        return true;
    }
    return false;
}

function changeDebitStatus($username, $op, $accountNumber)
{
    if ($op === 'delete') {
        $conn = connectDB();
        $sql = "delete from applicantpad where ApplicantUserName = '$username' and AccountNumber = '$accountNumber'";
        if (mysqli_query($conn, $sql)) {
            $conn2 = connectDB();
            $sql2 = "delete from padinfo where AccountNumber = '$accountNumber'";
            if (mysqli_query($conn2, $sql2)) {
                return true;
            }

        }
    } else if ($op === 'setDefault') {
        setUndefault();
        $conn = connectDB();
        $sql = "update padinfo set IsDefault = 1 where AccountNumber = '$accountNumber'";
        if (mysqli_query($conn, $sql)) {
            return true;
        }

    }
    return false;
}

// change credit card status
function changeCreditStatus($username, $op, $ccNumber, $ccExpiry)
{
    if ($op === 'delete') {
        $conn = connectDB();
        $sql = "delete from applicantcc where ApplicantUserName = '$username' and CCNumber = '$ccNumber'";
        if (mysqli_query($conn, $sql)) {
            $conn2 = connectDB();
            $sql2 = "delete from creditcardinfo where CCNumber = '$ccNumber' and ExpireDate = '$ccExpiry'";
            if (mysqli_query($conn2, $sql2)) {
                return true;
            }

        }
    } else if ($op === 'setDefault') {
        setUndefault();
        $conn = connectDB();
        $sql = "update creditcardinfo set IsDefault = 1 where CCNumber = '$ccNumber' and ExpireDate = '$ccExpiry'";
        if (mysqli_query($conn, $sql)) {
            return true;
        }

    }
    return false;
}

// Change current default to undefault
function setUndefault()
{
    global $username;
    global $paymentInfo;
    $creditInfo = $paymentInfo[0];
    $debitInfo = $paymentInfo[1];

    for ($i = 0; $i < count($creditInfo); $i++) {
        if ($creditInfo[$i]['isDefault']) {
            $ccNumber = $creditInfo[$i]['CCNumber'];
            $ccExpiry = $creditInfo[$i]['CCExpiry'];
            $conn = connectDB();
            $sql = "update creditcardinfo set IsDefault = 0 where CCNumber = '$ccNumber' and ExpireDate = '$ccExpiry'";
            if (!mysqli_query($conn, $sql)) {
                echo "error in setUndefault";
            }

        }
    }
    for ($i = 0; $i < count($debitInfo); $i++) {
        if ($debitInfo[$i]['isDefault']) {
            $accountNumber = $debitInfo[$i]['accountNumber'];
            $conn = connectDB();
            $sql = "update padinfo set IsDefault = 0 where AccountNumber = '$accountNumber'";
            if (!mysqli_query($conn, $sql)) {
                echo "error in setUndefault";
            }

        }
    }
}

function makePayment($amount)
{
    global $username;
    $conn = connectDB();
    $sql = "update applicant set Balance = Balance+$amount where UserName = '$username'";
    if (mysqli_query($conn, $sql)) {
        return true;
    }

    return false;
}

function changeUserCategory($category)
{
    global $username;
    $conn = connectDB();
    $sql = "update applicant set Category = '$category' where UserName = '$username'";
    if (mysqli_query($conn, $sql)) {
        return true;
    }
    return false;
}

function changeAutoManual($defaultPayment, $isAuto)
{
    $b = $isAuto === 'true' ? 1 : 0;
    $conn = connectDB();
    $sql = "";
    $s = $defaultPayment['type'];
    if ($s === 'credit') {
        $ccNumber = $defaultPayment['ccNumber'];
        $ccExpiry = $defaultPayment['ccExpiry'];
        $sql = "update creditcardinfo set Auto_Manual = $b
                where CCNumber = '$ccNumber' and ExpireDate = '$ccExpiry'";
    } else if ($s === 'debit') {
        $accountNumber = $defaultPayment['accountNumber'];
        $sql = "update padinfo set Auto_Manual = $b
                where AccountNumber = '$accountNumber'";
    }
    if (mysqli_query($conn, $sql)) {
        return true;
    }

    return false;
}

function changePassword($prevPass, $newPass)
{
    global $username;
    $conn = connectDB();
    $result = mysqli_query($conn, "select Password from user where UserName = '$username'");
    if ($result->fetch_assoc()['Password'] !== $prevPass) {
        echo "<script>alert('previous password not correct')</script>";
        return false;
    } else {
        $conn2 = connectDB();
        $sql = "update user set Password = '$newPass' where UserName = '$username'";
        if (mysqli_query($conn2, $sql)) {
            return true;
        }

        return false;
    }
}

function getAllJobCategories()
{
    $data = array();
    $conn = connectDB();
    $sql = "select distinct Category from job;";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $c = $row["Category"];
            array_push($data, $c);
        }
    }
    return $data;
}

/************************* End of data access *****************************************************/

/****************** Front-end view part ******************************************************/

//Search bar for job seeker
function searchJob($searchResult, $searchString)
{
    $message = count($searchResult) ? "Showing search result for " . "<mark>" . $searchString . "</mark>" : "Sorry no available job to display";
    $html = "<span>" . $message . "</span>";
    for ($i = 0; $i < count($searchResult); $i++) {

        $ID = $searchResult[$i]['jobID'];
        $empUserName = $searchResult[$i]['employerUserName'];

        $html .=
            "<div class='row align-items-center justify-content-center'>" .
            "    <div class='col-8 border border-dark rounded'>" .
            "       <p class='jobTitle'><b>" . $searchResult[$i]['title'] . "</b></p><br>" .
            "       <p><b>Job ID: </b>" . $searchResult[$i]['jobID'] . "</p>" .
            "       <p><b>Date Posted: </b>" . $searchResult[$i]['datePosted'] . "</p>" .
            "       <p><b>Employer: </b><a href='seekerDash.php?empContactInfo=$empUserName'>" . $searchResult[$i]['employerName'] . "</a></p>" .
            "       <p><b>Category: </b>" . $searchResult[$i]['category'] . "</p>" .
            "       <p><b>Description: </b>" . $searchResult[$i]['description'] . "</p>" .
            "       <p><b># Openings: </b>" . $searchResult[$i]['numOfOpenings'] . "</p>" .
            "       <p><b>Job Status: </b>" . $searchResult[$i]['jobStatus'] . "</p>" .
            "    </div>" .
            "    <div class='col-2 d-flex justify-content-center '>" .
            "    <form action='" . $_SERVER['PHP_SELF'] . "?tab=applyjob' method='post'>" .
            "       <button type='submit' name='applyJobID' value='$ID' class='btn btn-success'> Apply </button>" .
            "    </form>" .
            "    </div>" .
            "</div>";
    }
    echo "<script>document.getElementById('viewJobs').innerHTML = \"" . $html . "\"</script>";

}

function showPostedJobs($postedJobsData)
{
    global $jobCategories;

    $html =
        "<div class='row justify-content-center'>" .
        "    <div class = 'col-4'>" .
        "    <form action='" . $_SERVER['PHP_SELF'] . "'>" .
        "       <div class='form-group text-center'>" .
        "            <label for='selectCategory'>Select category:</label>" .
        "            <select class='form-control' id='selectCategory' name='jobCategory'>" .
        "                 <option>...</option>";

    for ($i = 0; $i < count($jobCategories); $i++) {

        $category = $jobCategories[$i];
        $html .=
            "                 <option value='$category'>$category</option>";
    }
    $html .=
        "            </select>" .
        "      </div>" .
        "   <button class='btn btn-primary' type='submit' name='tab' value='viewJobs'>Submit</button>" .
        "   </form>" .
        "   </div>" .
        "</div>";

    for ($i = 0; $i < count($postedJobsData); $i++) {

        $ID = $postedJobsData[$i]['jobID'];
        $empUserName = $postedJobsData[$i]['employerUserName'];

        $html .=
            "<div class='row align-items-center justify-content-center'>" .
            "    <div class='col-8 border border-dark rounded'>" .
            "       <p class='jobTitle'><b>" . $postedJobsData[$i]['title'] . "</b></p><br>" .
            "       <p><b>Job ID: </b>" . $postedJobsData[$i]['jobID'] . "</p>" .
            "       <p><b>Date Posted: </b>" . $postedJobsData[$i]['datePosted'] . "</p>" .
            "       <p><b>Employer: </b><a href='seekerDash.php?empContactInfo=$empUserName'>" . $postedJobsData[$i]['employerName'] . "</a></p>" .
            "       <p><b>Category: </b>" . $postedJobsData[$i]['category'] . "</p>" .
            "       <p><b>Description: </b>" . $postedJobsData[$i]['description'] . "</p>" .
            "       <p><b># Openings: </b>" . $postedJobsData[$i]['numOfOpenings'] . "</p>" .
            "       <p><b>Job Status: </b>" . $postedJobsData[$i]['jobStatus'] . "</p>" .
            "    </div>" .
            "    <div class='col-2 d-flex justify-content-center '>" .
            "    <form action='" . $_SERVER['PHP_SELF'] . "?tab=applyjob' method='post'>" .
            "       <button type='submit' name='applyJobID' value='$ID' class='btn btn-success'> Apply </button>" .
            "    </form>" .
            "    </div>" .
            "</div>";
    }
    echo "<script>document.getElementById('viewJobs').innerHTML = \"" . $html . "\"</script>";
}

function showApplications($data)
{

    $html = "";

    for ($i = 0; $i < count($data); $i++) {
        $appStatus = $data[$i]['appStatus'];
        $jobID = $data[$i]['jobID'];
        $empUserName = $data[$i]['employerUserName'];

        $html .=
            "<div class='row align-items-center justify-content-center'>" .
            "    <div class='col-8 border border-dark rounded'>" .
            "       <p class='jobTitle'><b>" . $data[$i]['title'] . "</b></p><br>" .
            "       <p><b>Job ID: </b>" . $data[$i]['jobID'] . "</p>" .
            "       <p><b>Date Posted: </b>" . $data[$i]['datePosted'] . "</p>" .
            "       <p><b>Employer: </b><a href='seekerDash.php?empContactInfo=$empUserName'>" . $data[$i]['employerName'] . "</a></p>" .
            "       <p><b>Category: </b>" . $data[$i]['category'] . "</p>" .
            "       <p><b>Description: </b>" . $data[$i]['description'] . "</p>" .
            "       <p><b># Openings: </b>" . $data[$i]['numOfOpenings'] . "</p>" .
            "       <p><b>Job Status: </b>" . $data[$i]['jobStatus'] . "</p>" .
            "       <p><b>Application Status: </b>" . $data[$i]['appStatus'] . "</p>" .
            "       <p><b>Application Date: </b>" . $data[$i]['appDate'] . "</p>" .
            "    </div>" .
            "    <div class='col-2 d-flex text-center '>" .
            "    <form action='" . $_SERVER['PHP_SELF'] . "?tab=withdrawapp' method='post'>" .
            "       <button type='submit' name='withdrawJobID' value='$jobID' class='btn btn-danger'> Withdraw </button>" .
            "    </form>";
        if ($appStatus === 'accepted') {
            $html .=
                "    <form action='" . $_SERVER['PHP_SELF'] . "?tab=acceptoffer' method='post'>" .
                "       <button type='submit' name='acceptJobID' value='$jobID' class='btn btn-success'> Accept Offer </button>" .
                "    </form>" .
                "    </div>" .
                "</div>";
        } else {
            $html .=
                "    </div>" .
                "</div>";
        }
    }
    echo "<script>document.getElementById('viewJobs').innerHTML = \"" . $html . "\"</script>";
}

function viewEmpContInfo($data)
{

    $empName = $empRepName = $empRepEmail = $empRepNumber = "To do";
    $html =
        "<div class = 'row justify-content-center'>" .
        "     <div class = 'col-8'>" .
        "          <p><b>Employer Name: </b> " . $data["empName"] . "</p>" .
        "          <p><b>Representative Name: </b> " . $data["empRepFirstName"] . " " . $data["empRepFirstName"] . "</p>" .
        "          <p><b>Representative Email: </b> " . $data["empRepEmail"] . "</p>" .
        "          <p><b>Representative Number: </b>" . $data["empRepContactNumber"] . "</p>" .
        "     </div>";
    "</div>";

    echo "<script>document.getElementById('viewJobs').innerHTML = \"" . $html . "\"</script>";
}

function showPaymentInfo($paymentInfo)
{

    $creditCardInfo = $paymentInfo[0];
    $debitCardInfo = $paymentInfo[1];

    $html =
        "<div class = 'row justify-content-center align-items-center'>" .
        "     <div class = 'col-8 text-center'>" .
        "          <button class = 'btn btn-success' onclick='editCreditCardSeeker()'>Add Credit Card</button>" .
        "          <button class = 'btn btn-success' onclick='editDebitCardSeeker()'>Add Bank Card</button>" .
        "     </div>" .
        "</div>";

    for ($i = 0; $i < count($creditCardInfo); $i++) {

        $html = showCreditCardInfo($html, $creditCardInfo[$i]);

    }

    for ($i = 0; $i < count($debitCardInfo); $i++) {

        $html = showDebitCardInfo($html, $debitCardInfo[$i]);

    }

    echo "<script>document.getElementById('accountSettings').innerHTML = \"" . $html . "\"</script>";
}

function showDebitCardInfo(string $html, $data): string
{
    $isDefault = $data["isDefault"];
    $accountNumber = $data["accountNumber"];
    $instituteNumber = $data["instituteNumber"];
    $branchNumber = $data["branchNumber"];

    $html .=
        "<div class = 'row justify-content-center align-items-center' style='margin-left: 10px'>";

    if ($isDefault == true) { // Make green border

        $html .=
            "<div class = 'col-8 border border-success rounded'>";
    } else { //make grey border

        $html .=
            "<div class = 'col-8 border rounded'>";
    }
    $html .=
        "     <p><b>Bank Account Number: </b>$accountNumber</p>" .
        "     <p><b>Institute Number: </b>$instituteNumber</p>" .
        "     <p><b>Branch Number: </b>$branchNumber</p>" .
        "</div>";

    if ($isDefault == false) {

        $html .=
            "<div class = 'col-2 text-center'>" .
            "   <form action='" . $_SERVER['PHP_SELF'] . "?tab=changeDebitStatus&accountNumber=$accountNumber' method='post'>" .
            "     <button type=submit name='op' value='setDefault' class = 'btn btn-primary'>Set Default</button>" .
            "     <button type=submit name='op' value='delete' class = 'btn btn-danger'>Delete</button>" .
            "   </form>" .
            "</div>";
    } else {

        $html .=
            "<div class = 'col-2 text-center'>" .
            "</div>";
    }

    $html .=
        "</div>";

    return $html;
}

function showCreditCardInfo(string $html, $data): string
{
    $isDefault = $data["isDefault"];
    $CCNumber = $data["CCNumber"];
    $CCExpiry = $data["CCExpiry"];
    $CCBNumber = $data["CCBNumber"];

    $html .=
        "<div class = 'row justify-content-center align-items-center' style='margin-left: 10px'>";

    if ($isDefault == true) { // Make green border

        $html .=
            "<div class = 'col-8 border border-success rounded'>";
    } else { // Make grey border

        $html .=
            "<div class = 'col-8 border rounded'>";
    }

    $html .=
        "     <p><b>Credit Card Number: </b>$CCNumber</p>" .
        "     <p><b>Expiry Date: </b>$CCExpiry</p>" .
        "     <p><b>CCB Number: </b>$CCBNumber</p>" .
        "</div>";

    if ($isDefault == false) {

        $html .=
            "   <div class = 'col-2 text-center'>" .
            "   <form action='" . $_SERVER['PHP_SELF'] . "?tab=changeCreditStatus&ccNumber=$CCNumber&ccExpiry=$CCExpiry' method='post'>" .
            "       <button type='submit' name='op' value='setDefault' class = 'btn btn-primary'>Set Default</button>" .
            "       <button type='submit' name='op' value='delete' class = 'btn btn-danger'>Delete</button>" .
            "   </form>" .
            "   </div>";
    } else {

        $html .=
            "<div class = 'col-2 text-center'>" .
            "</div>";
    }

    $html .=
        "</div>";

    return $html;
}

function showContactInfo()
{
    $url = $_SERVER['PHP_SELF'] . "?tab=changeContactInfo";

    $html =
        "<div class = 'row justify-content-center'>" .
        "  <div class = 'col-8'>" .
        "           <form action='$url' method='post'>" .
        "              <div class='form-group'>" .
        "                  <label for='firstName'><b>First Name</b></label>" .
        "                  <input type='text' class='form-control' id='firstName' name='firstName' placeholder='Enter first name' required>" .
        "              </div>" .
        "              <div class='form-group'>" .
        "                  <label for='lastName'><b>Last Name</b></label>" .
        "                  <input type='text' class='form-control' id='lastName' name='lastName' placeholder='Enter last name' required>" .
        "              </div>" .
        "              <div class='form-group'>" .
        "                  <label for='email'><b>Email</b></label>" .
        "                  <input type='email' class='form-control' id='email' name='email' placeholder='Enter email' required>" .
        "              </div>" .
        "              <div class='form-group'>" .
        "                  <label for='number'><b>Number</b></label>" .
        "                  <input type='text' class='form-control' id='number' name='number' placeholder='Enter phone number' required>" .
        "              </div>" .
        "              <input class='btn btn-primary' type='submit' value='Submit'>" .
        "           </form>" .
        "       </div>" .
        "  </div>" .
        "</div>";

    echo "<script>document.getElementById('accountSettings').innerHTML = \"" . $html . "\"</script>";
}

function showAccBalance()
{

    global $accountBalance;

    $html = getBalanceHTML($accountBalance);

    if (true) {
        $html = getMonthlyPaymentRadioButtonsHTML($html);
    }
    $html = getSeekerCategoryHTML($html);

    echo "<script>document.getElementById('accountSettings').innerHTML = \"" . $html . "\"</script>";
}

/**
 * @param string $html
 * @return string
 */
function getSeekerCategoryHTML(string $html): string
{
    global $userCategory;
    $toolTipSeekerBasic = "You can only view jobs but cannot apply. No charge";
    $toolTipSeekerPrime = "You can view jobs as well as apply for up to five jobs. A monthly charge of $10 will be applied. ";
    $toolTipSeekerGold = "You can view and apply to as many jobs as you want. A monthly charge of $20 will be applied.";

    $html .=
        "<div class = 'row justify-content-center'>" .
        "     <div class = 'col-8'>" .
        "          <div><b>Job Seeker Category: $userCategory</b></div>" .
        "       <form action='".$_SERVER['PHP_SELF']."?tab=changeAccBalance' method='post'>".
        "          <button type='submit' class='btn btn-secondary' name='basic' value='basic'>Change to Basic</button>".
        "          <button type='submit' class='btn btn-info' name='downgrade' value='prime'>Change to Prime</button>".
        "          <button type='submit' class='btn btn-warning' name='upgrade' value='gold'>Change to Gold</button>".
        "       </form>".
        "     </div>" .
        "</div>";

    return $html;
}

/**
 * @param string $html
 * @return string
 */
function getMonthlyPaymentRadioButtonsHTML(string $html): string
{
    global $autoPay;
    $paymentMethod = $autoPay ? "Auto" : "Manual";
    $html .=
        "<div class = 'row justify-content-center'>" .
        "     <div class = 'col-8'>" .
        "          <div><b>Payment Method: $paymentMethod</b></div>" .
        "     </div>" .
        "</div>" .
        "<div class='row justify-content-center mt-3'>".
        "   <div class='col-8'>".
        "       <form action='".$_SERVER['PHP_SELF']."?tab=changeAccBalance' method='post'>".
        "          <button type='submit' class='btn btn-info' name='auto' value='true'>Change to Auto payment</button>".
        "          <button type='submit' class='btn btn-info' name='auto' value='false'>Change to Manual payment</button>".
        "       </form>".
        "     </div>" .
        "</div>";

    return $html;
}

/**
 * @param float $balance
 * @return string
 */
function getBalanceHTML(float $balance): string
{
    global $monthlyCharge;
    global $autoPay;

    $html =
        "<div class = 'row justify-content-center'>" .
        "     <div class = 'col-6'>";

    if ($balance >= 0) {

        $html .=
            "     <div><b>Account Balance:</b> $$balance</div>" .
            "     <div>Your account is in good standing.</div>" .
            "</div>" .
            "<div class ='col-2'>";
    } else {

        $html .=
            "     <div><span class='badge badge-danger'>Account Balance </span>$$balance</div>" .
            "     <div>Your account limited. Make a payment to gain full access</div>" .
            "</div>" .
            "<div class ='col-2'>";
    }

    if ($balance < 0 || (!$autoPay)) {

        $html .=
            "          <form action='" . $_SERVER['PHP_SELF'] . "?tab=makePayment' method='post' onsubmit='return confirmPayment()'>" .
            "          <button class='btn btn-success' type='submit' name='amount' value='$monthlyCharge'> Make Payment $$monthlyCharge </button>" .
            "          </form>" .
            "     </div>" .
            "</div>";
    } else { // balance in good standing and auto monthly payment

        $html .=
            "     </div>" .
            "</div>";
    }

    return $html;
}

function showPasswordChange()
{

    $html =
        "<form action='" . $_SERVER['PHP_SELF'] . "?tab=passwordChange' method='post' onsubmit='return confirmPassword()'>" .
        "     <div class = 'row justify-content-center'>" .
        "        <div class = 'col-8'>" .
        "             <div class='form-group'>" .
        "                  <label for='prevPass'><b>Previous Password</b></label> " .
        "                  <input type='password' class='form-control' placeholder='Enter previous password' id='prevPass' name='prevPass' value='' required>" .
        "              </div>" .
        "              <div class='form-group'>" .
        "                   <label for='newPass'><b>New Password</b></label> " .
        "                   <input type='password' class='form-control' placeholder='Enter new password' id='newPass' name='newPass' value='' required>" .
        "              </div>" .
        "              <div class='form-group'>" .
        "                   <label for='conNewPass'><b>Confirm New Password</b></label> " .
        "                   <input type='password' class='form-control' placeholder='Confirm password' id='conNewPass' name='conNewPass' value='' required>" .
        "              </div>" .
        "                   <input class='btn btn-primary' type='submit' value='Submit'>" .
        "         </div>" .
        "    </div>" .
        "</form>";

    echo "<script>document.getElementById('accountSettings').innerHTML = \"" . $html . "\"</script>";
}