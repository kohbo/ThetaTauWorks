<?php
include_once 'db_connect.php';
include_once 'psl-config.php';
 
$error_msg = "";
 
if (isset($_POST['username'], $_POST['email'], $_POST['p'])) {
    // Sanitize and validate the data passed in
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Not a valid email
        $error_msg .= '<p class="error">The email address you entered is not valid</p>';
    }
 
    $password = filter_input(INPUT_POST, 'p', FILTER_SANITIZE_STRING);
    if (strlen($password) != 128) {
        // The hashed pwd should be 128 characters long.
        // If it's not, something really odd has happened
        $error_msg .= '<p class="error">Invalid password configuration.</p>';
    }
 
    // Username validity and password validity have been checked client side.
    // This should should be adequate as nobody gains any advantage from
    // breaking these rules.
    //
 
    $prep_stmt = "SELECT id FROM members WHERE email = ? LIMIT 1";
    $stmt = $mysqli->prepare($prep_stmt);
 
   // check existing email  
    if ($stmt) {
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();
 
        if ($stmt->num_rows == 1) {
            // A user with this email address already exists
            $error_msg .= '<p class="error">A user with this email address already exists.</p>';
                        $stmt->close();
        } else {
            $stmt->close();
        }
    } else {
        $error_msg .= '<p class="error">Database error Line 39</p>';
                $stmt->close();
    }
 
    // check existing username
    $prep_stmt = "SELECT id FROM members WHERE username = ? LIMIT 1";
    $stmt = $mysqli->prepare($prep_stmt);
 
    if ($stmt) {
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->store_result();
 
                if ($stmt->num_rows == 1) {
                        // A user with this username already exists
                        $error_msg .= '<p class="error">A user with this username already exists</p>';
                        $stmt->close();
                }
        } else {
                $error_msg .= '<p class="error">Database error line 55</p>';
                $stmt->close();
        }
        
    //check if valid member of omega gamma
    $prep_stmt = "SELECT email FROM tblBrothers where email = ? LIMIT 1";
    $stmt = $mysqli -> prepare($prep_stmt);
    
    if($stmt){
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();
        
        if($stmt ->num_rows == 0){
                $error_msg .= '<p class="error">E-mail not found in brother database.</p>';
                $stmt -> close();
        }
    }
 
    if (empty($error_msg)) {
        // Create a random salt
        $random_salt = hash('sha512', uniqid(bin2hex(openssl_random_pseudo_bytes(4)), true)); // Did not work
        //$random_salt = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));
 
        // Create salted password 
        $password = hash('sha512', $password . $random_salt);

        // Insert the new user into the database 
        $insert_prep = "INSERT INTO members (username, email, passwd, salt) VALUES (?, ?, ?, ?)";
        if($insert_stmt = $mysqli -> prepare($insert_prep)){
            $insert_stmt -> bind_param('ssss', $username, $email, $password, $random_salt);

            // Execute the prepared query.
            if (! $insert_stmt->execute()) {
                header('Location: ./?p=register&error=2');
            } else {
                echo '<div class="alert alert-success" role="alert">Registration Successful</div>';
            }
        }
    }
}

?>