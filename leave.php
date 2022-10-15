<?php

session_start();

include("nav/conn.php");

unset($_SESSION["ultimate"]);
unset($_SESSION["username"]);

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

You are now signed out.

</div>