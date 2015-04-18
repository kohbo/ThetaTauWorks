<?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';

sec_session_start();
?>

<!DOCTYPE html>

<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Theta Tau Works</title>

<!-- BootStrap CDN Files -->
<link rel="stylesheet"
	href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" />
<link rel="stylesheet"
	href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css">

<!-- Custom CSS -->
<link href='http://fonts.googleapis.com/css?family=Righteous'
	rel='stylesheet' type='text/css' />
<link href="style/main.css" rel="stylesheet" type="text/css" />

</head>

<body>
	<nav class="navbar navbar-inverse navbar-fixed-top">
		<!-- New NavBar -->
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed"
					data-toggle="collapse" data-target="#menu-data">
					<span class="sr-only">Toggle navigation</span> <span
						class="icon-bar"></span> <span class="icon-bar"></span> <span
						class="icon-bar"></span>
				</button>
				<a href="http://kohding.net/thetatauworks/" class="navbar-brand">Theta
					Tau Works</a>
			</div>

			<div class="collapse navbar-collapse" id="menu-data">
				<ul class="nav navbar-nav">
					<li><a href="?p=browse">Browse</a></li>
					<li><a href="?p=submit">Submit</a></li>
					<li><a href="#">Stats</a></li>
				</ul>
			
		
		<!--  
		<a href="http://kohding.net/thetatauworks/" class="navbar-brand">Theta
			Tau Works</a>
		<ul class="nav navbar-nav">
			<li><a href="?p=browse">Browse</a></li>
			<li><a href="?p=submit">Submit</a></li>
			<li><a href="#">Stats</a></li>
		</ul>
		-->

			<?php if(login_check($mysqli) == true) : ?>
			<div class="navbar-right">
			<?php if(!isMobile()):?>
			<p class="navbar-text">Welcome, <?php echo $_SESSION['username']; ?></p>
			<?php endif;?>
			<ul class="nav navbar-nav">
			  <li class="dropdown">
			     <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Settings <span class="caret"></span></a>
                 <ul class="dropdown-menu" role="menu">
                    <li><a href="logout.php">Logout</a></li>
                 </ul>
			  </li>   
			</ul>
			
			<!-- <div class="btn-group navbar-btn">
				<button type="button" class="dropdown-toggle btn btn-primary"
					data-toggle="dropdown" id="settings">Settings</button>
				<ul class="dropdown-menu" role="menu">
					<li><a href="logout.php">Logout</a></li>
				</ul>
			</div> -->
		</div>
		</div>
		</div>
			
			<?php else : ?>
			
			<form action="process_login.php" method="post" name="login_form"
			class="navbar-form navbar-right">
			<input type="text" name="email" class="form-control"
				placeholder="Email" /> <input type="password" name="password"
				id="password" class="form-control" placeholder="Password" />
			<button type="submit" class="btn btn-default"
				onclick="formhash(this.form, this.form.password);">Login</button>
			<button type="button" class="btn btn-success"
				onclick="location.href='./?p=register'" style="margin-right: 5px;">Register</button>
		</form>
			<?php endif; ?>
		</nav>
	<div class="container" style="margin-top: 70px">
		<div class="row" id="page_content">
		  <?php if(isset($_GET['error'])){ include "error.php";  }?>
		  <?php
    
    if (isset($_GET['p'])) {
        include $_GET['p'] . ".php";
    } else {
        include "index_landing.php";
    }
    ?>
		</div>
	</div>

</body>

<!-- Script Loading -->
<script type="text/JavaScript" src="js/sha512.js"></script>
<script type="text/JavaScript" src="js/forms.js"></script>

<!-- JQuery CDN -->
<script src="//code.jquery.com/jquery-1.11.2.min.js"></script>

<!-- BootStrap CDN -->
<script
	src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>

<!-- Scripts -->
<?php include_once 'includes/analytics.php'; ?>
</html>