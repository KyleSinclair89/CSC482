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
        <form action="login_process.php" method="post">
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
