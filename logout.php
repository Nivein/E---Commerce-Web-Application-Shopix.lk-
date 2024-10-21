<?php
session_start();
session_destroy();  // Destroy all session data
setcookie(session_name(), '', time() - 3600, '/');  // Clear the session cookie
header("Location: login.php");  // Redirect to login page
exit();
?>