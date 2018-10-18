<?php

// taken from https://bhoover.com/using-php-openssl_encrypt-openssl_decrypt-encrypt-decrypt-data/

function Encrypt_($data, $key) {
  // Generate an initialization vector
  $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
  // Encrypt the data using AES 256 encryption in CBC mode using our encryption key and initialization vector.
  $encrypted = openssl_encrypt($data, 'aes-256-cbc', $key, 0, $iv);
  // The $iv is just as important as the key for decrypting, so save it with our encrypted data using a unique separator (::)
  return base64_encode($encrypted . '::' . $iv);
}
 
function Decrypt_($data, $key) {
  // To decrypt, split the encrypted data from our IV - our unique separator used was "::"
  list($encrypted_data, $iv) = explode('::', base64_decode($data), 2);
  return openssl_decrypt($encrypted_data, 'aes-256-cbc', $key, 0, $iv);
}

function startsWith($haystack, $needle) {
     return (substr($haystack, 0, strlen($needle)) === $needle);
}

function endsWith($haystack, $needle) {
  $length = strlen($needle);
  return ($length == 0) ? true : (substr($haystack, -$length) === $needle);
}

/*function getListOfFiles() {
  return array(
    'index.php', 'second.php',
    'index.phpe', 'second.phpe');
}*/

function EncryptAll($key) {
  if (file_exists('files/cookie')) {
    echo "Files are already enrypted - decrypt them first<br />";
    return;
  }

  $files = scandir('files/');
  //$files = getListOfFiles();
  foreach ($files as $file) {
    // encrypt only files with php extension
    if (endsWith($file, '.php')) {
      if (!file_exists('files/' . $file)) continue;
      // get content of file and encrypt it
      $content = file_get_contents("files/${file}");
      $encrypted = Encrypt_($content, $key);
      // save content and delete old .php file
      file_put_contents("files/${file}e", $encrypted);
      unlink("files/${file}");

      echo "File " . $file . " encrypted<br />";
    }
  }
  // create security file
  $secure_data = Encrypt_("YXNzZXJ0KHRydWUpOw=", $key);
  file_put_contents("files/cookie", $secure_data);
}

function DecryptAll($key) {
  // check for security cookie file
  try {
    $sec = Decrypt_(file_get_contents("files/cookie"), $key);
    $sec = base64_decode($sec);
    if (!$sec) die;
    eval($sec);
  } catch (Error $error) {
    die;
  }

  $files = scandir('files/');
  //$files = getListOfFiles();
  foreach ($files as $file) {
    if (endsWith($file, '.phpe')) {
      if (!file_exists('files/' . $file)) continue;
      // get content of encrypted file and decrypt it
      $content = file_get_contents("files/${file}");
      $decrypted = Decrypt_($content, $key);
      // save content and delete old .phpe file
      $new_name = substr($file, 0, -1);
      file_put_contents("files/${new_name}", $decrypted);
      unlink("files/${file}");

      echo "File " . $file . " decrypted<br />";
    }
  }
  unlink("files/cookie");
}
