<?php

require_once '../Models/Administrator.php';
require_once '../DB/commonMethods.php';
session_start();

if ($_POST["func"] == "getAllAdministrators") {
    if (isset($_SESSION["administrator"])) {
        getAllAdministrators();
    }
} else if ($_POST["func"] == "saveAdministrator") {
    $administrator = new Administrator($_POST["name"], $_POST["role"], $_POST["phone"], $_POST["email"], $_POST["password"], $_POST["image"]);
    $administrator->id = $_POST["id"];
    saveAdministrator($administrator);
} else if ($_POST["func"] == "addAdministrator") {
    $administrator = new Administrator($_POST["name"], $_POST["role"], $_POST["phone"], $_POST["email"], $_POST["password"], $_POST["image"]);
    addAdministrator($administrator);
} else if ($_POST["func"] == "getAdministratorByID") {
    getAdministratorByID($_POST["id"]);
} else if ($_POST["func"] == "deleteAdministrator") {
    deleteAdministrator($_POST["id"]);
} else if ($_POST["func"] == "getCurrentUser"){
    $administrator = $_SESSION["administrator"];
    $myJson = json_encode($administrator);
    echo $myJson;
}
/* if (isset($_GET["administrationTabClicked"])){
  session_start();
  $administrator = $_SESSION["administrator"];
  $administrators = getAllAdministrators();
  } */

function getAllAdministrators() {
    $administrator = $_SESSION["administrator"];

    $administrators = getAllAdministratorsDB($administrator->role);

    $myJson = json_encode($administrators);
    echo $myJson;
}

function saveAdministrator($administrator) {
    echo saveAdministratorDB($administrator);
}

function addAdministrator($administrator) {
    echo addAdministratorDB($administrator);
}

function getAdministratorByID($id) {
    $administrator = getAdministratorByIdDB($id);
    $myJson = json_encode($administrator);
    echo $myJson;
}

function deleteAdministrator($id) {
    deleteAdministratorDB($id);
}

function uploadFile() {
    $target_dir = "../Upload/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
// Check if image file is a actual image or fake image
    if (isset($_POST["submit"])) {
        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        if ($check !== false) {
            echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }
    }
}
