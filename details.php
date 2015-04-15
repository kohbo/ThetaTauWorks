<?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';

echo "<ol class='breadcrumb'>
        <li><a href='index.php'>Home</a></li>
        <li><a href='index.php?p=browse'>Browse</a></li>
        <li>Details</li>
      </ol>";



if(login_check($mysqli)) {
    if(isset($_GET['id'])) {
        $id = $_GET['id'];
        $stmt = $mysqli -> prepare("SELECT submitted, company, position, type, contact, information, location, firstname, lastname FROM tbljobs 
            LEFT JOIN tblBrothers
            ON brothercontact = ID
            WHERE jobid = ? 
            ORDER BY submitted desc");
        $stmt -> bind_param("i", $id);
        $stmt -> execute();
        $stmt -> store_result();
        $stmt -> bind_result($submitted,$company,$position,$type,$contact,$information,$location,$firstname,$lastname);
        $stmt -> fetch();
        
        if($stmt -> num_rows == 1){
            echo '
            <table class="table">
                <tr>
                    <td><strong>Submitted</strong><td>
                    <td>'.$submitted.'</td>
                </tr>
                <tr>
                    <td><strong>Company</strong><td>
                    <td>'.$company.'</td>
                </tr>
                <tr>
                    <td><strong>Position</strong><td>
                    <td>'.$position.'</td>
                </tr>
                <tr>
                    <td><strong>Type</strong><td>
                    <td>'.label_type($type).'</td>
                </tr>
                <tr>
                    <td><strong>Contact</strong><td>
                    <td>'.linkify($contact).'</td>
                </tr>
                <tr>
                    <td><strong>Information</strong><td>
                    <td>'.$information.'</td>
                </tr>
                <tr>
                    <td><strong>Location</strong><td>
                    <td>'.$location.'</td>
                </tr>
                <tr>
                    <td><strong>Brother Contact</strong><td>
                    <td>'.$firstname.' '.$lastname.'</td>
                </tr>
            </table>
            ';
        } else {
            echo "Problem with query. No results.";
        }
    } else {
        echo "Invalid id provided.";
    }
} else {
    echo "You must be logged in to see this page.";
}

?>