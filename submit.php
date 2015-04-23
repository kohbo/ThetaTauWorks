<?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
include_once 'includes/process_submit.php';
// sec_session_start();

echo "<ol class='breadcrumb'>
        <li><a href='index.php'>Home</a></li>
        <li>Submit</li>
      </ol>
    ";

if (!empty($error_msg)) {
    echo '<div class="alert alert-warning" role="alert">'.$error_msg.'</div>';
}
if ($success == true) {
    echo '<div class="alert alert-success" role="alert">Opportunity successfully submitted.</div>';
    
    echo '<script>
            function redirect(){ 
                window.location.replace("?p=browse");
            };
            if(window.document.readyState == "interactive"){
                alert("Hello");
            }     
        </script>'; 
}
?>

<?php if(login_check($mysqli) == true) : ?>

<div class="col-md-7 col-md-offset-3">
<form name="new_opp_form" method="POST" action="?p=submit">
    <div class="form-group">
        <label for="company">Company</label>
        <input type="text" id="company" name="company" placeholder="Company Name" class="form-control" />
    </div>
    <div class="form-group">
        <label for="position">Position</label>
        <input type="text" id="position" name="position" placeholder="Position Description" class="form-control" />
    </div>
    <div class="form-group">
        <label for="type">Opportunity Type</label>
        <select name="type" id="type" class="form-control">
            <option value="Job">Job</option>
            <option value="Internship">Internship</option>
            <option value="Volunteer">Volunteer</option>
            <option value="Other">Other</option>
        </select>
    </div>
    <div class="form-group">
        <label for="major_one">Relevant Majors</label>
        <div class="form-group"><input type="text" id="major_one" name="major_one" placeholder="First Major" class="form-control" disabled/></div>
        <div class="form-group"><input type="text" id="major_two" name="major_two" placeholder="Second Major" class="form-control" disabled/></div>
        <div class="form-group"><input type="text" id="major_three" name="major_three" placeholder="Third Major" class="form-control" disabled/></div>
    </div>
    <div class="form-group">
        <label for="contact">Contact</label>
        <input type="text" id="contact" name="contact" placeholder="URL or E-mail" class="form-control" />
    </div>
    <div class="form-group">
        <label for="information">Additional Information</label>
        <textarea id="information" name="information" placeholder="E-mails, other contacts, requirements, deadlines, etc." class="form-control" rows="5" ></textarea>
    </div>
    <div class="form-group">
        <label for="location">Location</label>
        <input type="text" id="location" name="location" placeholder="Opportunity Location" class="form-control" />
    </div>
    <input type="button" value="Submit" class="btn btn-success" onclick="return validateform(this.form)" />
</form>
</div>
<script src="js/submit_form.js"></script>

<?php else : ?>

    <div class="jumbotron">
	<p>You must log in to see this page.</p>
	<p>Question/Suggestions? Contact me at <a href="mailto:kohdingn@kohding.net" target="_blank">kohdingn@kohding.net</a></p>
    </div>

<?php endif; ?>