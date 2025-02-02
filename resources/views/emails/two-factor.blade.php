<!-- resources/views/emails/two-factor.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Forget Password</title>
<style>
  body {
    font-family: 'Arial', sans-serif;
    background-color: #f4f4f9;
    color: #333;
    padding: 20px; /* Adds padding around the content */
  }

  h1 {
    color: #004a7c; /* Gives the header a distinct color */
  }

  p {
    font-size: 16px; /* Sets a readable font size for the paragraph */
    line-height: 1.5; /* Improves readability by spacing lines out */
  }

  a {
    ; /* Blue background for the link */
    color: white; /* White text for contrast */
    padding: 10px 20px; /* Padding around the text */
    text-decoration: none; /* No underline on the link */
    border-radius: 5px; /* Rounded corners for a smoother look */
    font-weight: bold; /* Makes the text bold */
    display: inline-block; /* Ensures padding and background encase the text */
  }

  a:hover {
    background-color: #9dc8f5;
    color: black/* Darker blue on hover for visual feedback */
  }
</style>
</head>
<body>
  <h1>Forget Password Email</h1>
  <p>Two Factor Authentication</p>
<p>Your two-factor authentication code is: <strong>{{ $two_factor_code }}</strong></p>
</body>
</html>
