<?php

    class WeatherStation{
        private $db;

        public function __construct($host, $username, $password, $database){
            $this->db = new mysqli($host, $username, $password, $database);

            if($this->db->connect_error){
                die("[WeatherStation] Connection failed: " . $this->db->connect_error);
            }
        }

        public function getWeatherCondition($nodeName){
            $humidity = $this->getWeather($nodeName, "HUMIDITY_DHT");
            $temperature1 = $this->getWeather($nodeName, "TEMP_DHT");
            $pressure = $this->getWeather($nodeName, "PASCAL_BMP");
            $temperature2 = $this->getWeather($nodeName, "TEMP_BMP");
            $airQuality = $this->getWeather($nodeName, "CPPM_MQ135");
            $temperature3 = $this->getWeather($nodeName, "TEMP_DALLAS");
            $brightness = $this->getWeather($nodeName, "LDR");
            $rain = $this->getWeather($nodeName, "RAIN");

            $weatherCondition = $this->calculateWeatherCondition($brightness[0]['Value'], $humidity[0]['Value'], $rain[0]['Value'], $airQuality[0]['Value'], $pressure[0]['Value'], $temperature1[0]['Value'], $temperature2[0]['Value'], $temperature3[0]['Value']);
            return $weatherCondition;
        }

        public function getWeather($stationName, $sensor){
            // Pobierz dane z bazy danych
            $query = "SELECT Value FROM Devices WHERE NodeName = ? AND Name = ?;";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("ss", $stationName, $sensor);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // Przetwarzaj dane
                $data = $result->fetch_all(MYSQLI_ASSOC);
                //$weather = $this->calculateWeather($data);
                return $data;
            } else {
                //return "Brak danych dla stacji meteo o nazwie: $stationName";
                return 0;
            }
        }

        private function calculateWeatherCondition($brightness, $humidity, $rain, $airQuality, $pressure, $temperature1, $temperature2, $temperature3){
            $averageTemperature = ($temperature1 + $temperature2 + $temperature3) / 3;
            $windSpeed = 0;
        
            if ($brightness > 80 && $humidity < 60 && $rain == 0 && $airQuality > 70 && $pressure > 1000 && $averageTemperature > 20) {
                return "Słonecznie";
            } elseif ($brightness < 50 && $humidity > 80 && $rain > 0 && $airQuality < 50 && $pressure < 1000 && $averageTemperature < 10) {
                return "Deszczowo";
            } elseif ($brightness < 30 && $humidity < 50 && $rain == 0 && $airQuality > 60 && $pressure > 1000 && $averageTemperature > 25) {
                return "Gorąco i bezchmurnie";
            } elseif ($rain > 0 && $humidity > 70) {
                return "Deszcz z wysoką wilgotnością";
            } elseif ($airQuality < 0.030) {
                return "Zła jakość powietrza";
            } elseif ($pressure < 980) {
                return "Niska ciśnienie atmosferyczne";
            } elseif ($averageTemperature < 0) {
                return "Mroźno";
            } elseif ($humidity > 90 && $averageTemperature > 15) {
                return "Mgła";
            } elseif ($rain > 0 && $temperature1 > 25) {
                return "Burze";
            } elseif ($brightness < 20 && $humidity > 60 && $rain == 0 && $airQuality > 50 && $pressure > 1000 && $averageTemperature > 15) {
                return "Pochmurno";
            } elseif ($averageTemperature > 25) {
                return "Upał";
            } elseif ($rain > 0 && $temperature1 < 10) {
                return "Deszcz ze śniegiem";
            } elseif ($windSpeed > 30) {
                return "Wietrznie";
            } else {
                return "Inne warunki pogodowe";
            }
        }
    }

    // Przykładowe użycie klasy
    //$weatherStation = new WeatherStation($host, $username, $password, $database);
    //$stationName = "NazwaTwojejStacjiMeteo";
    //$weather = $weatherStation->getWeather($stationName);

    //echo "Aktualna pogoda dla stacji meteo $stationName: $weather";

?>
