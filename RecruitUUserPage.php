<?php
require_once "config.php";
require_once "session.php";

// Get the user ID from the URL parameter
$id = $_GET["userid"];

// Additional code for handling form submissions
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Check if the form is submitted

    // Retrieve user input
    $birthday = isset($_POST["birthday"]) ? $_POST["birthday"] : null;
    $heightFeet = isset($_POST["heightFeet"]) ? $_POST["heightFeet"] : null;
    $heightInches = isset($_POST["heightInches"]) ? $_POST["heightInches"] : null;
    $weight = isset($_POST["weightPounds"]) ? $_POST["weightPounds"] : null;

    // Check if any of the attributes have values
    if ($birthday || $heightFeet || $heightInches || $weight) {
        // Build the SQL query to update the user's information
        $query = "UPDATE users SET";

        if ($birthday) {
            $query .= " birthday = '$birthday',";
        }

        if ($heightFeet) {
            $query .= " heightFeet = '$heightFeet',";
        }

        if ($heightInches) {
            $query .= " heightIN = '$heightInches',";
        }

        if ($weight) {
            $query .= " weight = '$weight',";
        }

        // Remove the trailing comma and complete the query
        $query = rtrim($query, ',') . " WHERE id = ?";

        // Prepare and execute the query
        $stmt = $db->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }
}

$query = $db->prepare("SELECT user_type, first_name, last_name, email, birthday, heightFeet, heightIN, weight FROM users WHERE id = ?");
$query->bind_param("i", $id);
$query->execute();

$result = $query->get_result();
$userInfo = $result->fetch_assoc();

$query->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>User Profile</title>
<link rel="stylesheet" type="text/css" href="RecruitUProfilePageStyle.css">
</head>

<body>

<?php
// Modify the greeting based on user_type
$greeting = "";
switch ($userInfo['user_type']) {
  case 'player':
    $greeting = "Player: ";
    break;
  case 'coach':
    $greeting = "Coach: ";
    break;
  case 'scout':
    $greeting = "Scout: ";
    break;
}
?>

<a href="RecruitUHomePage.php">
<img src="RecruitULogo.png" alt="RecruitU Logo" style="position: absolute; top: 35px; left: 50px; width: 200px; height: auto;">
</a>
<ul style="position: absolute; left: 1000px; top: 100px; list-style-type: none; margin: 0; padding: 0;">
    <li style="display: inline; font-weight: bold; margin-right: 20px;">
        <a href="RecruitUHomePage.php" style="background-color: #af0a06; color: white; padding: 10px; text-decoration: none; border-radius: 5px; border: 2.5px solid black; box-shadow: 0 0 10px rgba(0, 0, 0, 0.3); transition: box-shadow 0.3s, background-color 0.3s, color 0.3s;" onmouseover="this.style.backgroundColor='#000'; this.style.color='#EE2737';" onmouseout="this.style.backgroundColor='#af0a06'; this.style.color='white';">Home</a>
    </li>
    <li style="display: inline; font-weight: bold; margin-right: 20px;">
        <a href="RecruitUProfilePage.php" style="background-color: #af0a06; color: white; padding: 10px; text-decoration: none; border-radius: 5px; border: 2.5px solid black; box-shadow: 0 0 10px rgba(0, 0, 0, 0.3); transition: box-shadow 0.3s, background-color 0.3s, color 0.3s;" onmouseover="this.style.backgroundColor='#000'; this.style.color='#EE2737';" onmouseout="this.style.backgroundColor='#af0a06'; this.style.color='white';">Profile</a>
    </li>
    <li style="display: inline; font-weight: bold; margin-right: 20px;">
        <a href="SignOut.php" style="background-color: #af0a06; color: white; padding: 10px; text-decoration: none; border-radius: 5px; border: 2.5px solid black; box-shadow: 0 0 10px rgba(0, 0, 0, 0.3); transition: box-shadow 0.3s, background-color 0.3s, color 0.3s;" onmouseover="this.style.backgroundColor='#000'; this.style.color='#EE2737';" onmouseout="this.style.backgroundColor='#af0a06'; this.style.color='white';">Sign Out</a>
    </li>
</ul>

<br><br><br><br><br><br><br>
<h2>User Information</h2>

