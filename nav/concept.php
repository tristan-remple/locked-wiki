<?php

session_start();

if (empty($_SESSION["ultimate"])) {
  header("location:../index.php");
}

include("conn.php");

  $errmsg = 'This page is still under construction.';
  include 'error.php';