<?php
// Start the login session
session_start();

// Initialize login status message
$loginStatus = "";

// Check if the user is logged in
if (isset($_SESSION["loggedin"]) && isset($_SESSION["userid"])) {
    // User is logged in
    $loginStatus = $_SESSION["user"] . " is logged in!";
} else {
    // User is not logged in
    // Ensure loggedin tag reflects true loggedin status
    $_SESSION["loggedin"] = false;
    $loginStatus = "User is not logged in";
}
?>
