<?php
require_once 'include/DB_Functions.php';
$db = new DB_Functions();

// json response array
$response = array("error" => 0);

if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['username']) && isset($_POST['password']) && isset($_POST['empid']) && isset($_POST['department']) && isset($_POST['designation'])) {

    // receiving the post params
    $name = $_POST['name'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $empid = $_POST['empid'];
    $department = $_POST['department'];
    $designation = $_POST['designation'];

    if($db->isUserExisted($username) == true) {
    	$response["error"] = 1;
		$response["error_msg"] = "User already exists!";
        echo '<script language="javascript">';
        echo 'alert("User already exists!")';
        echo '</script>';
        echo "<script>setTimeout(\"location.href = 'registration.html';\",0);</script>";
    }
    else {
    	if($db->addUser($name, $email, $username, $password, $empid, $department, $designation)) {
			 $user  = $db->getUserDetails($username);
			 if($user != NULL) {
			 	 $response["error"] = 0;
			 	 $response["error_msg"] = "Register successfully done! ";
                 echo '<script language="javascript">';
                 echo 'alert("Register done successfully")';
                 echo '</script>';
                 echo "<script>setTimeout(\"location.href = 'index.html';\",0);</script>";
			 }
			 else {
			$response["error"] = 2;
            $response["error_msg"] = "User not registered! DB Error!";
            echo '<script language="javascript">';
            echo 'alert("User not registered! DB Error!")';
            echo '</script>';
            echo "<script>setTimeout(\"location.href = 'registration.html';\",0);</script>";
		      }
		}
	}
}
else {
    // required post params is missing
    $response["error"] = 3;
    $response["error_msg"] = "Required parameters User Details is missing!";
    echo '<script language="javascript">';
    echo 'alert("Required parameters User Details is missing!")';
    echo '</script>';
    echo "<script>setTimeout(\"location.href = 'registration.html';\",0);</script>";
}
?>
