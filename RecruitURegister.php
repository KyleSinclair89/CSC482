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

    if ($query = $db->prepare("SELECT * FROM users WHERE email = ?")){ // "?" MEANS BIND PARAMETER. THATS WHY WE INCLUDE WHAT THE BIND PARAMETER IS IN THE NEXT FEW LINES.
        $error = '';
        // bind parameters
        $query->bind_param('s', $email);
        $query->execute(); // OBTAINS THE EMAIL WERE REGISTERING WITH.
        // store the result to check the content that exists in the DB.
        $query->store_result(); // STORES THE RESULT OF THE WHAT THE EMAIL WERE TRYING TO REGISTER WITH.
        if ($query->num_rows > 0) { // USES THE EMAIL IN THE QUERY TO SEE IF AN ACCOUNT WITH THAT EMAIL ALREADY EXISTS IN THE USER TABLE. IF THERE IS, THIS WILL INCREMENT TO A NUMBER GREATER THAN 0,
                                   // MEANING AN ACCOUNT WITH THAT EMAIL ALREADY EXISTS.
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
<html lang="en">

<head>
    <title>RecruitU | Recruitment Center</title>
    <link rel="stylesheet" type="text/css" href="LoginStyle.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #EE2737;
        }

        #loginStatus {
    text-align: left;
    padding: 10px;
    background-color: #fff;
    color: #000;
    position: fixed;
    top: 0;
    left: 0;
    margin-bottom: 100px;
}


        header {
    margin-left: 150px;
    text-align: center;
    padding: 40px; /* Adjust the padding here */
    font-weight: bold;
}

.container {
    background-color: #fff;
    padding: 10px;
    border-radius: 8px;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
    max-width: 400px;
    width: 90%;
    margin: auto;
    margin-top: 10px; /* Adjust the margin-top here */
    margin-right: 150px;
    margin-bottom: 10px;
}

        h2,
        form {
            text-align: center;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="password"],
        input[type="email"],
        select {
            width: calc(100% - 30px);
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        button[type="submit"] {
            width: calc(100% - 30px);
            padding: 10px;
            background-color: #e74c3c;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button[type="submit"]:hover {
            background-color: #c0392b;
        }

        p {
            margin-top: 15px;
            text-align: center;
        }

        a {
            color: #007bff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .logo {
            width: 250px;
            margin: 0 auto 20px; /* Center the logo and add margin at the bottom */
            display: block;
        }
    </style>
</head>

<body>
    
    <header>
        <img src="RecruitULogo.png" alt="RecruitU Logo" class="logo" width="100">
        <h1>Welcome to RecruitU!</h1>
        <p>Explore the globe for the best athletes around.</p>
    </header>

    <div class="container">
        <h2>Register</h2>
        <form action="RecruitURegister.php" method="post">
            <!-- Your registration form inputs go here -->
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

            <label for="user_type">Select Role (I am a):</label>
            <select id="user_type" name="user_type" required>
                <option value="coach">Coach</option>
                <option value="player">Player</option>
                <option value="scout">Scout</option>
            </select><br><br>

            <input type="submit" class="btn btn-primary" name="submit" value="Register">

            <p>Already have an account? <a href="RecruitULogin.html">Sign in here</a></p>
        </form>
    </div>
</body>

</html>

