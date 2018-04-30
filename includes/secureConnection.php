<?php
  function checkHTTPS() {

    if(!empty($_SERVER['HTTPS']))
      if($_SERVER['HTTPS'] !== 'off')
        return true;
      else
        return false;
    else
      if($_SERVER['SERVER_PORT'] == 443)
        return true;
      else
        return false;
  }

  if(!checkHTTPS) {
    $redirectURL = 'https://' . $_SERVER['HTTPS_HOST'] . $_SERVER['REQUEST_URI'];
    header("Location: $redirectURL");
    exit;
  }
  
?>
