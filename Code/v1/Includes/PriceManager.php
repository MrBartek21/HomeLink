<?php

    class PriceManager{
        private $db;

        public function __construct($db){
            $this->db = $db;
        }

        public function addPrice($data){
            $query = "INSERT INTO Price (Name, Type, Value, Price) VALUES (?, ?, ?, ?)";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("ssss", $data['Name'], $data['Type'], $data['Value'], $data['Price']);

            if($stmt->execute()){
                return true;
            }else{
                return false;
            }
        }

        public function modifyPrice($priceID, $data){
            $query = "UPDATE Price SET Name = ?, Type = ?, Value = ?, Price = ? WHERE ID = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("ssssi", $data['Name'], $data['Type'], $data['Value'], $data['Price'], $priceID);
        
            if($stmt->execute()){
                return true;
            }else{
                return false;
            }
        }

        public function deletePrice($id){
            $query = "DELETE FROM Price WHERE ID = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("i", $id);

            if($stmt->execute()){
                return true;
            }else{
                return false;
            }
        }

        public function getAllPrices(){
            $query = "SELECT ID, Name, Type, Value, Price FROM Price";
            $result = $this->db->query($query);

            if($result->num_rows > 0){
                $prices = array();
                while($row = $result->fetch_assoc()){
                    $prices[] = $row;
                }
                return $prices;
            }else{
                return array();
            }
        }

        public function getPriceById($id){
            $query = "SELECT ID, Name, Type, Value, Price FROM Price WHERE ID = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();

            if($result->num_rows > 0){
                return $result->fetch_assoc();
            }else{
                return null;
            }
        }
    }

    // Przykładowe użycie:
    // $db = new mysqli("host", "username", "password", "database");
    // $priceManager = new PriceManager($db);
    //$newPrice = [
        //     'Name' => 'Product A',
        //     'Type' => 'Type A',
        //     'Value' => '1024',
        //     'Price' => '10',
        // ];
    // $priceManager->addPrice($newPrice);
    // $allPrices = $priceManager->getAllPrices();
    // foreach ($allPrices as $price) {
    //     echo "ID: " . $price['ID'] . ", Name: " . $price['Name'] . ", Type: " . $price['Type'] . ", Value: " . $price['Value'] . "<br>";
    // }
    // $priceById = $priceManager->getPriceById(1);
    // echo "Price with ID 1: " . json_encode($priceById);

?>
