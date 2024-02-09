<?php
define('DBSERVER', 'localhost'); // Database server
define('DBUSERNAME', 'root'); // DB Username
define('DBPASSWORD', ''); // DB PASSWORD
define('DBNAME', 'accounts'); // DB name

/* connect to MySQL database */
$db = mysqli_connect(DBSERVER, DBUSERNAME, DBPASSWORD, DBNAME);

// Check db connection
if($db === false)
{
	die("Error: Trouble connecting to database. " . mysqli_connect_error());
}

?>
