<?php

    class UserManager{
        private $db;

        public function __construct($host, $username, $password, $database){
            $this->db = new mysqli($host, $username, $password, $database);

            if($this->db->connect_error){
                die("[UserManager] Connection failed: " . $this->db->connect_error);
            }
        }

        public function login($login, $password){
            // Use prepared statements to prevent SQL injection
            $stmt = $this->db->prepare("SELECT ID, Pass, isBan FROM Users WHERE Login = ?");
            $stmt->bind_param("s", $login);
            $stmt->execute();
            $stmt->bind_result($userId, $hashedPassword, $isBan);

            if($stmt->fetch() && password_verify($password, $hashedPassword)){
                if ($isBan == 1) {
                    return ['success' => false, 'error' => 'User is banned.'];
                }

                // Update LastLogin and IP columns
                //$this->updateUserLoginInfo($userId);

                $_SESSION['logged'] = true;
                $_SESSION['userId'] = $userId;
                return ['success' => true, 'userId' => $userId];
            }else{
                $_SESSION['logged'] = false;
                return ['success' => false, 'error' => 'Invalid login credentials.'];
            }
        }

        private function updateUserLoginInfo($userId) {
            $ip = $_SERVER['REMOTE_ADDR']; // Get user's IP address
            $currentDateTime = date('Y-m-d H:i:s');
        
            $stmt = $this->db->prepare("UPDATE Users SET LastLogin = ?, IP = ? WHERE ID = ?");
            $stmt->bind_param("ssi", $currentDateTime, $ip, $userId);
            $stmt->execute();
        }

        public function register($name, $login, $password) {
            // Use prepared statements to prevent SQL injection
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $this->db->prepare("INSERT INTO Users (Name, Login, Pass) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $login, $hashedPassword);

            if ($stmt->execute()) {
                return ['success' => true];
            } else {
                return ['success' => false, 'error' => $stmt->error];
            }
        }

        public function getUserInfo($userId){
            $stmt = $this->db->prepare("SELECT * FROM Users WHERE ID = ?");
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();
    
            return $result->fetch_assoc();
        }

        public function isAdmin($userId) {
            $stmt = $this->db->prepare("SELECT isAdmin FROM Users WHERE ID = ?");
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $stmt->bind_result($isAdmin);

            return ($stmt->fetch() && $isAdmin);
        }

        /*public function displayServers($userId) {
            if ($this->isAdmin($userId)) {
                $result = $this->db->query("SELECT * FROM Servers");
            } else {
                $stmt = $this->db->prepare("SELECT * FROM Servers WHERE IDOwner = ?");
                $stmt->bind_param("i", $userId);
                $stmt->execute();
                $result = $stmt->get_result();
            }

            $servers = [];
            while ($row = $result->fetch_assoc()) {
                $servers[] = $row;
            }

            return $servers;
        }*/

        /*public function displayNodes($userId) {
            if ($this->isAdmin($userId)) {
                $result = $this->db->query("SELECT * FROM Nodes");
            } else {
                $result = $this->db->query("SELECT Nodes.* FROM Nodes
                                            JOIN Servers ON Nodes.ID = Servers.IDNode
                                            WHERE Servers.IDOwner = $userId GROUP BY Nodes.ID");
            }

            $nodes = [];
            while ($row = $result->fetch_assoc()) {
                $nodes[] = $row;
            }

            return $nodes;
        }*/

        public function banUser($userId) {
            // Check if the user is an admin before banning
            if ($this->isAdmin($userId)) {
                return ['success' => false, 'error' => 'Cannot ban an admin user.'];
            }

            $stmt = $this->db->prepare("DELETE FROM Users WHERE ID = ?");
            $stmt->bind_param("i", $userId);

            if ($stmt->execute()) {
                return ['success' => true];
            } else {
                return ['success' => false, 'error' => $stmt->error];
            }
        }

        public function resetPassword($userId) {
            // Generate a new password (you may want to implement a secure password generation function)
            $newPassword = 'new_secure_password';

            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

            $stmt = $this->db->prepare("UPDATE Users SET Pass = ? WHERE ID = ?");
            $stmt->bind_param("si", $hashedPassword, $userId);

            if ($stmt->execute()) {
                return ['success' => true, 'newPassword' => $newPassword];
            } else {
                return ['success' => false, 'error' => $stmt->error];
            }
        }

        public function logout(){
            $_SESSION['logged'] = false; // Set the session variable to false upon logout
        }

        public function generateUserList(){
            $output = '';

            $result = mysqli_query($this->db, "SELECT * FROM Users;");

            while ($row = mysqli_fetch_assoc($result)){
                $ID = $row['ID'];
                $Name = $row['Name'];
                $LocationID = $row['LocationID'];

                $result2 = mysqli_query($this->db, "SELECT Name, Deafult FROM UserLocation WHERE ID = '$LocationID'");
                $row2 = mysqli_fetch_assoc($result2);
                $Location = $row2['Name'];
                $Deafult = $row2['Deafult'];

                if ($Deafult == 1) $Color = "success";
                else $Color = "danger";

                $output .= '
                    <li class="list-group-item list-group-item-dark d-flex justify-content-between align-items-center">
                        <img src="https://res.cloudinary.com/dxfq3iotg/image/upload/v1573035860/Pra/Hadie-profile-pic-circle-1.png" alt="' . $ID . '" class="rounded-circle img-fluid " width="35" height="35">
                        ' . $Name . '
                        <span class="badge text-bg-' . $Color . '">' . $Location . '</span>
                    </li>
                ';
            }

            return $output;
        }
    }


    /*
    // Example usage:
    $userManager = new UserManager("your_host", "your_username", "your_password", "your_database");

    // Login example
    $result = $userManager->login("example_login", "example_password");
    if ($result['success']) {
        echo "Login successful. User ID: " . $result['userId'] . ", isAdmin: " . ($result['isAdmin'] ? 'true' : 'false');
    } else {
        echo "Login failed.";
    }

    // Registration example
    $result = $userManager->register("John Doe", "john_doe", "secure_password");
    if ($result['success']) {
        echo "Registration successful.";
    } else {
        echo "Registration failed. Error: " . $result['error'];
    }*/

    // Example usage:
    // $Connect is assumed to be the database connection object.
    // $userList = new UserList($host, $username, $password, $database);
    // $userListHTML = $userList->generateUserList();
    // echo $userListHTML;

?>
