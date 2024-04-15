
<?php
require_once "config.php";

// get id from url parameter
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

        // Omit the trailing comma
        $query = rtrim($query, ',') . " WHERE id = ?";

        // Prepare and execute the query
        $stmt = $db->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }
}

$query = $db->prepare("SELECT user_type, first_name, last_name, email FROM users WHERE id = ?");
$query->bind_param("i", $id);
$query->execute();

$result = $query->get_result();
$userInfo = $result->fetch_assoc();

$query->close();
?>


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
    $stmt->bind_param("isssi", $id, $schoolName, $major, $gpa, $graduationYear);
    $stmt->execute();
    $stmt->close();
}

// Retrieve user's school history from the database
$query = "SELECT school_name, major, gpa, graduation_year FROM school_history WHERE user_id = ?";
$stmt = $db->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$schoolHistory = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<?php
require_once "config.php";
require_once "session.php";

// Check if the user is logged in
if (!isset($_SESSION["loggedin"]) || !isset($_SESSION["userid"])) {
    // Redirect the user to the login page if not logged in
    header("Location: login.php");
    exit;
}

// Check if the form for adding sports history is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["addSport"])) {
    // Retrieve user input
    $sport = $_POST["sport"];
    $position = $_POST["position"];
    $yearsPlayed = $_POST["yearsPlayed"];
    $accolades = $_POST["accolades"];

    // Insert the sports history into the database
    $query = "INSERT INTO sports_history (user_id, sport, position, years_played, accolades) VALUES (?, ?, ?, ?, ?)";
    $stmt = $db->prepare($query);
    $stmt->bind_param("issss", $id, $sport, $position, $yearsPlayed, $accolades);
    $stmt->execute();
    $stmt->close();
}

// Retrieve user's sports history from the database
$query = "SELECT sport, position, years_played, accolades FROM sports_history WHERE user_id = ?";
$stmt = $db->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$sportsHistory = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>



<!DOCTYPE html>
<!-- FOR SOME REASON, NEW CSS IMPLEMENTATIONS DONT WORK WHEN WRITING THEM IN THE CSS FILE. FOR STYLING, WRITE THEM AS IN-LINE DOCUMENT -->
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Information Form</title>
  <link rel="stylesheet" type="text/css" href="RecruitUProfilePageStyle.css">
</head>



<body style = "margin-left: 30px;">



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

<?php
$query = $db->prepare("SELECT user_type, first_name, last_name, email, birthday, heightFeet, heightIN, weight FROM users WHERE id = ?");
$query->bind_param("i", $id);
$query->execute();

$result = $query->get_result();
$userInfo = $result->fetch_assoc();

$query->close();

// Debug output
// var_dump($userInfo);
?>

<a href="RecruitUHomePage.php">
<img src="RecruitULogo.png" alt="RecruitU Logo" style="position: absolute; top: 35px; left: 50px; width: 200px; height: auto;">
</a>
<ul style="position: relative; left: 1100px; top: 100px; list-style-type: none; margin: 0; padding: 0;">
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

<br><br><br><br><br><br><br>
  <h2 style = "margin-top: 33px;">User Information</h2>

  <!-- Display user information -->
<br>
   <p style="color: white; font-weight: bold; text-shadow: -1.5px -1.5px 0 #000, 1.5px -1.5px 0 #000, -1.5px 1.5px 0 #000, 1.5px 1.5px 0 #000;"><?php echo $greeting . $userInfo['first_name'] . ' ' . $userInfo['last_name']; ?></p>

 <!-- Red box element -->
    <div class="red-box" style = "margin-top: -22px; height: 28px; width: 100%; z-index: -1;"></div>


  <p style = "font-weight: bold;">Registered email for <?php echo $userInfo['first_name'] ?>: <?php echo $userInfo['email']; ?></p>


<br>

<form id="userForm" action="RecruitUProfilePage.php" method="post">
<?php if (isset($userInfo['birthday'])): ?>
  <p style = "color: white; font-weight: bold; text-shadow: -1.5px -1.5px 0 #000, 1.5px -1.5px 0 #000, -1.5px 1.5px 0 #000, 1.5px 1.5px 0 #000;"><strong>Birthday:</strong> <?php echo $userInfo['birthday']; ?></p>
<?php endif; ?>

 <br>
