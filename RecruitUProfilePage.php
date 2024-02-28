<?php
require_once "config.php";
require_once "session.php";

$id = $_SESSION["userid"];

$query = $db->prepare("SELECT first_name, last_name, email FROM users WHERE id = ?");
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
  <title>User Information Form</title>
  <link rel="stylesheet" type="text/css" href="RecruitUProfilePageStyle.css">
</head>
<body>

  <h2>User Information</h2>

  <!-- Display user information -->
  <p>Welcome to RecruitU, <?php echo $userInfo['first_name'] . ' ' . $userInfo['last_name']; ?>!</p>
  <p>Your registered email: <?php echo $userInfo['email']; ?></p>

  <form action="UpdateProfile.php" method="post">
    <label for="birthday">Birthday:</label>
    <input type="date" id="birthday" name="birthday" value="">
    <br>

    <label for="heightFeet">Height:</label>
    <input type="number" id="heightFeet" name="heightFeet" placeholder="Feet" min="1" max="10" value="">
    <input type="number" id="heightIN" name="heightIN" placeholder="Inches" min="0" max="11" value="">
    <br>

    <label for="weightPounds">Weight:</label>
    <input type="number" id="weightPounds" name="weightPounds" placeholder="Pounds" min="0" max="700" value="">
    <br>

    <br>
    <br>
    <input type="submit" name="submit" value="Update Information">
  </form>

  <h2>School History</h2>

  <table>
    <thead>
      <tr>
        <th>School Name</th>
        <th>Major</th>
        <th>GPA</th>
        <th>Class Year</th>
      </tr>
    </thead>
    <tbody id="schoolHistory">
      <!-- Users can dynamically add rows to this table -->
    </tbody>
  </table>

  <button onclick="addOrUpdateSchoolRow()">Add or Update School</button>

<h2>Sports History</h2>

  <table>
    <thead>
      <tr>
        <th>Sport</th>
        <th>Position</th>
        <th>Years Played</th>
        <th>Accolades</th>
      </tr>
    </thead>
    <tbody id="sportsHistory"> <!-- Use a unique identifier for the tbody -->
      <!-- Users can dynamically add rows to this table -->
    </tbody>
  </table>

  <button onclick="addOrUpdateSportRow()">Add or Update Sport</button> <!-- Update the function name -->

  
  <p id="errorMessage"></p>

  <script>
    // ... (rest of your JavaScript code)
  </script>

</body>
</html>
