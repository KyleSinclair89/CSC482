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
<br><br><br><br><br><br><br><br><br><br><br>
<form id="uploadForm" action="upload.php" method="post" enctype="multipart/form-data">
        <input type="text" id="status" name="status" placeholder="Write your status/caption...">
        <label for="file-upload" id="uploadBtn">Upload Picture</label>
        <input type="file" id="file-upload" name="file" style="display: none;">
        <input type="submit" value="Submit">
    </form>
</body>
</html>
