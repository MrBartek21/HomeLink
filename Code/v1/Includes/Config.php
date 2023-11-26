<?php
	ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);


	$DB_HOST = "10.0.0.193";
	$DB_USER = "root";
	$DB_PASS = "Bartek2001";
	$DB_NAME = "intelihaven";

	$ProjectName = "HomeLink";
	$ProjectNameShort = "HL";


	//NotificationManager
	$telegramBotToken = '6935982830:AAGqAuBtSaMYdG-zt5A6cPgMeXG4IoPoO50';
    $chatId = '@snake2';
    $discordWebhookUrl = 'TWÓJ_URL_WEBHOOKA_DISCORD';


	$SESSION_COOKIE = $ProjectName;
	$SESSION_ID_LENGHT = 40;
	$SESSION_COOKIE_EXPIRE = 43200;


	//Head section
	$DescriptionHead = "Najlepsze kursy programowania za darmo. Wejdź i sam sprawdź. MrBartek Mistrzu - Strona główna - MrBartek21.y0.pl";
	$KeywordsHead = "programming, radio, arduino, raspberry, domoticz, myhome, smarthome, youtube";
	$AuthorHead = "MrBartek21";


	$index = $_SERVER['SERVER_NAME'];
    $port = $_SERVER['SERVER_PORT'];
    $index =  "http://".$index.":".$port;
    $index2 =  $index.":".$port;
    

    //Różne
	$TitleHead = $ProjectName;
	$TitleHead2 = $ProjectNameShort;



	function Menu($data, $table, $isAdmin = false){
		$menus = [];
		
		if($table == 1){
			$menus = [
				["index", "fas fa-home", "Strona główna"]
			];
		}elseif($table == 2){
			$menus = [
				["config", "fas fa-cogs", "Config"],
				["admin", "fas fa-microchip", "Admin"],
				["users", "fas fa-user", "Users"],
				["devices", "fas fa-code-branch", "Devices"]
			];
		}
	
		foreach($menus as $menu){
			list($short, $icon, $name) = $menu;
	
			$isActive = ($data == $short) ? 'class="active"' : '';
			$url = $short . '.php';

			if($table==1){
				echo '<li ' . $isActive . '><a href="' . $url . '"><i class="' . $icon . '"></i>' . $name . '</a></li>';
			}elseif(!$isAdmin && in_array($short, ["users", "devices"]) && $table == 2){
				echo '<li ' . $isActive . '><a href="' . $url . '"><i class="' . $icon . '"></i>' . $name . '</a></li>';
			}

			if($isAdmin==true){
				echo '<li ' . $isActive . '><a href="' . $url . '"><i class="' . $icon . '"></i>' . $name . '</a></li>';
			}
		}
	}
	
	
?>