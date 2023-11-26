<?php
    session_start();

	require_once("Includes/Config.php");
	
	//Definiowanie zmiennych sesyjnych
    define('SESSION_COOKIE',$SESSION_COOKIE);
    define('SESSION_ID_LENGHT',$SESSION_ID_LENGHT);
    define('SESSION_COOKIE_EXPIRE',$SESSION_COOKIE_EXPIRE);

	require_once("Includes/UserManager.php");
	require_once("Includes/LocalizationManager.php");
	require_once("Includes/NotificationManager.php");
	
	
	if(isset($_POST['login'])){
		$login = $_POST['login'];
        $pass = $_POST['pass'];

		$userManager = new UserManager($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
		$result = $userManager->login($login, $pass);

		$notificationManager = new NotificationManager($telegramBotToken, $chatId, $discordWebhookUrl);
		$notificationManager->sendTelegramMessage("Użtykownik ".$login." próbuje się zalogować!");

		if($result['success']){
			echo $notificationManager->sendTelegramMessage("Użtykownik ".$login." zalogował się pomyślnie!");

			//$db = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
 			//$localizationManager = new LocalizationManager($db);
 			//$userIP = $_SERVER['REMOTE_ADDR'];
 			//$Lang = $localizationManager->getUserCode($userIP, $result['userId']);

			echo "Login successful. User ID: ".$result['userId'].", Lang: ".$Lang.", isAdmin: ".($result['isAdmin']?'true':'false');

		}else header('Location: login.php');
		
	}
	
	if(isset($_SESSION['logged']) && $_SESSION['logged']==true) header('Location: profil.php');
	
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
		
		<!-- Bootstrap core CSS -->
		<link href="Vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

		<!-- Custom styles for this template -->
		<link href="CSS/style.css" rel="stylesheet">
		<link href="CSS/sidebar.css" rel="stylesheet">


		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
		<link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
        
        <title>Logowanie - <?php echo $TitleHead;?></title>
		<title><?php echo $Lang_MainPage.' - '.$TitleHead;?></title>
    </head>
    <body>
		<div class="wrapper">

			<nav id="sidebar" class="bg-darkblue">
				<div class="sidebar-header">
					<h3>
					<IMG src="Graphic/LogoMenu2.png" class="d-inline-block mr-sm-1 align-bottom" width="40" height="35" alt="">
					<?php echo $TitleHead;?></h3>
					<strong><?php echo $TitleHead2;?></strong>
				</div>
				<ul class="list-unstyled components">
					<?php //Menu('login', 1);?>
				</ul>
				<ul class="list-unstyled components">
					<?php //Menu('login', 2);?>
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
							<a href="login.php" class="btn btn-warning mr-2">Zaloguj się <i class="fas fa-sign-in-alt"></i></a>
						</div>
					</div>
				</nav>
				<div class="row" style="margin-right: 0px; margin-left: 0px;">
					<div class="col-sm-12">
						<div class="card text-dark bg-lightgrey">
							<div class="card-block">
								<h3 class="card-title text-center"><i class="fas fa-sign-in-alt"></i> Logowanie</h3>
								<?php if(isset($_SESSION['errorSingIn'])) echo $_SESSION['errorSingIn'];?>
								<form action="#" method="post" class="form-inline mt-2 mt-md-0">
									<input class="form-control mr-1" style="margin-bottom: 20px; width: 100% !important;" type="text" name="login" id="login" placeholder="Login">
									<input class="form-control mr-1" style="margin-bottom: 20px; width: 100% !important;" type="password" name="pass" placeholder="Hasło"v>
									<button type="submit" class="btn btn-primary" style="width: 100% !important;">Zaloguj się</button>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<footer class="footer navbar-fixed-bottom fixed-bottom navbar-dark indigo container-footer">
			<div class="container">
				<p class="m-0 text-center text-white">Copyright &copy; <a href="<?php echo $Link_Index;?>"><?php echo $TitleHead;?></a> <?php echo date('Y');?></p>
				<p class="m-0 text-center text-white"><a href="privacy"><?php echo $Lang_Privacy;?></a> | <?php chatLink();?></p>
			</div>
        </footer>


		<!-- Bootstrap core JavaScript -->
		<script src="Vendor/jquery/jquery.min.js"></script>
		<script src="Vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
        
		
		<script type="text/javascript">
			$(document).ready(function(){
				$('#sidebarCollapse').on('click', function(){
					$('#sidebar').toggleClass('active');
				});
			});
		</script>
	</body>
</html>