<?php
    session_start();

	require_once("Includes/Config.php");
	
	//Definiowanie zmiennych sesyjnych
    define('SESSION_COOKIE',$SESSION_COOKIE);
    define('SESSION_ID_LENGHT',$SESSION_ID_LENGHT);
    define('SESSION_COOKIE_EXPIRE',$SESSION_COOKIE_EXPIRE);

	//if(!isset($_SESSION['logged'])) header('Location: login.php');

	require_once("Includes/UserManager.php");
	require_once("Includes/LocationManager.php");
	require_once("Includes/Weather.php");
	
	
	//$UserID = $_SESSION['userId'];
	//$UserName = $_SESSION['User'];

	// Get user info
	$userManager = new UserManager($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
	$userListHTML = $userManager->generateUserList();
    //$userInfo = $userManager->getUserInfo($UserID);
	//$isAdmin = $userInfo['isAdmin'];
	//$UserName = $userInfo['Name'];

	$isAdmin = True;
	$UserName = "MrBartek21";
	$UserID = 1;


    

	$LocationManager = new LocationManager($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
    $generateButtons = $LocationManager->generateButtons();
	$generateModals = $LocationManager->generateModals();

	$weatherStation = new WeatherStation($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
    $stationName = "Meteo";
	$weather = $weatherStation->getWeatherCondition($stationName);

	
	
	//All information
	/*$connect = @new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
    $connect->set_charset("utf-8");
    $result = mysqli_query($connect, "SELECT * FROM information WHERE UserID = '$UserID'");
    $row = $result->fetch_assoc();
	
    $ProjectName = $row['ProjectName'];
    $ProjectID = $row['ProjectID'];
    $ProjectNumber = $row['ProjectNumber'];
    $ServiceUserName = $row['ServiceUserName'];*/
	
?>
<!DOCTYPE HTML>
<html lang="pl">
    <head>
		<link rel="apple-touch-icon" sizes="57x57" href="Graphic/Favicon/apple-icon-57x57.png">
        <link rel="apple-touch-icon" sizes="60x60" href="Graphic/Favicon/apple-icon-60x60.png">
        <link rel="apple-touch-icon" sizes="72x72" href="Graphic/Favicon/apple-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="76x76" href="Graphic/Favicon/apple-icon-76x76.png">
        <link rel="apple-touch-icon" sizes="114x114" href="Graphic/Favicon/apple-icon-114x114.png">
        <link rel="apple-touch-icon" sizes="120x120" href="Graphic/Favicon/apple-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="144x144" href="Graphic/Favicon/apple-icon-144x144.png">
        <link rel="apple-touch-icon" sizes="152x152" href="Graphic/Favicon/apple-icon-152x152.png">
        <link rel="apple-touch-icon" sizes="180x180" href="Graphic/Favicon/apple-icon-180x180.png">
        <link rel="icon" type="image/png" sizes="192x192"  href="Graphic/Favicon/android-icon-192x192.png">
        <link rel="icon" type="image/png" sizes="32x32" href="Graphic/Favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="96x96" href="Graphic/Favicon/favicon-96x96.png">
        <link rel="icon" type="image/png" sizes="16x16" href="Graphic/Favicon/favicon-16x16.png">
		
		<meta name="robots" content="index, follow">
		<meta name="msapplication-TileImage" content="Graphic/Favicon/ms-icon-144x144.png"/>
		<meta name="msapplication-TileColor" content="#ffffff"/>
		<meta name="theme-color" content="#b93731"/>
        <link rel="manifest" href="Graphic/Favicon/manifest.json">
        
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
		<meta name="author" content="<?php echo $AuthorHead;?>"/>
        <meta name="keywords" content="<?php echo $KeywordsHead;?>" />
        <meta name="description" content="<?php echo $DescriptionHead;?>"/>


		<link rel="stylesheet" href="Vendor/BootStrap/latest/css/bootstrap.min.css">
		
		<link href="Vendor/FontAwesome/css/fontawesome.css" rel="stylesheet">
		<link href="Vendor/FontAwesome/css/brands.css" rel="stylesheet">
		<link href="Vendor/FontAwesome/css/solid.css" rel="stylesheet">

		<link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
        <link href="CSS/style.css" rel="stylesheet">
		<link href="CSS/sidebar.css" rel="stylesheet">
        
        <title>Strona główna - <?php echo $TitleHead;?></title>
		<title><?php echo $Lang_MainPage.' - '.$TitleHead;?></title>
    </head>
    <body>
		<?php echo $generateModals;?>
		<div class="wrapper">
			<nav id="sidebar" class="bg-darkblue active">
				<div class="sidebar-header">
					<h3>
					<IMG src="Graphic/LogoMenu2.png" class="d-inline-block mr-sm-1 align-bottom" width="40" height="35" alt="">
					<?php echo $TitleHead;?></h3>
					<strong><?php echo $TitleHead2;?></strong>
				</div>
				<ul class="list-unstyled components">
					<?php Menu('index', 1);?>
				</ul>
				<ul class="list-unstyled components">
					<?php Menu('index', 2, $isAdmin);?>
				</ul>
			</nav>

			<div id="content">
				<nav class="navbar navbar-expand-lg navbar-dark bg-blue">
					<div class="container-fluid">
						<button type="button" id="sidebarCollapse" class="btn btn-info">
							<i class="fas fa-align-left"></i>
							<span>Przełącz menu</span>
						</button>
						
						<div class="float-right">
							<a href="profil.php" class="btn btn-warning mr-2"><?php echo $UserName;?></a>
							<IMG src="Graphic/Avatars/<?php echo $UserID;?>.png" class="d-inline-block mr-sm-1 align-bottom rounded-circle" width="40" height="40" alt="">
						</div>
					</div>
				</nav>

				<div class="row" style="margin-right: 0px; margin-left: 0px;">
					
					<div class="col-md-4">
						<div class="card text-dark bg-lightgrey">
							<div class="card-block">
								<h3 class="card-title text-left"><i class="fas fa-info-circle"></i> <span id="infoDiv">N/A</span></h3>
								<div id="infoDivAjax">
									<div class="row text-center" style="display:none;">
										<div class="col-md-6">
											<h3><?php echo $weather;?></h3>
										</div>
										<div class="col-md-6">
											<h1><p><B>2.1 °C</B></p></h1>
										</div>
									</div>
									<div class="row" style="display:none;">
										<div class="col-md-6">
											<p><i class="fas fa-tint"></i> <B>94%</B></p>
											<p><i class="fas fa-cloud"></i> <B>1001hPa</B></p>
										</div>
										<div class="col-md-6 text-end">
											<p><i class="fas fa-wind"></i> <B>0.000327ppm</B></p>
											<p><i class="fas fa-thermometer-empty"></i> <B>5.1 °C</B></p>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="col-sm-4">
						<div class="card text-dark bg-lightgrey">
							<div class="card-block">
								<h3 class="card-title text-left"><i class="far fa-chart-bar"></i> Wykresy</h3>

								<div id="chartDivLoading" class="d-flex justify-content-center">
									<div class="spinner-border" role="status">
										<span class="visually-hidden">Loading...</span>
									</div>
								</div>

								<div id="chartDiv" style="display: none;"></div>

							</div>
						</div>
					</div>

					<div class="col-sm-4">
						<div class="card text-dark bg-lightgrey">
							<div class="card-block">
								<h3 class="card-title text-left"><i class="far fa-users"></i> Domownicy</B></h3>
								<?php echo $userListHTML;?>
							</div>
						</div>
					</div>
				</div>
				
				<div class="row" style="margin-right: 0px; margin-left: 0px;">
					<div class="col-sm-4">
						<div class="card text-dark bg-lightgrey">
							<div class="card-block">
								<h3 class="card-title text-left"><i class="fas fa-location-arrow"></i> Pomieszczenia</h3>
								<?php echo $generateButtons;?>
							</div>
						</div>
					</div>

					<div class="col-sm-4">
						<div class="card text-dark bg-lightgrey">
							<div class="card-block">
								<h3 class="card-title text-left"><i class="far fa-chart-bar"></i> Stan usług</h3>
								<?php echo $ServiceUserStatus;?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<footer class="footer navbar-fixed-bottom fixed-bottom navbar-dark indigo container-footer">
			<div class="container">
				<p class="m-0 text-center text-white">Copyright &copy; <a href="<?php echo $Link_Index;?>"><?php echo $TitleHead;?></a> <?php echo date('Y');?></p>
			</div>
        </footer>


		<script src="Vendor/JQuerry/jquery.slim.js" crossorigin="anonymous"></script>
        <script src="Vendor/Ajax/popper.js/popper.min.js" crossorigin="anonymous"></script>
        <script src="Vendor/BootStrap/latest/js/bootstrap.min.js" crossorigin="anonymous"></script>
		<script src="Vendor/Ajax/jquery.js/jquery.min.js"></script>

		<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

		<script>
            $(document).ready(function(){

				var options = {
					series: [{
						name: "kW",
						data: [0,0,0,0,0,0,0,0,0]
					}],
					chart: {
						type: 'area',
						//height: 350,
						zoom: {
							enabled: false
						}
					},
					dataLabels: {
						enabled: false
					},
					stroke: {
						curve: 'straight'
					},
					title: {
						text: 'Zużycie prądu',
						align: 'left'
					},
					animations: {
						initialAnimation: {
							enabled: false
						}
					},
					//labels: series.monthDataSeries1.dates,
					xaxis: {
						type: 'datetime',
						labels: {
							format: 'yyyy-MM-dd HH:mm'
						},
					},
					yaxis: {
						opposite: false
					},
					legend: {
						horizontalAlign: 'left'
					}
				}

				document.getElementById("chartDivLoading").style.setProperty("display", "none", "important");
				document.getElementById("chartDiv").style.display = "block";

				var chart = new ApexCharts(document.querySelector("#chartDiv"), options);
				chart.render();

				


				function ElementUpdate(type){
					if(type == "infoDivAjax"){
						$.get('JS/Ajax.php', {type: type}, function(response){
							$("#infoDivAjax").html(response);
						});
					}else if(type == "Chart1"){
						$.getJSON('JS/Ajax.php', {type: type}, function(response){
							chart.updateSeries([{
								data: response
							}])
						});
					}
				}
			
				async function fnAsync(){
					await ElementUpdate("infoDivAjax");
					await ElementUpdate("Chart1");
				}
				fnAsync();

				
				setInterval(function(){
					ElementUpdate("infoDivAjax");
					//ElementUpdate("Chart1");
                },1000);

				setInterval(function(){
					//ElementUpdate("infoDivAjax");
					ElementUpdate("Chart1");
                },10000);
            });
        </script>

		<script type="text/javascript">
			$(document).ready(function(){
				$('#sidebarCollapse').on('click', function(){
					$('#sidebar').toggleClass('active');
				});
			});
		</script>
		<script async src='JS/general.js'></script>
	</body>
</html>