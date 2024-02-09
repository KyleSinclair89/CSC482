<?php

require_once "config.php";
require_once "session.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $firstName = trim($_POST['first_name']);
    $lastName = trim($_POST['last_name']);
    $userType = trim($_POST['user_type']);
    $password_hash = password_hash($password, PASSWORD_BCRYPT);

    if ($query = $db->prepare("SELECT * FROM users WHERE email = ?")){
        $error = '';
        // bind parameters
        $query->bind_param('s', $email);
        $query->execute();
        // store the result to check the content that exists in the DB.
        $query->store_result();
        if ($query->num_rows > 0) {
            $error .= '<p class="error">Email is already registered.</p>';
        } else {
            // validate password
            if (strlen($password) < 6) {
                $error .= '<p class="error">Password must be 6 or more characters.</p>';
            }
        }
        if (empty($error)) {
            $insertQuery = $db->prepare("INSERT INTO users (username, email, password, first_name, last_name, user_type) VALUES (?, ?, ?, ?, ?, ?)");
            $insertQuery->bind_param("ssssss", $username, $email, $password_hash, $firstName, $lastName, $userType);
            $result = $insertQuery->execute();
            if ($result) {
                $error .= '<p class="success">Your registration was successful!</p>';
            } else {
                $error .= '<p class="error">Something went wrong! Please try again.</p>';
            }
            // Must be here to ensure insertQuery exists
            $insertQuery->close();
        }
    }

    $query->close();
    // Close connection to the DB.
    mysqli_close($db);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dish List | Food Pantry</title>
    <link rel="stylesheet" type="text/css" href="DishListStyle.css">

    <style>
        h2 {
            text-align: center;
        }
        form {
            text-align: center;
        }
    </style>
</head>
<body>
    <header>
        <img src="DishListLogo.png" alt="Dish List Logo" class="logo" width="100">
        <h1>Welcome to Dish List!</h1>
        <p>Explore a wide range of delicious recipes.</p>
    </header>

    <nav>
        <ul class="menu-item">
            <li class="Main">
                <a href="DishListMain.html">Home</a>
            </li>
            <li class="Recipes">
                <a href="DishListRecipes.html">Recipes</a>
            </li>
            <li class="About Us">
                <a href="DishListAboutUs.html">About Us</a>
            </li>
            <li class="Login">
                <a href="DishListLogin.html">Login</a>
            </li>
            <li class="Register">
                <a href="DishListRegister.html">Register</a>
            </li>
            <li class="CreateRecipe">
                <a href="CreateRecipe.html">Create Recipe</a>
            </li>
            <li>
                <a href="DishListCart.html">Cart</a>
            </li>
            <li>
                <a href="history.html">History</a>
            </li>
        </ul>
    </nav>

    <div class="container">
        <h2>Register</h2>
        <form action="DishListRegister.php" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required><br><br>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br><br>

            <label for="email">Email Address:</label>
            <input type="email" id="email" name="email" required><br><br>

            <!-- New fields added for registration -->
            <label for="first_name">First Name:</label>
            <input type="text" id="first_name" name="first_name" required><br><br>

            <label for="last_name">Last Name:</label>
            <input type="text" id="last_name" name="last_name" required><br><br>

            <label for="user_type">User Type:</label>
            <select id="user_type" name="user_type" required>
                <option value="coach">Coach</option>
                <option value="scout">Scout</option>
            </select><br><br>

            <input type="submit" class="btn btn-primary" name="submit" value="Register">

            <p>Already have an account? <a href="DishListLogin.html">Sign in here</a></p>

            <p><a href="DishListMain.html">Return Home</a></p>
        </form>
    </div>
</body>
</html>
