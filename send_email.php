<?php
if($_SERVER["REQUEST_METHOD"] == "POST"){
  // Get the data from the form
  $name = $_POST['name'];
  $email = $_POST['email'];
  $subject = $_POST['subject'];
  $message = $_POST['message'];

  // Limit the message sent to 150 words
  $message = substr($message, 0, 150);

  // Settings for the email
  $to = "kylesinclair@mail.adelphi.edu";
  $headers = "From: $name <$email>";
  $body = "Subject: $subject \n\n$message";

  // Send the email
  if(mail($to,$subject,$body,$headers)){
    echo "Email sent successfully! Returning home..."
    //header("location: RecruitUHomePage.html");
	//				exit;
  }
  else{
    echo "Failed to send email! Please try again."
      //header("location: RecruitUContactUs.html");
	//				exit;
  }
}
?>
