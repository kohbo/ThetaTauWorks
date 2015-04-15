<!DOCTYPE html>

<body>
<script src="js/sha512.js"></script>

<?php if(!isset($_GET['p'])) :?>
<script>
var x = "<?php echo $_GET['pp'];?>";
location.href = "./generate_hashes.php?p=" + hex_sha512(x) + "&pp=" + x;
</script>

<?php endif; ?>

<?php 
$password = $_GET['p'];
$random_salt = '72e72cc2b15c38c5adaa167394eaadbdbd692de222bc42bf9ea96a74cf4c47aa19f3d49837469fbfe14f3c2f71d8fa6e1acbfd0b6a93c2dfa843485cdca4dc15';

if(!isset($random_salt)){
    $random_salt = hash('sha512', uniqid(bin2hex(openssl_random_pseudo_bytes(4)), true));
}
$pass_hash = hash('sha512', $password . $random_salt);

echo "<p>Password: ",$_GET['pp'],"</p>";
echo "<p>Password_JS_Hash: ",$password,"</p>";
echo "<p>Salt: ",$random_salt,"</p>";
echo "<p>Hashed Pass: ",$pass_hash,"</p>";

?>

</body>