<?php
require_once "config.php";
require_once "session.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_id = $_SESSION["userid"];
    $sport = $_POST["sport"];
    $position = $_POST["position"];
    $yearsPlayed = $_POST["yearsPlayed"];
    $accolades = $_POST["accolades"];

    $query = "INSERT INTO sports_history (user_id, sport, position, years_played, accolades) VALUES (?, ?, ?, ?, ?)";
    $stmt = $db->prepare($query);
    $stmt->bind_param("isiss", $user_id, $sport, $position, $yearsPlayed, $accolades);
    $stmt->execute();
    $stmt->close();
}
?>
