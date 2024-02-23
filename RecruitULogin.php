<?php

require_once "config.php";
require_once "session.php";

$error = '';
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
	$email = trim($_POST['email']);
	$password = trim($_POST['password']);

	// check to see if the email field is empty
	if (empty($email) )
	{
		$error .= '<p class ="error">Please enter your email. </p>';
	}
	
	// check to see if password is empty.
	if (empty($password) )
	{
		$error .= '<p class = "error">Please enter your password. </p>';
	}
	
	if(empty($error) )
	{
		if($query = $db->prepare("SELECT * FROM users WHERE email = ?") )
		{
			$query->bind_param('s', $email);
			$query->execute();
			$result = $query->get_result();
			$row = $result->fetch_assoc();

			if($row)
			{
				if(password_verify($password, $row['password']) )
				{
					$_SESSION['loggedin'] = true;
					$_SESSION['userid'] = $row['id'];
					$_SESSION["user"] = $row['username'];
					

					// redirect to user page.
					header("location: RecruitUProfilePage.php");
					exit;
				}
				else
				{
					$error .='<p class = "error"> The password is not valid. Please try again!</p>';
				}
			}
			else
			{
				$error .= '<p class = "error">No User with that email exists. Make sure the email is correct!</p>';
			}
		}
		$query->close();
	}
	// close connection to the database.
	mysqli_close($db);
}
?>	



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RecruitU Login</title>
    <link rel="stylesheet" href="loginStyle.css">
</head>
<header>
	<img src="RecruitULogo.png" alt="RecruitU Logo" class="logo" width="100">
        <h1>Welcome back to RecruitU!</h1>
        <p>Ready to continue your search for athletic success?</p>
    </header>
<body>
    <div class="login-container">
        <h2>Login to RecruitU</h2>
        <form action="RecruitUProfilePage.php" method="post">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
		<br>
            <button type="submit">Login</button>
        </form>
        
        <p>Don't have an account? <a href="RecruitURegister.php">Register here</a>.</p>
    </div>
</body>
</html>
