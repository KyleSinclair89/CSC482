<?php
// Assuming you have already established the database connection

// Retrieve the search query
$searchQuery = $_GET['query'];

// Parse the search query (assuming it's in "FirstName LastName" format)
list($firstName, $lastName) = explode(' ', $searchQuery);

// Query the database for matching users
$query = "SELECT id, first_name, last_name FROM users WHERE first_name LIKE ? AND last_name LIKE ?";
$stmt = $conn->prepare($query);
$searchTerm = "%$firstName%";
$stmt->bind_param("ss", $searchTerm, $searchTerm);
$stmt->execute();
$result = $stmt->get_result();
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
        while ($row = $result->fetch_assoc()) {
            // Display search results as clickable links to user pages
            echo "<li><a href='RecruitUUserPage.php?userid={$row['id']}'>{$row['first_name']} {$row['last_name']}</a></li>";
        }
        ?>
    </ul>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>

