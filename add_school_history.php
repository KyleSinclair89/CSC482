<?php
require_once "config.php";
require_once "session.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_id = $_SESSION["userid"];
    $schoolName = $_POST["schoolName"];
    $major = $_POST["major"];
    $gpa = $_POST["gpa"];
    $classYear = $_POST["classYear"];

    $query = "INSERT INTO school_history (user_id, school_name, major, gpa, class_year) VALUES (?, ?, ?, ?, ?)";
    $stmt = $db->prepare($query);
    $stmt->bind_param("isdsi", $user_id, $schoolName, $major, $gpa, $classYear);
    $stmt->execute();
    $stmt->close();
}
?>
