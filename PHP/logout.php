<?php
session_start(); // Start the session

// Destroy all session variables
session_unset();

// Destroy the session
session_destroy();

// Remove the session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
}

// Redirect to the homepage
header("Location: ../index.html");
exit();
?>