<?php
// Include the database connection file
require_once "config.php";

// Retrieve the search query
$lastName = $_GET['lastName'];

// Query the database for matching users based on last name
$query = "SELECT id, first_name, last_name, user_type FROM users WHERE last_name LIKE ?";
$stmt = $db->prepare($query);

// Check if the prepare() call failed
if ($stmt === false) {
    die("Error: Unable to prepare query. " . $db->error);
}

// Bind the parameter and execute the statement
$searchTerm = "%$lastName%";
$stmt->bind_param("s", $searchTerm);
$stmt->execute();

// Get the result set
$result = $stmt->get_result();

// Close the statement
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" type="text/css" href="RecruitUHomePageStyle.css">
    <title>Search Results</title>
</head>
<body>

<img id="logo" src="RecruitULogo.png" alt="RecruitU Logo">
<ul style="position: absolute; left: 1000px; top: 100px; list-style-type: none; margin: 0; padding: 0;">
    <li style="display: inline; font-weight: bold; margin-right: 20px;">
        <a href="RecruitUHomePage.php" style="background-color: #af0a06; color: white; padding: 10px; text-decoration: none; border-radius: 5px; border: 2.5px solid black; box-shadow: 0 0 10px rgba(0, 0, 0, 0.3); transition: box-shadow 0.3s, background-color 0.3s, color 0.3s;" onmouseover="this.style.backgroundColor='#000'; this.style.color='#EE2737';" onmouseout="this.style.backgroundColor='#af0a06'; this.style.color='white';">Home</a>
    </li>
    <li style="display: inline; font-weight: bold; margin-right: 20px;">
        <a href="RecruitUProfilePage.php" style="background-color: #af0a06; color: white; padding: 10px; text-decoration: none; border-radius: 5px; border: 2.5px solid black; box-shadow: 0 0 10px rgba(0, 0, 0, 0.3); transition: box-shadow 0.3s, background-color 0.3s, color 0.3s;" onmouseover="this.style.backgroundColor='#000'; this.style.color='#EE2737';" onmouseout="this.style.backgroundColor='#af0a06'; this.style.color='white';">Profile</a>
    </li>
    <li style="display: inline; font-weight: bold; margin-right: 20px;">
        <a href="RecruitUAboutUs.html" style="background-color: #af0a06; color: white; padding: 10px; text-decoration: none; border-radius: 5px; border: 2.5px solid black; box-shadow: 0 0 10px rgba(0, 0, 0, 0.3); transition: box-shadow 0.3s, background-color 0.3s, color 0.3s;" onmouseover="this.style.backgroundColor='#000'; this.style.color='#EE2737';" onmouseout="this.style.backgroundColor='#af0a06'; this.style.color='white';">About Us</a>
    </li>
    <li style="display: inline; font-weight: bold; margin-right: 20px;">
        <a href="SignOut.php" style="background-color: #af0a06; color: white; padding: 10px; text-decoration: none; border-radius: 5px; border: 2.5px solid black; box-shadow: 0 0 10px rgba(0, 0, 0, 0.3); transition: box-shadow 0.3s, background-color 0.3s, color 0.3s;" onmouseover="this.style.backgroundColor='#000'; this.style.color='#EE2737';" onmouseout="this.style.backgroundColor='#af0a06'; this.style.color='white';">Sign Out</a>
    </li>
</ul>
    <h1>Search Results</h1>
    <ul>
        <?php
        // Loop through the result set and display user information
        while ($row = $result->fetch_assoc()) {
            // Display search results as clickable links to user pages
            echo "<li><a href='RecruitUUserPage.php?userid={$row['id']}'>{$row['first_name']} {$row['last_name']} - {$row['user_type']}</a></li>";
        
        }
        ?>
    </ul>
</body>
</html>

<?php
// Close the database connection
$db->close();
?>
