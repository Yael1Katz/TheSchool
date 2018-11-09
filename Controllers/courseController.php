<?php

require_once '../Models/Course.php';
require_once '../DB/commonMethods.php';
session_start();

if ($_POST["func"] == "getAllCourses") {
    getAllCourses();
} else if ($_POST["func"] == "saveCourse") {
    $course = new Course($_POST["name"], $_POST["description"], $_POST["image"]);
    $course->id = $_POST["id"];
    saveCourse($course);
} else if ($_POST["func"] == "addCourse") {
    $course = new Course($_POST["name"], $_POST["description"], $_POST["image"]);
    addCourse($course);
} else if ($_POST["func"] == "getCourseByID") {
    getCourseByID($_POST["id"]);
} else if ($_POST["func"] == "deleteCourse") {
    deleteCourse($_POST["id"]);
}

function getAllCourses() {
    $courses = getAllCoursesDB();
    $myJson = json_encode($courses);
    echo $myJson;
}

function addCourse($course) {
    $myJson = json_encode(addCourseDB($course));
    echo $myJson;
    //echo addCourseDB($course);
}

function getCourseByID($id) {
    $course = getCourseByIDDB($id);
    $myJson = json_encode($course);
    echo $myJson;
}

function saveCourse($course) {
    $myJson = json_encode(saveCourseDB($course));
    echo $myJson;
    //echo saveCourseDB($course);
}

function deleteCourse($id) {
    deleteCourseDB($id);
}
