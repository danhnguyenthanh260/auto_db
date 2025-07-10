<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "pdo.php";
session_start();
$salt = 'XyZzy12*_';
$stored_hash = '1a52e17fa899cf40fb04cfc42e6352f1'; // password is php123
$failure = false;

if (isset($_POST['cancel'])) {
    header("Location: index.php");
    return;
}

if (isset($_POST['who']) && isset($_POST['pass'])) {
    if (strlen($_POST['who']) < 1 || strlen($_POST['pass']) < 1) {
        $failure = "Email and password are required";
    } elseif (strpos($_POST['who'], '@') === false) {
        $failure = "Email must have an at-sign (@)";
    } else {
        $check = hash('md5', $salt . $_POST['pass']);
        if ($check == $stored_hash) {
            error_log("Login success " . $_POST['who']);
            header("Location: autos.php?name=" . urlencode($_POST['who']));
            return;
        } else {
            error_log("Login fail " . $_POST['who'] . " $check");
            $failure = "Incorrect password";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>8e72af4b Danh Nguyễn - Login</title>
</head>
<body>
<h1>Please Log In</h1>
<?php
if ($failure !== false) {
    echo('<p style="color: red;">' . htmlentities($failure) . "</p>\n");
}
?>
<form method="POST">
  Email <input type="text" name="who"><br/>
  Password <input type="text" name="pass"><br/>
  <input type="submit" value="Log In">
  <input type="submit" name="cancel" value="Cancel">
</form>
</body>
</html>