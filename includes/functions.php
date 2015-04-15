<?php
include_once 'psl-config.php';


function sec_session_start(){
    if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
        // last request was more than 30 minutes ago
        session_unset();     // unset $_SESSION variable for the run-time
        session_destroy();   // destroy session data in storage
    }
	$session_name = 'sec_session_id';
	$secure = SECURE;
	$httponly = true;

	if(ini_set('session.use_only_cookies', 1) === FALSE){
		header("Location: ../error.php?err=Could not initiate a safe session (ini_set)");
		exit();
	}

	$cookie_params = session_get_cookie_params();
	session_set_cookie_params($cookie_params["lifetime"],
		$cookie_params["path"],
		$cookie_params["domain"],
		$secure,
		$httponly);

	session_name($session_name);
	session_start();
	session_regenerate_id(true);
	$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
}

function login($email, $password, $mysqli) {
    // Using prepared statements means that SQL injection is not possible. 
    if ($stmt = $mysqli->prepare("SELECT id, username, passwd, salt, enabled FROM members WHERE email = ? AND enabled = true LIMIT 1")) {
        $stmt->bind_param('s', $email);
        $stmt->execute();    // Execute the prepared query.
        $stmt->store_result();
 
        // get variables from result.
        $stmt->bind_result($user_id, $username, $db_password, $salt, $enabled);
        $stmt->fetch();
 
        // hash the password with the unique salt.
        $password = hash('sha512', $password . $salt);
        if ($stmt->num_rows == 1) {
            // If the user exists we check if the account is locked
            // from too many login attempts 
 
            if (checkbrute($user_id, $mysqli) == true) {
                // Account is locked 
                // Send an email to user saying their account is locked
                return false;
            } else {
                // Check if the password in the database matches
                // the password the user submitted.
                if ($db_password == $password) {
                    // Password is correct!
                    // Get the user-agent string of the user.
                    $user_browser = $_SERVER['HTTP_USER_AGENT'];
                    // XSS protection as we might print this value
                    $user_id = preg_replace("/[^0-9]+/", "", $user_id);
                    $_SESSION['user_id'] = $user_id;
                    // XSS protection as we might print this value
                    $username = preg_replace("/[^a-zA-Z0-9_\\-]+/", 
                                                                "", 
                                                                $username);
                    $_SESSION['username'] = $username;
                    $_SESSION['login_string'] = hash('sha512', 
                              $password . $user_browser);
                    // Login successful.
                    return true;
                } else {
                    // Password is not correct
                    // We record this attempt in the database
                    $now = time();
                    $mysqli->query("INSERT INTO login_attempts(user_id, time)
                                    VALUES ('$user_id', '$now')");
                    return false;
                }
            }
        } else {
            // No user exists.
            return false;
        }
    }
}

function checkbrute($user_id, $mysqli) {
	$now = time();

	$valid_attempts = $now - (2 * 60 * 60);

	if($stmt = $mysqli -> prepare("SELECT time 
		FROM login_attempts 
		WHERE user_id = ?
		AND time > '$valid_attempts'")){
		$stmt -> bind_param('i', $user_id);
		$stmt -> execute();
		$stmt -> store_result();

		if($stmt -> num_rows > 5){
			return true;
		} else {
			return false;
		}
	}
}

function esc_url($url) {
 
    if ('' == $url) {
        return $url;
    }
 
    $url = preg_replace('|[^a-z0-9-~+_.?#=!&;,/:%@$\|*\'()\\x80-\\xff]|i', '', $url);
 
    $strip = array('%0d', '%0a', '%0D', '%0A');
    $url = (string) $url;
 
    $count = 1;
    while ($count) {
        $url = str_replace($strip, '', $url, $count);
    }
 
    $url = str_replace(';//', '://', $url);
 
    $url = htmlentities($url);
 
    $url = str_replace('&amp;', '&#038;', $url);
    $url = str_replace("'", '&#039;', $url);
 
    if ($url[0] !== '/') {
        // We're only interested in relative links from $_SERVER['PHP_SELF']
        return '';
    } else {
        return $url;
    }
}

function login_check($mysqli) {

    // Check if all session variables are set 
    if (isset($_SESSION['user_id'], 
                        $_SESSION['username'], 
                        $_SESSION['login_string'])) {

        $user_id = $_SESSION['user_id'];
        $login_string = $_SESSION['login_string'];
        $username = $_SESSION['username'];
 
        // Get the user-agent string of the user.
        $user_browser = $_SERVER['HTTP_USER_AGENT'];
 
        if ($stmt = $mysqli->prepare("SELECT passwd 
                                      FROM members 
                                      WHERE id = ? LIMIT 1")) {
            // Bind "$user_id" to parameter. 
            $stmt->bind_param('i', $user_id);
            $stmt->execute();   // Execute the prepared query.
            $stmt->store_result();
 
            if ($stmt->num_rows == 1) {
                // If the user exists get variables from result.
                $stmt->bind_result($password);
                $stmt->fetch();
                $login_check = hash('sha512', $password . $user_browser);
 
                if ($login_check == $login_string) {
                    // Logged In!!!! 
                    return true;
                } else {
                    // Not logged in 
                    return false;
                }
            } else {
                // Not logged in 
                return false;
            }
        } else {
            // Not logged in 
            return false;
        }
    } else {
        // Not logged in 
        return false;
    }
}

function linkify($text){
    if(preg_match("/http/", $text)){	return "<a href='".$text."' target='_blank' >".$text."</a>";	}
    else if(preg_match("/@/", $text)){	return "<a href='mailto:".$text."' target='_blank' >".$text."</a>";	}
    else {  return $text;  }
}

function label_type($type){
    switch($type){
        case "Job":
            return "<span class='label label-primary'>".$type."</span>";
            break;
        case "Internship":
            return "<span class='label label-success'>".$type."</span>";
            break;
        case "Volunteer":
            return "<span class='label label-info'>".$type."</span>";
            break;
        default:
            return "<span class='label label-default'>".$type."</span>";
            break;
    }
}

function isMobile() {
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}

function sanitize_string($string){
    return strip_tags(html_entity_decode($string));
}

?>