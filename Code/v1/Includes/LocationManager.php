<?php

    class LocationManager{
        private $connect;

        public function __construct($host, $username, $password, $database){
            $this->db = new mysqli($host, $username, $password, $database);

            if($this->db->connect_error){
                die("[LocationManager] Connection failed: " . $this->db->connect_error);
            }
        }

        public function generateButtons(){
            $output = '';
            $result = mysqli_query($this->db, "SELECT * FROM Locations");
            $resultCount = $result->num_rows;

            if($resultCount >= 1){
                while($row = mysqli_fetch_array($result)){
                    $ID = $row['ID'];
                    $Name = $row['Name'];
                    $Description = $row['Description'];

                    $output .= '
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal'.$ID.'">'.$Name.'</button>
                    ';
                }
            }

            return $output;
        }

        public function generateModals(){
            $output = '';
            $result = mysqli_query($this->db, "SELECT * FROM Locations");
            $resultCount = $result->num_rows;

            if($resultCount >= 1){
                while($row = mysqli_fetch_array($result)){
                    $ID = $row['ID'];
                    $Name = $row['Name'];
                    $Description = $row['Description'];

                    $output .= '
                        <div class="modal" tabindex="-1" id="modal'.$ID.'">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">'.$Name.'</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>'.$Name.'</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-primary">Save changes</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    ';
                }
            }

            return $output;
        }
    }

    // Example usage
    //$locationAccordion = new LocationAccordion($host, $username, $password, $database);
    //$locationAccordion->generateAccordion();
    //echo $locationAccordion;

?>
