<?php
    require_once("../Includes/Config.php");
    require_once("../Includes/Weather.php");



    if($_GET['type'] == "infoDivAjax"){
        $weatherStation = new WeatherStation($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
        $stationName = "Meteo";
	    $weather = $weatherStation->getWeatherCondition($stationName);
        $airQuality = $weatherStation->getWeather($stationName, "CPPM_MQ135");
        $humidity = $weatherStation->getWeather($stationName, "HUMIDITY_DHT");
        $temperature2 = $weatherStation->getWeather($stationName, "TEMP_BMP");
        $temperature1 = $weatherStation->getWeather($stationName, "TEMP_DHT");
        $pressure = $weatherStation->getWeather($stationName, "PASCAL_BMP");

        echo '
            <div class="row text-center">
                <div class="col-md-6">
                    <h3>'.$weather.'</h3>
                </div>
                <div class="col-md-6">
                    <h1><p><B>'.$temperature2[0]['Value'].' °C</B></p></h1>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <p><i class="fas fa-tint"></i> <B>'.$humidity[0]['Value'].'%</B></p>
                    <p><i class="fas fa-cloud"></i> <B>'.$pressure[0]['Value'].'hPa</B></p>
                </div>
                <div class="col-md-6 text-end">
                    <p><i class="fas fa-wind"></i> <B>'.$airQuality[0]['Value'].'ppm</B></p>
                    <p><i class="fas fa-thermometer-empty"></i> <B>'.$temperature1[0]['Value'].' °C</B></p>
                </div>
            </div>
        ';

    }elseif($_GET['type'] == "Chart1"){
        $Connect = @new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
        $result = mysqli_query($Connect, "SELECT DATE(Date) AS 'x', MAX(Value) AS 'y' FROM historical_data WHERE Sensor='Temp_DHT' GROUP BY DATE(Date) LIMIT 10;");

        $jsonArray = [];
        while($row=mysqli_fetch_assoc($result)){
            $jsonArray[] = $row;
        }

        $json = json_encode($jsonArray);
        echo $json;
    }


?>