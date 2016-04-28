<?php

class DB_Functions {

    private $conn;
    // constructor
    function __construct() {
        require_once 'include/DB_Connect.php';
        // connecting to database
        $db = new DB_Connect();
        $this->conn = $db->connect();
    }

    // destructor
    function __destruct() {

    }

    /**
     * Storing new user
     * @param name, email, username, passHash, empid, department, designation
     * returns boolean for successful addition
     */
    public function addUser($name, $email, $username, $password, $empid, $department, $designation) {
        $stmt = $this->conn->prepare("INSERT INTO doctrack_users(uuid, name, email, username, password, empid, department, designation, created_at) VALUES(?, ?, ?, ?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("ssssssss", uniqid('dt'), $name, $email, $username, $password, $empid, $department, $designation);
        $result = $stmt->execute();
        $stmt->close();

        // check for successful store
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function getUserDetails($username) {
        $stmt = $this->conn->prepare("SELECT * FROM doctrack_users WHERE username = ? LIMIT 1");
        $stmt->bind_param("s",$username);
        if($stmt->execute()) {
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            return $user;
        }
        else {
            return NULL;
        }
    }

    /**
     * Check user is existed or not
     * @param regno
     * returns boolean for existence
     */
    public function isUserExisted($username) {
        $stmt = $this->conn->prepare("SELECT username from doctrack_users WHERE username = ? LIMIT 1");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // user existed
            $stmt->close();
            return true;
        } else {
            // user not existed
            $stmt->close();
            return false;
        }
    }

    public function addUserAccount($username, $accno) {
        $user = $this->getUserDetails($username);
        $uuid =  $user['uuid'];
        $stmt = $this->conn->prepare("INSERT INTO doctrack_user_acc(uuid, accno, created_at) VALUES(?, ?, NOW())");
        $stmt->bind_param("ss", $uuid, $accno);
        $result = $stmt->execute();
        $stmt->close();
        // check for successful store
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function isAccnoAdded($username) {
        $stmt = $this->conn->prepare("SELECT uuid from doctrack_users WHERE username = ? LIMIT 1");
        $stmt->bind_param("s", $username);
        if($stmt->execute()) {
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            $uuid = $user['uuid'];
            $stmt = $this->conn->prepare("SELECT uuid from doctrack_user_acc WHERE uuid = ? LIMIT 1");
            $stmt->bind_param("s", $uuid);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                // user existed
                $stmt->close();
                return true;
            } else {
                // user not existed
                $stmt->close();
                return false;
            }
        }
        else {
            return false;
        }
    }

    public function getAccountNo($username) {
        $stmt = $this->conn->prepare("SELECT uuid from doctrack_users WHERE username = ? LIMIT 1");
        $stmt->bind_param("s", $username);
        if($stmt->execute()) {
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            $uuid = $user['uuid'];
            $stmt = $this->conn->prepare("SELECT accno from doctrack_user_acc WHERE uuid = ? LIMIT 1");
            $stmt->bind_param("s", $uuid);
            if($stmt->execute()) {
                $user = $stmt->get_result()->fetch_assoc();
                $stmt->close();
                $accno = $user['accno'];
                return $accno;
            }
            else {
                return NULL;
            }
        }
        else {
            return NULL;
        }
    }

    public function submitTelephoneBill($uuid, $landline, $mobile1, $mobile2, $from_month, $to_month, $totalcharges) {
        $stmt = $this->conn->prepare("INSERT INTO telephone_bill(uuid, landline, mobile1, mobile2, from_month, to_month, totalcharges, created_at) VALUES(?, ?, ?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("ssssssd", $uuid, $landline, $mobile1, $mobile2, $from_month, $to_month, $totalcharges);
        $result = $stmt->execute();
        $error = $stmt->error;
        $stmt->close();
        // check for successful store
        if ($result) {
                return true;
            } else {
                return false;
        }
    }
}

?>
