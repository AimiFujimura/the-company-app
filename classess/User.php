<?php
    require_once 'Database.php';

    class User extends Database{
        public function store($request){
            $first_name = $request['firstname'];
            $last_name = $request['lastname'];
            $username = $request['username'];
            $user_password = $request['password'];

            $password = password_hash($user_password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO users(`first_name`,`last_name`,`username`,`password`) VALUES('$first_name','$last_name','$username','$password')";

            if($this->conn->query($sql)){
                header('location: ../views');
                exit();
            }else{
                die("Error in insertint data" . $this->conn->error);
            }
        }

        public function login($request){
            $username = $request['username'];
            $password = $request['password'];

            $sql = "SELECT * FROM users WHERE username = '$username'";
            $result = $this->conn->query($sql);

            if($result->num_rows == 1){
                $user = $result->fetch_assoc();

                if(password_verify($password,$user['password'])){
                    session_start();
                    $_SESSION['id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['fullname'] = $user['first_name'] . " " . $user['last_name'];

                    header('location: ../views/dashboard.php');
                    exit;
                }else{
                    die("Password is incorrect.");
                }
            }else {
                die("Username not found.");
            }
        }

        public function logout(){
            session_start();
            session_unset();
            session_destroy();

            header('location: ../views');
            exit;
        }

        public function getAllUsers(){
            $sql = "SELECT id, first_name, last_name, username, photo FROM users";
            if($result = $this->conn->query($sql)){
                return $result;
            }else{
                die("Error in retrieving users." . $this->conn->error);
            }
        }
    }

?>