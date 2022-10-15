<?php

session_start();

include("nav/conn.php");

$usernameErr = $passwordErr = "";
$username = $password = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (empty($_POST["username"])) {
    $usernameErr = "Userame is required";
  } else {
    $username = test_input($_POST["username"]);
    if (!preg_match("/^[a-zA-Z-' ]*$/",$username)) {
      $usernameErr = "Only letters and dashes allowed";
    }
    }
  

  if (empty($_POST["password"])) {
    $passwordErr = "Password is required";
  } else {
    $password = ($_POST["password"]);
  }
}

  
  function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

?>

<html>
<head>

<title>Access | Knife Queer Art Wiki</title>
<meta charset="UTF-8">

<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Alegreya&family=Sarabun&display=swap" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="css/rosestyle.css">

<meta name="robots" content="noindex, nofollow" />

</head><body>

<div class="verticontainer">
  
  <div class="title">Sign In</div>
  
  <div class="flatcolor login">

<?php

if (empty($_SESSION['username'])) {
    
    echo 'Please sign in to view this wiki.<br>
    Logging in will update your browser cookie.
    <br><BR>
    
    <form method="post" action="', htmlspecialchars($_SERVER["PHP_SELF"]), '">
    USERNAME<br>
    <input type="text" class="smallfield" name="username" value="',
    $username,
    '"><br><br>
    PASSWORD<br>
    <input type="password" class="smallfield" name="password" value="',
    $password,
    '">
      
    <br><br><br>
    <input type="submit" class="submitbtn" name="submit" value="SUBMIT"></input>
    
    <br><BR>';
    
$query = "SELECT * FROM daggers WHERE username = '". mysqli_real_escape_string($db, $username) ."' AND password = '". mysqli_real_escape_string($db, $password) ."'" ;
     $result = mysqli_query($db, $query);
     $rowl = mysqli_fetch_array($result);
     if ((mysqli_num_rows($result) == 1) && ($rowl['ultimate'] == TRUE)) {
        echo "Thank you, you are now logged in, ";
        $_SESSION["ultimate"]='view';
        $_SESSION["username"]= $_POST['username'];
        echo $_POST['username'], ". You may now <a href='nav/selection.php'>proceed</a>.";
      } elseif ((mysqli_num_rows($result) == 1) && ($rowl['ultimate'] == NULL)) {
        echo "This is Knife's personal wiki. You may be looking for their <a href='http://www.unrealityagents.com/'>main site</a>.";
      } elseif ((isset($_POST['username'])) && (isset($_POST['password'])) && (mysqli_num_rows($result) == 0)) {
        echo "No match found.";
      }

echo "</form>";

} else {
  echo "<a href='nav/selection.php'>Proceed to verse selection.</a><br><br>
  <a href='http://www.unrealityagents.com/'>Or return to the main site.</a>";
}

?>

</div>