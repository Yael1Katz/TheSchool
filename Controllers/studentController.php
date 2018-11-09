<?php
require_once '../Models/Student.php';
require_once '../DB/commonMethods.php';
session_start();

if ($_POST["func"] == "getAllStudents") {
        getAllStudents();
}else if ($_POST["func"] == "saveStudent") {
    $student = new Student($_POST["name"], $_POST["phone"], $_POST["email"], $_POST["image"]);
    $student->id = $_POST["id"];
    $student->courses = $_POST["courses"];
    saveStudent($student);
} else if ($_POST["func"] == "addStudent") {
    $student = new Student($_POST["name"], $_POST["phone"], $_POST["email"], $_POST["image"]);
    $student->courses = $_POST["courses"];
    addStudent($student);
    echo json_encode($student->courses);
} else if($_POST["func"] == "getStudentByID"){
    getStudentByID($_POST["id"]);
}else if ($_POST["func"] == "deleteStudent") {
    deleteStudent($_POST["id"]);
}

function getAllStudents(){
    $students = getAllStudentsDB();
    $myJson = json_encode($students);
    echo $myJson;
}

function addStudent($student) {
    echo addStudentDB($student);
}

function getStudentByID($id) {
    $student = getStudentByIDDB($id);
    $myJson = json_encode($student);
    echo $myJson;
}

function saveStudent($student) {
    echo saveStudentDB($student);
}

function deleteStudent($id) {
    deleteStudentDB($id);
}