<?php if (isset($userInfo['heightFeet'])): ?>
  <p style = "color: white; font-weight: bold; text-shadow: -1.5px -1.5px 0 #000, 1.5px -1.5px 0 #000, -1.5px 1.5px 0 #000, 1.5px 1.5px 0 #000;"><strong>Height:</strong> <?php echo $userInfo['heightFeet'] . ' feet ' . $userInfo['heightIN'] . ' inches'; ?></p>
<?php endif; ?>
  <br>
<?php if (isset($userInfo['weight'])): ?>
  <p style = "color: white; font-weight: bold; text-shadow: -1.5px -1.5px 0 #000, 1.5px -1.5px 0 #000, -1.5px 1.5px 0 #000, 1.5px 1.5px 0 #000;"><strong>Weight:</strong> <?php echo $userInfo['weight'] . ' pounds'; ?></p>
<?php endif; ?>
   <br>

</form>
<br><br>
<div style="margin-left: -10px; background-color: #af0a06; padding-bottom: 20px; border-radius: 10px; padding-right: 20px; border: 2px solid black;">

 <h2 style="margin-left: 150px; font-size: 48px; color: white; font-weight: bold; text-shadow: -1.5px -1.5px 0 #000, 1.5px -1.5px 0 #000, -1.5px 1.5px 0 #000, 1.5px 1.5px 0 #000; margin-bottom: 35px;">School History</h2>

    <table style="margin-left: 25px; background-color: white; border-collapse: collapse; color: white; border-radius: 10px;">
        <thead>
            <tr>
    		<th style="border: 2px solid #af0a06; padding: 8px; border-radius: 10px; color: black;">School Name</th>
    		<th style="border: 2px solid #af0a06; padding: 8px; border-radius: 10px; color: black;">Major</th>
    		<th style="border: 2px solid #af0a06; padding: 8px; border-radius: 10px; color: black;">GPA</th>
   		 <th style="border: 2px solid #af0a06; padding: 8px; border-radius: 10px; color: black;">Graduation Year</th>
	      </tr>

        </thead>
        <tbody>
            <?php foreach ($schoolHistory as $school): ?>
            <tr>
                <td style = "border: 2px solid #af0a06; padding: 8px; font-weight: bold; border-radius: 10px;  color: black;"><?php echo $school['school_name']; ?></td>
                <td style = "border: 2px solid #af0a06; padding: 8px; font-weight: bold; border-radius: 10px;  color: black;"><?php echo $school['major']; ?></td>
                <td style = "border: 2px solid #af0a06; padding: 8px; font-weight: bold; border-radius: 10px; color: black;"><?php echo $school['gpa']; ?></td>
                <td style = "border: 2px solid #af0a06; padding: 8px; font-weight: bold; border-radius: 10px; color: black;"><?php echo $school['graduation_year']; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

    
<br><br>
<div style="margin-left: -10px; background-color: #af0a06; padding-bottom: 20px; border-radius: 10px; padding-right: 20px; border: 2px solid black;">

 <h2 style="margin-left: 150px; font-size: 48px; color: white; font-weight: bold; text-shadow: -1.5px -1.5px 0 #000, 1.5px -1.5px 0 #000, -1.5px 1.5px 0 #000, 1.5px 1.5px 0 #000; margin-bottom: 35px;">Sports History</h2>

    <table style="margin-left: 25px; background-color: white; border-collapse: collapse; color: white; border-radius: 10px;">
        <thead>
            <tr>
                <th style="border: 2px solid #af0a06; padding: 8px; border-radius: 10px; color: black;">Sport</th>
                <th style="border: 2px solid #af0a06; padding: 8px; border-radius: 10px; color: black;">Position</th>
                <th style="border: 2px solid #af0a06; padding: 8px; border-radius: 10px; color: black;">Years Played</th>
                <th style="border: 2px solid #af0a06; padding: 8px; border-radius: 10px; color: black;">Accolades</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($sportsHistory as $sport): ?>
            <tr>
                <td style="border: 2px solid #af0a06; padding: 8px; font-weight: bold; border-radius: 10px;  color: black;"><?php echo $sport['sport']; ?></td>
                 <td style="border: 2px solid #af0a06; padding: 8px; font-weight: bold; border-radius: 10px;  color: black;"><?php echo $sport['position']; ?></td>
                <td style="border: 2px solid #af0a06; padding: 8px; font-weight: bold; border-radius: 10px; color: black;"><?php echo $sport['years_played']; ?></td>
                 <td style="border: 2px solid #af0a06; padding: 8px; font-weight: bold; border-radius: 10px; color: black;"><?php echo $sport['accolades']; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
    

  <script>
    
  </script>
<br>
</body>
</html>
