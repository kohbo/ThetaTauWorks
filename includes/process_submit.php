<?php
include_once 'includes/db_connect.php';
include_once 'includes/PHPMailer/PHPMailerAutoload.php';

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
        
        $bro_sql = "SELECT roll, fname, lname FROM memberidView WHERE user_id = ?";
        
        $bro_stmt = $mysqli -> prepare($bro_sql);
        $bro_stmt -> bind_param("i", $userid);
        $bro_stmt -> execute();
        $bro_stmt -> store_result();
        $bro_stmt -> bind_result($roll, $fname, $lname);
        $bro_stmt -> fetch();
        
        $stmt -> bind_param('ssssssi',$_POST['company'],$_POST['position'],$_POST['type'],$_POST['contact'],$_POST['information'],$_POST['location'],$roll);
        if($stmt -> execute()){
            $success = true;
        }
        $stmt -> close();
        
        $bro_stmt -> close();
        
        if($_POST['mail'] == 'on'){
            $mail = new PHPMailer;
            
            $mail -> isSMTP();
            
//             $mail -> SMTPDebug = 2;
//             $mail->Debugoutput = 'html';
            
            $mail -> Host = 'smtp.gmail.com';
            $mail -> SMTPAuth =  true;
            $mail -> Username = 'kohbosan@gmail.com';
            $mail -> Password = 'aftsiglopas';
            $mail -> SMTPSecure = 'tls';
            $mail -> Port = 587;
            
            $mail -> From = 'thetatauworksbot@thetatauworks.com';
            $mail -> FromName = 'TT Works Bot';
            $mail -> addAddress('kohbosan@gmail.com');
            
            $mail -> Subject = 'New Opportunity Posting';
            $mail -> Body = 'A new posting has been added to Theta Tau Works by '. $fname .' '. $lname;
            
            if(!$mail -> send()){
                $error_msg .= "Mailer Error: " . $mail -> ErrorInfo;
            }
        }
        
    } else {
        $error_msg .= "Database connection error.";
    }
}