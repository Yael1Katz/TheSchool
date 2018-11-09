<?php
session_start();
require_once '../Models/Administrator.php';
require_once '../DB/commonMethods.php';

if(isset($_GET['logout'])){
    unset($_SESSION['logedin']);
    unset($_SESSION["administrator"]);
    header('Location: ../index.php?login');
}
else if (isUserValid($_POST['email'], $_POST['password'])) {
    $_SESSION["logedin"] = true;
    header('Location: ../index.php?school');
} else {
    header('Location: ../index.php?userIsNotValid');
}

function isUserValid($email, $password) {
    $administrator = isUserValidDB($email, $password);
    if ($administrator != null) {
        
        $_SESSION["administrator"] = $administrator;
        return true;
    }
    return false;
}
