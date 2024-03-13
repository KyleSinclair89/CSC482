<?php

// start the session once again
session_start();

// destroy the session
if (session_destroy() ) 
{
	// redirect to the login page.
	header("location: RecruitULogin.html");
}

?>
