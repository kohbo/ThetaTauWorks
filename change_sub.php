<?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';

sec_session_start();

if(isset($_POST['checked'])){
    $_POST['checked'] = ($_POST['checked'] == 'true') ? 1 : 0;
    $stmt = $mysqli -> prepare("UPDATE members SET email_notification = ? WHERE id = ?");
    $stmt -> bind_param("ii", $_POST['checked'], $_SESSION['user_id']);
    if($stmt -> execute()){
        echo "Changes Submitted Successfully";
    } else {
        echo "An error has occured.";
    }
}
?>
