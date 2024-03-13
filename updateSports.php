<?php
require_once "config.php";
require_once "session.php";

// Assuming $id is the user's ID retrieved from the session
$id = $_SESSION["userid"];

// Additional code for handling form submissions
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve sports history data from the form
    $sport = isset($_POST['sport']) ? $_POST['sport'] : null;
    $position = isset($_POST['position']) ? $_POST['position'] : null;
    $yearsPlayed = isset($_POST['yearsPlayed']) ? $_POST['yearsPlayed'] : null;
    $accolades = isset($_POST['accolades']) ? $_POST['accolades'] : null;

    // Insert or update the sports history data in the database
    $updateQuery = $db->prepare("INSERT INTO sports_history (user_id, sport, position, years_played, accolades) VALUES (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE sport = VALUES(sport), position = VALUES(position), years_played = VALUES(years_played), accolades = VALUES(accolades)");
    if (!$updateQuery) {
        die('Error in preparing SQL query: ' . $db->error);
    }

    $updateQuery->bind_param("isssi", $id, $sport, $position, $yearsPlayed, $accolades);

    $result = $updateQuery->execute();

    if (!$result) {
        die('Error in executing SQL query: ' . $updateQuery->error);
    }

    $updateQuery->close();

    echo '<p class="success">Sports history updated successfully!</p>';
    // Redirect to user page.
    header("location: RecruitUProfilePage.php");
    exit;
}
?>
