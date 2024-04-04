<?php
require_once "config.php";
require_once "session.php";

$id = $_SESSION["userid"];

// ImgBB API Key
$imgbbApiKey = '0ed2ddbf017c95f36e022a65d5546d65';

// Database Connection
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'accounts';

$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to get user details based on user ID
function getUserDetails($userId, $conn) {
    $query = "SELECT first_name, last_name, user_type FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
    return $user;
}

// Function to display all posts
function displayAllPosts($conn) {
    $query = "SELECT post_id, user_id, caption, imageURL FROM posts ORDER BY post_id DESC";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        while ($post = $result->fetch_assoc()) {
            $user = getUserDetails($post['user_id'], $conn);
            ?>
            <div class="post-container">
                <div class="post-content">
                    <h3><?php echo $user['first_name'] . ' ' . $user['last_name']; ?></h3>
                    <p>User Type: <?php echo $user['user_type']; ?></p>
                    <p><?php echo $post['caption']; ?></p>
                    <img src="<?php echo $post['imageURL']; ?>" alt="Post Image">
                </div>
            </div>
            <?php
        }
    } else {
        echo "No posts found.";
    }
}

// Additional code for handling form submissions
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Check if the form is submitted

// Check if the file was uploaded without errors
    //if (isset($_FILES["file"]) && $_FILES["file"]["error"] == UPLOAD_ERR_OK) {
        // ImgBB API endpoint
        $imgbbApiUrl = 'https://api.imgbb.com/1/upload';

        // Prepare image data for ImgBB API
        $imageData = base64_encode(file_get_contents($_FILES["file"]["tmp_name"]));

        // Prepare data for the API request
        $postData = http_build_query([
            'key' => $imgbbApiKey,
            'image' => $imageData,
        ]);

        // Set up options for the API request
        $options = [
            'http' => [
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => $postData,
            ],
        ];

        // Create context for the API request
        $context = stream_context_create($options);

        // Send request to ImgBB API
        $response = file_get_contents($imgbbApiUrl, false, $context);

        // Decode JSON response
        $imgbbData = json_decode($response, true);

        // Get the image URL from ImgBB response
        $imageUrl = $imgbbData['data']['url'];

        // Check if a status is set and not empty
        $status = isset($_POST["status"]) ? $_POST["status"] : '';

        // Insert the image URL and status into the database
        $query = "INSERT INTO posts (user_id, caption, imageURL) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iss", $_SESSION["userid"], $status, $imageUrl);
        $stmt->execute();
        $stmt->close();

       // echo "Image uploaded and added to the database successfully.";
    //} else {
       // echo "Error uploading file.";
   // }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" type="text/css" href="RecruitUHomePageStyle.css">
<title>RecruitU | Home</title>
<style>
    body {
        margin: 0;
        padding: 0;
        position: relative;
    }
    #logo {
        position: absolute;
        top: 35px;
        left: 50px;
        width: 200px; /* Adjust width as needed */
        height: auto; /* Maintain aspect ratio */
    }

</style>
</head>
<body>
    <img id="logo" src="RecruitULogo.png" alt="RecruitU Logo">
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
<br><br><br><br><br><br><br><br><br><br><br>
<form id="uploadForm" action="RecruitUHomePage.php" method="post" enctype="multipart/form-data" style="position: relative;">
    <input type="text" id="status" name="status" placeholder="Write your status/caption..." style="margin-left: 10px; position: absolute; top: 0; left: 0;">
    <label for="file-upload" id="uploadBtn" style="background-color: #af0a06; color: white; padding: 10px; text-decoration: none; border-radius: 5px; border: 2.5px solid black; box-shadow: 0 0 10px rgba(0, 0, 0, 0.3); transition: box-shadow 0.3s, background-color 0.3s, color 0.3s; top: 0; right: 0; z-index: 1; margin-left: 370px;" onmouseover="this.style.backgroundColor='#000'; this.style.color='#EE2737';" onmouseout="this.style.backgroundColor='#af0a06'; this.style.color='white'; position: relative;">Upload Picture</label>

    <input type="file" id="file-upload" name="file" style="display: none;">
<input type="submit" id="submitButton" style="background-color: #af0a06; color: white; padding: 10px; text-decoration: none; border-radius: 5px; border: 2.5px solid black; box-shadow: 0 0 10px rgba(0, 0, 0, 0.3); transition: box-shadow 0.3s, background-color 0.3s, color 0.3s; top: 0; right: 0; z-index: 1; margin-top: 50px; cursor: pointer;" onmouseover="this.style.backgroundColor='#000'; this.style.color='#EE2737';" onmouseout="this.style.backgroundColor='#af0a06'; this.style.color='white';"></label>
</form>
<br>
<?php
    // Call the function to display all posts
    displayAllPosts($conn);
	$conn -> close();
    ?>

</body>
</html>



