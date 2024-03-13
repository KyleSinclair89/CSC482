<?php
require_once "config.php";
require_once "session.php";

// Check if the user is logged in
if (!isset($_SESSION["loggedin"]) || !isset($_SESSION["userid"])) {
    // Redirect the user to the login page if not logged in
    header("Location: login.php");
    exit;
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["addSchool"])) {
    // Retrieve user input
    $schoolName = $_POST["schoolName"];
    $major = $_POST["major"];
    $gpa = $_POST["gpa"];
    $graduationYear = $_POST["graduationYear"];

    // Insert the school history into the database
    $query = "INSERT INTO school_history (user_id, school_name, major, gpa, graduation_year) VALUES (?, ?, ?, ?, ?)";
    $stmt = $db->prepare($query);
    $stmt->bind_param("isdsi", $_SESSION["userid"], $schoolName, $major, $gpa, $graduationYear);
    $stmt->execute();
    $stmt->close();
}

// Retrieve user's school history from the database
$query = "SELECT school_name, major, gpa, graduation_year FROM school_history WHERE user_id = ?";
$stmt = $db->prepare($query);
$stmt->bind_param("i", $_SESSION["userid"]);
$stmt->execute();
$result = $stmt->get_result();
$schoolHistory = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>
