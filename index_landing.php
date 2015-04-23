<?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';

?>

<div class="jumbotron">

<?php if(login_check($mysqli) == false) : ?>

	<h1>Hello!</h1>
	<p>This site is a work in progress.</p>
	<p>If you are a brother of Theta Tau Omega Gamma chapter, please register in order to view the opportunities available.</p>
	<p>Question/Suggestions? Contact me at <a href="mailto:kohdingn@kohding.net" target="_blank">kohdingn@kohding.net</a></p>

<?php else : ?>

	<h1>Hello <?php echo $_SESSION['username']; ?>!</h1>
	<div>
	   <h2>Recent Posts<span class="pull-right"><?php echo $mysqli->query("SELECT COUNT(*) FROM tbljobs")->fetch_assoc()['COUNT(*)']; ?> Opportunities Available</span></h2>
	</div>
    <table class="table">
    <tr>
        <th class="hidden-xs">Type</th>
        <th>Posted</th>
    	<th>Company</th>
    	<th>Position</th>
    	<th>Details</th>
    </tr>
    
    <?php
    $result = $mysqli -> query("SELECT jobid, submitted, company, position, type FROM tbljobs ORDER BY submitted desc LIMIT 4");
    
    while(($row = $result -> fetch_assoc()) != false){
    	echo "<tr><td class='hidden-xs'>".label_type($row['type'])."</td><td>".
    	   	substr($row['submitted'],0,10)."</td><td>".
    	    $row['company']."</td><td>".$row['position'].
    	    "</td><td><a href='index.php?p=details&id=".$row['jobid']."'>View Details</a></td></tr>";
    }
    ?>
    
    </table>
	<p>Please click browse in the navbar to view all opportunities available.</p>

<?php endif; ?>

</div>