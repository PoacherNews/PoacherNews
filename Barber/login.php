<?php
    if(!session_start()) {
      header("Location: error.php")
      exit;
    }

    $loggedin = empty($_SESSION['loggedin']) ? false : $_SESSION['loggedin'];

    if($loggedin){
      header("Location: index.php");
      exit;
    }

    $action = empty($_POST['action']) ? '' : $_POST['action'];

    if($action == 'do_login') {
      handle_login();
    }
    else {
      login_form();
    }

    function handle_login() {

      $username = empty($_POST['username']) ? '' : $_POST['username'];
      $password = empty($_POST['password']) ? '' : $_POST['password'];

      require_once 'db.php';

      if($db->connect_error) {
        $error = 'Error: ' . $db->connect_errno . ' ' . $db->connect_error;
        require "loginForm.php";
        exit;
      }

      $username = $db->real_escape_string($username);
      $password = sha1($db->real_escape_string($password));

      $query = "SELECT ID FROM users WHERE username = '$username' AND password = '$password'";

      $queryResult = $db->query($query);

      if(!$queryResult) {
        $error = 'Error: Contact system administrator';
        require 'loginForm.php';
        exit;
      }

      if($queryResult->num_rows == 1) {
        $_SESSION['loggedin'] = $username;
        header(Location: index.php);
        exit;
      }
      else {
        if($db->query("SELECT ID FROM users WHERE username = '$username'")->num_rows == 1)
          $error = "Error: Username does not exist";
        else
          $error = "Error: Incorrect password";

        require "loginForm.php";
        exit;
      }
    }
?>
