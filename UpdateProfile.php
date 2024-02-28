<?php
require_once "config.php";
require_once "session.php";

$birthday = isset($_POST['birthday']) ? $_POST['birthday'] : null;
$heightFeet = isset($_POST['heightFeet']) ? $_POST['heightFeet'] : null;
$heightInches = isset($_POST['heightIN']) ? $_POST['heightIN'] : null;
$weightPounds = isset($_POST['weightPounds']) ? $_POST['weightPounds'] : null;

// Assuming $id is the user's ID retrieved from the session
$id = $_SESSION["userid"];

// Update user information in the database
$updateQuery = $db->prepare("UPDATE users SET birthday = ?, heightFeet = ?, heightIN = ?, weight = ? WHERE id = ?");
if (!$updateQuery) {
    die('Error in preparing SQL query: ' . $db->error);
}

$updateQuery->bind_param("siiii", $birthday, $heightFeet, $heightIN, $weightPounds, $id);

$result = $updateQuery->execute();

if (!$result) {
    die('Error in executing SQL query: ' . $updateQuery->error);
}

$updateQuery->close();

echo '<p class="success">User information updated successfully!</p>';
// redirect to user page.
header("location: RecruitUProfilePage.php");
exit;
?>