<p style="color: white; font-weight: bold;"><?php echo $greeting . $userInfo['first_name'] . ' ' . $userInfo['last_name']; ?></p>

<div class="red-box"></div>

<p style="font-weight: bold;">Your registered email: <?php echo $userInfo['email']; ?></p>

<?php if (isset($userInfo['birthday'])): ?>
  <p style="color: white; font-weight: bold;">Birthday: <?php echo $userInfo['birthday']; ?></p>
<?php endif; ?>

<?php if (isset($userInfo['heightFeet'])): ?>
  <p style="color: white; font-weight: bold;">Height: <?php echo $userInfo['heightFeet'] . ' feet ' . $userInfo['heightIN'] . ' inches'; ?></p>
<?php endif; ?>

<?php if (isset($userInfo['weight'])): ?>
  <p style="color: white; font-weight: bold;">Weight: <?php echo $userInfo['weight'] . ' pounds'; ?></p>
<?php endif; ?>

<div style="margin-left: -30px; background-color: #af0a06; padding: 10px; border-radius: 10px; width: 101%; border: 2px solid black;">
<h2 style="margin-left: 420px; font-size: 48px; color: white; font-weight: bold; margin-bottom: 35px;">School History</h2>
<table style="margin-left: 395px; background-color: white; border-collapse: collapse; width: 30%; border-radius: 10px;">
    <thead>
        <tr>
            <th style="border: 2px solid #af0a06; padding: 8px; border-radius: 10px;">School Name</th>
            <th style="border: 2px solid #af0a06; padding: 8px; border-radius: 10px;">Major</th>
            <th style="border: 2px solid #af0a06; padding: 8px; border-radius: 10px;">GPA</th>
            <th style="border: 2px solid #af0a06; padding: 8px; border-radius: 10px;">Graduation Year</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Retrieve user's school history from the database
        $query = "SELECT school_name, major, gpa, graduation_year FROM school_history WHERE user_id = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $schoolHistory = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        foreach ($schoolHistory as $school): ?>
            <tr>
                <td style="border: 2px solid #af0a06; padding: 8px; border-radius: 10px;"><?php echo $school['school_name']; ?></td>
                <td style="border: 2px solid #af0a06; padding: 8px; border-radius: 10px;"><?php echo $school['major']; ?></td>
                <td style="border: 2px solid #af0a06; padding: 8px; border-radius: 10px;"><?php echo $school['gpa']; ?></td>
                <td style="border: 2px solid #af0a06; padding: 8px; border-radius: 10px;"><?php echo $school['graduation_year']; ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</div>

<div style="margin-left: -30px; background-color: #af0a06; padding: 10px; border-radius: 10px; width: 101%; border: 2px solid black;">
<h2 style="margin-left: 420px; font-size: 48px; color: white; font-weight: bold; margin-bottom: 35px;">Sports History</h2>
<table style="margin-left: 395px; background-color: white; border-collapse: collapse; width: 30%; border-radius: 10px;">
    <thead>
        <tr>
            <th style="border: 2px solid #af0a06; padding: 8px; border-radius: 10px;">Sport</th>
            <th style="border: 2px solid #af0a06; padding: 8px; border-radius: 10px;">Position</th>
            <th style="border: 2px solid #af0a06; padding: 8px; border-radius: 10px;">Years Played</th>
            <th style="border: 2px solid #af0a06; padding: 8px; border-radius: 10px;">Accolades</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Retrieve user's sports history from the database
        $query = "SELECT sport, position, years_played, accolades FROM sports_history WHERE user_id = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $sportsHistory = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        foreach ($sportsHistory as $sport): ?>
            <tr>
                <td style="border: 2px solid #af0a06; padding: 8px; border-radius: 10px;"><?php echo $sport['sport']; ?></td>
                <td style="border: 2px solid #af0a06; padding: 8px; border-radius: 10px;"><?php echo $sport['position']; ?></td>
                <td style="border: 2px solid #af0a06; padding: 8px; border-radius: 10px;"><?php echo $sport['years_played']; ?></td>
                <td style="border: 2px solid #af0a06; padding: 8px; border-radius: 10px;"><?php echo $sport['accolades']; ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</div>

</body>
</html>
