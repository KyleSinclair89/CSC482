<?php
// Assuming the update form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_profile'])) {
    // Update the user's profile information in the database
    $birthday = $_POST['birthday'];
    $age = $_POST['age'];
    $height = $_POST['height'];
    $weight = $_POST['weight'];
    $school_history = $_POST['school_history'];

    // Add your database insertion logic here
    $updateQuery = $db->prepare("UPDATE users SET birthday=?, age=?, height=?, weight=?, school_history=? WHERE username=?");
    $updateQuery->bind_param("sddddss", $birthday, $age, $height, $weight, $school_history, $userData['username']);
    $updateResult = $updateQuery->execute();

    if ($updateResult) {
        $updateStatus = '<p class="success">Profile updated successfully!</p>';
        // Update $userData with the new values if needed
    } else {
        $updateStatus = '<p class="error">Error updating profile. Please try again.</p>';
    }

    $updateQuery->close();
}
?>

<!-- ... (previous HTML code) ... -->

<form action="ProfilePage.php" method="post">
    <!-- ... (previous form fields) ... -->

    <!-- Allow users to input or edit additional attributes -->
    <label for="birthday">Birthday:</label>
    <input type="date" id="birthday" name="birthday" value="<?php echo $userData['birthday']; ?>"><br><br>

    <label for="age">Age:</label>
    <input type="number" id="age" name="age" value="<?php echo $userData['age']; ?>"><br><br>

    <label for="height">Height (cm):</label>
    <input type="number" id="height" name="height" value="<?php echo $userData['height']; ?>"><br><br>

    <label for="weight">Weight (kg):</label>
    <input type="number" id="weight" name="weight" value="<?php echo $userData['weight']; ?>"><br><br>

    <label for="school_history">School History:</label>
    <textarea id="school_history" name="school_history"><?php echo $userData['school_history']; ?></textarea><br><br>

    <input type="submit" class="btn btn-primary" name="update_profile" value="Update Profile">
</form>

