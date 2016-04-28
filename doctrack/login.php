<?php
require_once 'include/DB_Functions.php';
$db = new DB_Functions();

// json response array
$response = array("error" => 0);

if (isset($_POST['username']) && isset($_POST['password'])) {

    // receiving the post params
    $username = $_POST['username'];
    $password = $_POST['password'];

    // get the user details
    $user = $db->getUserDetails($username);

    if ($user != NULL) {
        // user is found , now check for the correct login credentials
        if ( $password  == $user["password"]) {
            session_start();
            $_SESSION['uuid'] = $user['uuid'];
            $_SESSION['name']= $user["name"];
            $_SESSION['username']= $username;
            $_SESSION['designation'] = $user['designation'];
            $_SESSION['department'] = $user['department'];
            $_SESSION['empid'] = $user['empid'];
            $accno = $db->getAccountNo($username);
            $_SESSION['accno'] = $accno;
            $_SESSION['logged_in'] = true;

            $response["error"] = 0;
            $response["user"]["uuid"] = $user["uuid"];
            $response["user"]["name"] = $user["name"];
            if($db->isAccnoAdded($username)) {
                header('Location: home.php');
            }
            else {
                header('Location: accountno.html');
            }
        }
        else {

            // wrong login credentials
            $response["error"] = 1;
            $response["error_msg"] = "Login credentials wrong.";
            $response["password"] = $password;
            echo '<script language="javascript">';
            echo 'alert("Login credentials wrong.")';
            echo '</script>';
            echo "<script>setTimeout(\"location.href = 'index.html';\",0);</script>";
        }

    } else {
        // user is not found
        $response["error"] = 2;
        $response["error_msg"] = "Username doesn't exists.";
        echo '<script language="javascript">';
        echo 'alert("Username doesnt exists.")';
        echo '</script>';
        echo "<script>setTimeout(\"location.href = 'index.html';\",0);</script>";
    }
} else {
    // required post params is missing
    $response["error"] = 3;
    $response["error_msg"] = "Required parameters username or password is missing!";
    echo '<script language="javascr.0ipt">';
    echo 'alert("Required parameters username or password is missing!")';
    echo '</script>';
    echo "<script>setTimeout(\"location.href = 'index.html';\",0);</script>";
}
?>
