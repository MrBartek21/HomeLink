<?php

    class ServerManager{
        private $db;

        public function __construct($db){
            $this->db = $db;
        }

        public function getNodes($IDLocation = null){
            if($IDLocation !== null){
                // Display nodes for a specific location
                $query = $this->db->prepare("SELECT * FROM Nodes WHERE IDLocation = ?");
                $query->bind_param("i", $IDLocation);
            }else{
                // Display all nodes
                $query = $this->db->prepare("SELECT * FROM Nodes");
            }

            $query->execute();
            $result = $query->get_result();
            $nodes = $result->fetch_all(MYSQLI_ASSOC);

            return $nodes;
        }

        public function displayNodes($nodes){
            $Locations = $this->getServerLocation();
            $options = '';
            foreach($Locations as $Location){
                $options .= '<option value="'.$Location["ID"].'">'.$Location["Name"].'</option>';
            }

            echo '
                <table class="table">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Description</th>
                            <th scope="col">IP</th>
                            <th scope="col">Port</th>
                            <th scope="col">IDLocation</th>
                            <th scope="col">MaxCPU</th>
                            <th scope="col">MaxMemory</th>
                            <th scope="col">MaxDrive</th>
                            <th scope="col">MaxServers</th>
                            <th scope="col">Maintenance</th>
                            <th scope="col"></th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>

                    <form id="form0" action="" method="POST">
                        <th scope="row">
                            <input type="hidden" class="form-control" id="ID0" name="ID0">
                        </th>
                        <td>
                            <div class="form-group">
                                <input type="text" class="form-control" id="Name0" name="Name0" placeholder="Name">
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <input type="text" class="form-control" id="Description0" name="Description0" placeholder="Description">
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <input type="text" class="form-control" id="IP0" name="IP0" placeholder="IP">
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <input type="number" class="form-control" id="Port0" name="Port0" placeholder="Port" min="25500" max="25600" value="25565">
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <select class="custom-select" id="IDLocation0" name="IDLocation0">
                                    '.$options.'
                                </select>
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <input type="number" class="form-control" id="MaxCPU0" name="MaxCPU0" placeholder="MaxCPU" min="1" max="4" value="1">
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <input type="number" class="form-control" id="MaxMemory0" name="MaxMemory0" placeholder="MaxMemory" min="512" max="4096" value="512">
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <input type="number" class="form-control" id="MaxDrive0" name="MaxDrive0" placeholder="MaxDrive" min="1024" max="10240" value="1024">
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <input type="number" class="form-control" id="MaxServers0" name="MaxServers0" placeholder="MaxServers" min="1" max="10" value="1">
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <input type="checkbox" class="form-control" id="Maintenance0" name="Maintenance0" placeholder="Maintenance">
                            </div>
                        </td>
                        <td><button type="submit" class="btn btn-success">Add</button></td>
                    </form>
                ';
        
            foreach($nodes as $node){
                $Locations = $this->getServerLocation();
                $options = '';
                foreach($Locations as $Location){
                    if($node["IDLocation"] == $Location["ID"]) $options .= '<option value="'.$Location["ID"].'" selected>'.$Location["Name"].'</option>';
                    else $options .= '<option value="'.$Location["ID"].'">'.$Location["Name"].'</option>';
                }


                echo '
                <tr>
                    <form id="form0" action="" method="GET">
                        <th scope="row">'.$node["ID"].'
                            <input type="hidden" class="form-control" id="ID" name="ID" value="'.$node["ID"].'">
                        </th>
                        <td>
                            <div class="form-group">
                                <input type="text" class="form-control" id="Name" name="Name" placeholder="Name" value="'.$node["Name"].'">
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <input type="text" class="form-control" id="Description" name="Description" placeholder="Description" value="'.$node["Description"].'">
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <input type="text" class="form-control" id="IP" name="IP" placeholder="IP" value="'.$node["IP"].'">
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <input type="number" class="form-control" id="Port" name="Port" placeholder="Port" min="25500" max="25600" value="'.$node["Port"].'">
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <select class="custom-select" id="IDLocation" name="IDLocation">
                                    '.$options.'
                                </select>
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <input type="number" class="form-control" id="MaxCPU" name="MaxCPU" placeholder="MaxCPU" min="1" max="4" value="'.$node["MaxCPU"].'">
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <input type="number" class="form-control" id="MaxMemory" name="MaxMemory" placeholder="MaxMemory" min="512" max="4096" value="'.$node["MaxMemory"].'">
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <input type="number" class="form-control" id="MaxDrive" name="MaxDrive" placeholder="MaxDrive" min="1024" max="10240" value="'.$node["MaxDrive"].'">
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <input type="number" class="form-control" id="MaxServers" name="MaxServers" placeholder="MaxServers" min="1" max="10" value="'.$node["MaxServers"].'">
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <input type="checkbox" class="form-control" id="Maintenance" name="Maintenance" placeholder="Maintenance" value="'.$node["Maintenance"].'">
                            </div>
                        </td>
                        <td><button type="submit" class="btn btn-success">Modify</button></td>
                    </form>
                        <td>
                            <form id="formD'.$node["ID"].'" action="" method="POST">
                                <input type="hidden" class="form-control" id="IDD" name="IDD" value="'.$node["ID"].'">
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                </tr>';
            }
        
            echo '
                    </tbody>
                </table>
                ';
        }

        public function modifyNode($nodeID, $data) {
            $query = $this->db->prepare("UPDATE Nodes SET Name = ?, Description = ?, IP = ?, Port = ?, IDLocation = ?, MaxCPU = ?, MaxMemory = ?, MaxDrive = ?, MaxServers = ? WHERE ID = ?");
            $query->bind_param("sssiiddddi", $data['Name'], $data['Description'], $data['IP'], $data['Port'], $data['IDLocation'], $data['MaxCPU'], $data['MaxMemory'], $data['MaxDrive'], $data['MaxServers'], $nodeID);
        
            return $query->execute();
        }

        public function addNode($data) {
            $query = $this->db->prepare("INSERT INTO Nodes (Name, Description, IP, Port, IDLocation, MaxCPU, MaxMemory, MaxDrive, MaxServers) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $query->bind_param("sssiidddi", $data['Name'], $data['Description'], $data['IP'], $data['Port'], $data['IDLocation'], $data['MaxCPU'], $data['MaxMemory'], $data['MaxDrive'], $data['MaxServers']);
        
            return $query->execute();
        }

        public function deleteNode($nodesID){
            $query = $this->db->prepare("DELETE FROM Nodes WHERE ID = ?");
            $query->bind_param("i", $nodesID);
    
            return $query->execute();
        }

        public function getServerLocation($ID = 0){
            if($ID > 0){
                // Display locations for a specific ID
                $query = $this->db->prepare("SELECT * FROM Locations WHERE ID = ?");
                $query->bind_param("i", $ID);
            }else{
                // Display all locations
                $query = $this->db->prepare("SELECT * FROM Locations");
            }
            //$query = $this->db->prepare("SELECT * FROM Locations");
            $query->execute();
            $result = $query->get_result();
            $locations = $result->fetch_all(MYSQLI_ASSOC);

            return $locations;
        }

        public function displayServerLocation($locations){
            echo '
                <table class="table">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Code</th>
                            <th scope="col">Description</th>
                            <th scope="col"></th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>

                    <form id="form0" action="" method="POST">
                        <th scope="row">
                            <input type="hidden" class="form-control" id="ID0" name="ID">
                        </th>
                        <td>
                            <div class="form-group">
                                <input type="text" class="form-control" id="Name0" name="Name0" placeholder="Name">
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <input type="text" class="form-control" id="Code0" name="Code0" placeholder="Code">
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <input type="text" class="form-control" id="Description0" name="Description0" placeholder="Description">
                            </div>
                        </td>
                        <td><button type="submit" class="btn btn-success">Add</button></td>
                    </form>
                ';
        
            foreach($locations as $location){
                echo '
                <tr>
                    <form id="form'.$location["ID"].'" action="" method="GET">
                        <th scope="row">'.$location["ID"].'
                            <input type="hidden" class="form-control" id="ID" name="ID" value="'.$location["ID"].'">
                        </th>
                        <td>
                            <div class="form-group">
                                <input type="text" class="form-control" id="Name" name="Name" placeholder="Name" value="'.$location["Name"].'">
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <input type="text" class="form-control" id="Code" name="Code" placeholder="Code" value="'.$location["Code"].'">
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <input type="text" class="form-control" id="Description" name="Description" placeholder="Description" value="'.$location["Description"].'">
                            </div>
                        </td>
                        <td><button type="submit" class="btn btn-success">Modify</button></td>
                    </form>
                        <td>
                            <form id="formD'.$location["ID"].'" action="" method="POST">
                                <input type="hidden" class="form-control" id="IDD" name="IDD" value="'.$location["ID"].'">
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    
                </tr>';
            }
        
            echo '
                    </tbody>
                </table>
                ';
        }

        public function modifyServerLocation($locationID, $data){
            $query = $this->db->prepare("UPDATE Locations SET Name = ?, Code = ?, Description = ? WHERE ID = ?");
            $query->bind_param("sssi", $data['Name'], $data['Code'], $data['Description'], $locationID);

            return $query->execute();
        }

        public function addServerLocation($data){
            $query = $this->db->prepare("INSERT INTO Locations (Name, Code, Description) VALUES (?, ?, ?)");
            $query->bind_param("sss", $data['Name'], $data['Code'], $data['Description']);
    
            return $query->execute();
        }

        public function deleteServerLocation($locationID){
            $query = $this->db->prepare("DELETE FROM Locations WHERE ID = ?");
            $query->bind_param("i", $locationID);
    
            return $query->execute();
        }
    }

    // Example usage:
    // $db = new mysqli("your_host", "your_username", "your_password", "your_database");
    // $serverManager = new ServerManager($db);

    // $modifyServerLocation = $serverManager->modifyServerLocation($data['ID'], $data);

    // $ServerLocation = $serverManager->getServerLocation($UserID);
    // $serverManager->displayServerLocation($ServerLocation);

    // Example usage to add a new location:
    // $newLocationData = [
    //     'Name' => 'New Location',
    //     'Code' => 'NL',
    //     'Description' => 'Description of the new location',
    // ];
    // $result = $serverManager->addServerLocation($newLocationData);

    // Example usage to delete a location by ID:
    // $locationIDToDelete = 1; // Replace with the actual location ID
    // $result = $serverManager->deleteServerLocation($locationIDToDelete);

    // Example usage:
    // $newNodeData = [
    //     'Name' => 'New Node',
    //     'Description' => 'Description of the new node',
    //     'IP' => '192.168.1.1',
    //     'Port' => 8080,
    //     'IDLocation' => 1,  // Replace with the actual location ID
    //     'MaxCPU' => 4.0,
    //     'MaxMemory' => 8192,
    //     'MaxDrive' => 500,
    //     'MaxServers' => 10,
    // ];

    // $result = $serverManager->addNode($newNodeData);


?>
