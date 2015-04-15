<?php
include_once 'includes/db_connect.php';

$error_msg = "";

$prep_stmt = "INSERT INTO tbljobs (company, position, type, contact, information, location, brothercontact) VALUES(?,?,?,?,?,?,?)";
$stmt = $mysqli -> prepare($prep_stmt);

//TODO Add handler for contact, company, and position all blank -> do not process, in case of JS override
if(true){
    if($stmt){
        
        $information = filter_var($_POST['information'], FILTER_SANITIZE_URL);
        $contact = filter_var($_POST['contact'], FILTER_SANITIZE_URL);
        
        foreach ($_POST as &$string){
            $string = sanitize_string(trim($string));
            if($string == ''){
                $string = 'Not Provided';
            }
        }
        
        $userid = $_SESSION['user_id'];
        
        $bro_sql = "SELECT roll FROM memberidView WHERE user_id = ?";
        
        $bro_stmt = $mysqli -> prepare($bro_sql);
        $bro_stmt -> bind_param("i", $userid);
        $bro_stmt -> execute();
        $bro_stmt -> store_result();
        $bro_stmt -> bind_result($roll);
        $bro_stmt -> fetch();
        
        $stmt -> bind_param('ssssssi',$_POST['company'],$_POST['position'],$_POST['type'],$_POST['contact'],$_POST['information'],$_POST['location'],$roll);
        if($stmt -> execute()){
            $success = true;
        }
        $stmt -> close();
        
        $bro_stmt -> close();
        
        
    } else {
        $error_msg .= "Database connection error.";
    }
}