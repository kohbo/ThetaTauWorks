<?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';

echo "<ol class='breadcrumb'>
        <li><a href='index.php'>Home</a></li>
        <li>Browse</li>
      </ol>
    ";
?>

<?php if(login_check($mysqli) == true) : ?>

<table class="table">
<tr>
    <th class="hidden-xs">Type</th>
    <th>Posted</th>
	<th>Company</th>
	<th>Position</th>
	<th>Details</th>
</tr>

<?php
$result = $mysqli -> query("SELECT jobid, submitted, company, position, type, contact, information, location, firstname, lastname FROM tbljobs 
LEFT JOIN tblBrothers
ON brothercontact = ID
ORDER BY submitted desc");

while(($row = $result -> fetch_assoc()) != false){
	echo "<tr><td class='hidden-xs'>".label_type($row['type'])."</td><td>".
	   	substr($row['submitted'],0,10)."</td><td>".
	    $row['company']."</td><td>".$row['position'].
	    "</td><td><a href='./?p=details&id=".$row['jobid']."'>View Details</a></td></tr>";
}
?>

</table>

<?php else : ?>

<p>You must be logged in to see this page. Only brothers of Theta Tau can use this website.</p> 

<?php endif; ?>