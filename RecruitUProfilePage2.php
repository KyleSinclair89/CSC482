<?php
require_once "config.php";
require_once "session.php";

$id = $_SESSION["userid"];

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

$query = $db->prepare("SELECT user_type, first_name, last_name, email FROM users WHERE id = ?");
$query->bind_param("i", $id);
$query->execute();

$result = $query->get_result();
$userInfo = $result->fetch_assoc();

$query->close();
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

  <h2>User Information</h2>

  <!-- Display user information -->
   <p style="color: white; font-weight: bold; text-shadow: -1.5px -1.5px 0 #000, 1.5px -1.5px 0 #000, -1.5px 1.5px 0 #000, 1.5px 1.5px 0 #000;"><?php echo $greeting . $userInfo['first_name'] . ' ' . $userInfo['last_name']; ?></p>

 <!-- Red box element -->
    <div class="red-box" style = "margin-top: 28px; height: 28px; width: 100%; z-index: -1;"></div>


  <p>Your registered email: <?php echo $userInfo['email']; ?></p>


<br>

<form id="userForm" action="RecruitUProfilePage.php" method="post">
<?php if (isset($userInfo['birthday'])): ?>
  <p><strong>Birthday:</strong> <?php echo $userInfo['birthday']; ?></p>
<?php endif; ?>
  <label for="birthday">Re-Enter Birthday:</label>
  <input type="date" id="birthday" name="birthday">
 <br><br>
<?php if (isset($userInfo['heightFeet'])): ?>
  <p><strong>Height:</strong> <?php echo $userInfo['heightFeet'] . ' feet ' . $userInfo['heightIN'] . ' inches'; ?></p>
<?php endif; ?>
  <label for="heightFeet">Re-Enter Height:</label>
  <input type="number" id="heightFeet" name="heightFeet" placeholder="Feet" min="1" max="10">
  <input type="number" id="heightInches" name="heightInches" placeholder="Inches" min="0" max="11">
  <br><br>
<?php if (isset($userInfo['weight'])): ?>
  <p><strong>Weight:</strong> <?php echo $userInfo['weight'] . ' pounds'; ?></p>
<?php endif; ?>
  <label for="weightPounds">Re-Enter Weight:</label>
  <input type="number" id="weightPounds" name="weightPounds" placeholder="Pounds" min="0" max="700">
   <br>
   <br>


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

  <button onclick="submitForm()">Submit</button>

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

  <button onclick="submitForm()">Submit</button>
  <p id="errorMessage"></p>

</form>

  <script>
    // ... (rest of your JavaScript code)
  </script>

</body>
</html>
