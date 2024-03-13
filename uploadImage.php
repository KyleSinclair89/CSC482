<?php
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

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the file was uploaded without errors
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] == UPLOAD_ERR_OK) {
        // ImgBB API endpoint
        $imgbbApiUrl = 'https://api.imgbb.com/1/upload';

        // Prepare image data for ImgBB API
        $imageData = base64_encode(file_get_contents($_FILES["image"]["tmp_name"]));

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

        // Insert the image URL into the database
        $sql = "INSERT INTO posts (imageURL) VALUES ('$imageUrl')";

        if ($conn->query($sql) === TRUE) {
            echo "Image uploaded and added to the database successfully.";
        } else {
            echo "Error adding image to the database: " . $conn->error;
        }
    } else {
        echo "Error uploading file.";
    }
}

// Display the uploaded image if the URL is available
if (isset($imageUrl)) {
    echo "<h2>Uploaded Image</h2>";
    echo "<img src='$imageUrl' alt='Uploaded Image' style='width: 400px; height: auto; border: 10px solid white;'>";
}

// Close database connection
$conn->close();
?>
