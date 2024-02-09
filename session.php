<?php
// Start the login session
session_start();

// if the user is logged in, welcome and redirect to home page.
//Check if loggedin has been set and if the userid has been set to something
if (isset($_SESSION["loggedin"]) && isset($_SESSION["userid"])) {
	//Test echos to make sure it is working
	echo $_SESSION["user"] . " is logged in!";
} else {
	//User in not logged in
	//Ensure loggedin tag reflects true loggedin status
	$_SESSION["loggedin"] = false;
	echo "User is not logged in";
}
?>
