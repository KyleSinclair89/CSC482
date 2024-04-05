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
    <title>Search Results</title>
</head>
<body>
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
