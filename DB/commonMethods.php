<?php

function isUserValidDB($email, $password1) {
    require 'connection.php';
    $sql = "select * FROM administrators "
            . " WHERE email='$email' and password='$password1'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $administrator = new Administrator($row["name"], $row["role"], $row["phone"], $row["email"], $row["password"], $row["image"]);
        $administrator->id = $row["id"];
        mysqli_close($conn);
        return $administrator;
    }
    return null;
}

function getAllAdministratorsDB($role) {
    $administrators = array();
    require 'connection.php';
    if ($role == "manager") {
        $sql = "select * FROM administrators WHERE role != 'owner'";
    } else if ($role == "owner") {
        $sql = "select * FROM administrators";
    }
    $result = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        $administrator = new Administrator($row["name"], $row["role"], $row["phone"], $row["email"], $row["password"], $row["image"]);
        $administrator->id = $row["id"];
        array_push($administrators, $administrator);
    }
    mysqli_close($conn);
    return $administrators;
}

function saveAdministratorDB($administrator) {
    require 'connection.php';

    if ($administrator->role == "owner") {
        $sql = "select * FROM administrators WHERE role='owner' and id!=" . $administrator->id;
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) == 0) {
            $sql = "UPDATE administrators
            SET name='$administrator->name', role='$administrator->role', phone='$administrator->phone', email='$administrator->email', password='$administrator->password', image='$administrator->image'
            WHERE id=" . $administrator->id;
            $result = mysqli_query($conn, $sql);
        } else {
            echo "cannot add administrator. There is already administrator with owner role";
        }
    } else {
        $sql = "UPDATE administrators
            SET name='$administrator->name', role='$administrator->role', phone='$administrator->phone', email='$administrator->email', password='$administrator->password', image='$administrator->image'
            WHERE id=" . $administrator->id;
        $result = mysqli_query($conn, $sql);
    }
    mysqli_close($conn);
}

function saveStudentDB($student) {
    require 'connection.php';
    $sql = "UPDATE students
            SET name='$student->name', phone='$student->phone', email='$student->email', image='$student->image'
            WHERE id=" . $student->id;
    $result = mysqli_query($conn, $sql);
    if ($result) {

        $sql = "DELETE FROM course_student
        WHERE id_student=" . $student->id;
        $result = mysqli_query($conn, $sql);

        foreach ($student->courses as $courseId) {
            $sql = "INSERT INTO course_student
            (id_course, id_student) 
            VALUES ( $courseId, $student->id )";
            $result = mysqli_query($conn, $sql);
        }
    }
    mysqli_close($conn);
    return $student->id;
}

function saveCourseDB($course) {
    require 'connection.php';
    $sql = "UPDATE courses
            SET name='$course->name', description='$course->description', image='$course->image'
            WHERE id=" . $course->id;
    $result = mysqli_query($conn, $sql);
    if ($result) {

        $sql = "select * FROM course_student WHERE id_course=" . $course->id;
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                array_push($course->students, $row["id_student"]);
            }
        }
    }
    mysqli_close($conn);
    return $course;
}

function addAdministratorDB($administrator) {
    require 'connection.php';
    if ($administrator->role == "owner") {
        $sql = "select * FROM administrators WHERE role='owner'";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) == 0) {
            $sql = "INSERT INTO administrators
            (name, role, phone, email, password, image) 
            VALUES ('" . $administrator->name . "', '" . $administrator->role . "', '" . $administrator->phone . "', '" . $administrator->email . "', '" . $administrator->password . "', '" . $administrator->image . "')";
            $result = mysqli_query($conn, $sql);
        } else {
            echo "cannot add administrator. There is already administrator with owner role";
        }
    } else {
        $sql = "INSERT INTO administrators
            (name, role, phone, email, password, image) 
            VALUES ('" . $administrator->name . "', '" . $administrator->role . "', '" . $administrator->phone . "', '" . $administrator->email . "', '" . $administrator->password . "', '" . $administrator->image . "')";
        $result = mysqli_query($conn, $sql);
    }

    mysqli_close($conn);
}

