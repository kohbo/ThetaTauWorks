<?php require_once "includes/db_connect.php"; ?>
<!DOCTYPE html>
<html>
<head>
<title>Email Verification</title>
<style>
body{
	background-color: black;
	color: white;
}

input {
	border: none;
	padding: 3px 5px;
	margin: 10px 3px;
}
</style>
</head>
<body>

<form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post">
Roll # <input type="text" name = "roll" />
<br />
Email <input type="text" name ="email" />
<br />
<input type ="submit" value="Submit"/>
</form>
<br />
<?php
if(isset($_POST['roll']) and isset($_POST['email'])){
	$roll = $_POST['roll'];
	$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);
	if($roll < 135 and $roll > 0){
	    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
	        $mysqli -> query("UPDATE tblBrothers SET email = '".$email."' WHERE roll = ".$roll." LIMIT 1");
	        $mysqli -> query("INSERT INTO login_attempts (user_id, time) VALUES (".$roll.", '".date("Y/m/d")."')");
	    } else {
	        echo "Invalid e-mail format.";   
	    }		
	} else {
		echo "Invalid roll # or email";
		exit();
	}
	echo "E-mail for roll #".$roll." updated to ".$email.".";

}
?>
</body>


</html>