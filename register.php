<?php
include_once 'includes/register.inc.php';
include_once 'includes/functions.php';
?>
<script type="text/JavaScript" src="js/sha512.js"></script> 
<script type="text/JavaScript" src="js/forms.js"></script>
<!-- Registration form to be output if the POST variables are not
set or if the registration script caused an error. -->
<?php
if (!empty($error_msg)) {
    echo '<div class="alert alert-warning" role="alert">'.$error_msg.'</div>';
}
?>
<h1>Register with us</h1>
<ul>
    <li>Usernames may contain only digits, upper and lower case letters and underscores</li>
    <li>Emails must have a valid email format</li>
    <li>Passwords must be at least 6 characters long</li>
    <li>Passwords must contain
        <ul>
            <li>At least one uppercase letter (A..Z)</li>
            <li>At least one lower case letter (a..z)</li>
            <li>At least one number (0..9)</li>
        </ul>
    </li>
    <li>Your password and confirmation must match exactly</li>
    <li>Your email must be on file with the <a href="http://thetataufiu.com" target="_blank">Omega Gamma website</a></li>
</ul>
<form action="<?php echo esc_url($_SERVER['PHP_SELF']."?p=register"); ?>" 
        method="post" 
        name="registration_form">
    <div class="form-group col-md-5"><label for="username">Username</label><input type='text' name='username' id='username' class="form-control" /></div>
    <div class="form-group col-md-5"><label for="email">Email</label><input type="text" name="email" id="email" class="form-control" /></div>
    <div class="form-group col-md-5"><label for="email">Password</label><input type="password" name="password" id="password" class="form-control" /></div>
    <div class="form-group col-md-5"><label for="email">Confirm Password</label><input type="password" name="confirmpwd" id="confirmpwd" class="form-control" /></div>
    <div class="col-md-10">
        <input type="button" value="Register" onclick="return regformhash(this.form, this.form.username, this.form.email, this.form.password, this.form.confirmpwd);" class="btn btn-success"              />
    </div> 
</form>