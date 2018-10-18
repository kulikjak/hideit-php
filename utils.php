<?php

require_once("functions.php");

// simple function to include results of other files
function HIDEIT_REQUIRE($file) {
  return HIDEIT_EVAL($file);
}

// simple function to get href link
function HIDEIT_HREF($link) {
  return "run.php?page=$link";
}

function HIDEIT_EVAL($file) {
  // try to handle the file in not encrypted mode
  if (file_exists("files/${file}.php")) {

    // read and evaluate given file
    $content = file_get_contents("files/${file}.php");

    // ommit opening php tag if one is preset
    if (startsWith($content, "<?php"))
      $content = substr($content, 5);
    return eval($content);
  }

  // try to run in encrypted mode
  if (file_exists("files/${file}.phpe")) {

    // check and read decryption session
    if (!(isset($_SESSION['seed']) && $_SESSION['seed'])) die;

    // load whole file into the memory
    $content = file_get_contents("files/${file}.phpe");
    if (!$content) die;

    // decrypt and evaluate given file
    try {
      $decrypted = Decrypt_($content, $_SESSION['seed']);
      // ommit opening php tag if one is preset
      if (startsWith($decrypted, "<?php"))
        $decrypted = substr($decrypted, 5);
      return eval($decrypted);
    } catch (Error $error) {
      die;
    }
  }
  die;
}
