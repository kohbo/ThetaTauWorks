<?php
switch ($_GET['error']){
    case '1':
        $msg = "An error has occured while logging in.";
        break;
    case '2':
        $msg = "An error has occured while registering. Please contact kohdingn@kohding.net";
        break;
    default:
        $msg = "A general error has occured. Please contact kohdingn@kohding.net";
        break;   
}
?>

<div class="alert alert-danger alert-dismissible" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <strong>Error: </strong><?php echo $msg; ?>
</div>