<?php

require_once("functions.php");
session_start();

if (isset($_GET['action']) && $_GET['action']) {
  switch($_GET['action']) {
    case 'seed': 
    {
      if (isset($_POST['seed']) && $_POST['seed']) {
        $_SESSION['seed'] = base64_encode($_POST['seed']);
        echo "Program was seeded successfully.<br />";
      } else {
        echo "Program was not seeded due to missing seed...<br />";
      }
      break;
    }
    case 'encrypt':
      EncryptAll($_SESSION['seed']);
      break;
    case 'decrypt':
      DecryptAll($_SESSION['seed']);
      break;
  }
}

if (isset($_SESSION['seed']) && $_SESSION['seed']) {
  echo "Session seed is set<br />";
} else {
  echo "No session seed is set<br />"; 
}
?>

<form action="manage.php?action=seed" method="POST">
  Set new encryption seed:<br>
  <input type="text" name="seed">
  <input type="submit" value="Set seed">
</form>

<form action="manage.php?action=encrypt" method="POST">
  <input type="submit" value="Encrypt">
</form>

<form action="manage.php?action=decrypt" method="POST">
  <input type="submit" value="Decrypt">
</form>