function getAdministratorByIdDB($id) {
    require 'connection.php';
    $sql = "select * FROM administrators WHERE id=" . $id;
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $administrator = new Administrator($row["name"], $row["role"], $row["phone"], $row["email"], $row["password"], $row["image"]);
    $administrator->id = $row["id"];
    mysqli_close($conn);
    return $administrator;
}

function getStudentByIdDB($id) {
    require 'connection.php';
    $sql = "select * FROM students WHERE id=" . $id;
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $student = new Student($row["name"], $row["phone"], $row["email"], $row["image"]);
    $student->id = $row["id"];
    $sql = "select * FROM course_student WHERE id_student=" . $student->id;
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            array_push($student->courses, $row["id_course"]);
        }
    }

    mysqli_close($conn);
    return $student;
}

function getCourseByIdDB($id) {
    require 'connection.php';
    require '../Models/Student.php';
    $sql = "select * FROM courses WHERE id=" . $id;
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $course = new Course($row["name"], $row["description"], $row["image"]);
    $course->id = $row["id"];
    $sql = "select * FROM course_student WHERE id_course=" . $course->id;
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            array_push($course->students, $row["id_student"]);
        }
    }
    mysqli_close($conn);
    return $course;
}

function deleteAdministratorDB($id) {
    require 'connection.php';
    $sql = "DELETE FROM administrators WHERE id=" . $id;
    $result = mysqli_query($conn, $sql);
    mysqli_close($conn);
}

function deleteStudentDB($id) {
    require 'connection.php';
    $sql = "DELETE FROM students WHERE id=" . $id;
    $result = mysqli_query($conn, $sql);
    $sql = "DELETE FROM course_student WHERE id_student=" . $id;
    $result = mysqli_query($conn, $sql);
    mysqli_close($conn);
}

function deleteCourseDB($id) {
    require 'connection.php';
    $sql = "DELETE FROM courses WHERE id=" . $id;
    $result = mysqli_query($conn, $sql);
    mysqli_close($conn);
}

function getAllCoursesDB() {
    $courses = array();
    require 'connection.php';
    $sql = "select * FROM courses";
    $result = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        $course = new Course($row["name"], $row["description"], $row["image"]);
        $course->id = $row["id"];
        array_push($courses, $course);
    }
    mysqli_close($conn);
    return $courses;
}

function getAllStudentsDB() {
    $students = array();
    require 'connection.php';
    $sql = "select * FROM students";
    $result = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        $student = new Student($row["name"], $row["phone"], $row["email"], $row["image"]);
        $student->id = $row["id"];
        array_push($students, $student);
    }
    mysqli_close($conn);
    return $students;
}

function addCourseDB($course) {
    require 'connection.php';
    $sql = "INSERT INTO courses
            (name, description, image) 
            VALUES ('" . $course->name . "', '" . $course->description . "', '" . $course->image . "')";
    $result = mysqli_query($conn, $sql);
    $courseId = $conn->insert_id;
    $course->id = $courseId;
    mysqli_close($conn);
    return $course;
    //return $courseId;
}

function addStudentDB($student) {
    require 'connection.php';
    $studentId;
    $sql = "INSERT INTO students
            (name, phone, email, image) 
            VALUES ('" . $student->name . "', '" . $student->phone . "', '" . $student->email . "', '" . $student->image . "')";
    $result = mysqli_query($conn, $sql);
    //$conn->insert_id;
    if (!$result) {
        return false;
    } else {
        $studentId = $conn->insert_id;
        foreach ($student->courses as $courseId) {
            $sql = "INSERT INTO course_student
            (id_course, id_student) 
            VALUES ( $courseId, $studentId )";
            $result = mysqli_query($conn, $sql);
        }
    }
    mysqli_close($conn);
    return $studentId;
}
