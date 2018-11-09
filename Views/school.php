<div class="col-sm-4 sidenav">
    <div class="col-sm-6">
        <h4>Courses</small</h4>
        <?php
        if (isset($_SESSION["administrator"]) && ($_SESSION["administrator"]->role == "owner" || $_SESSION["administrator"]->role == "manager")) {
            echo '<a rel="no-refresh" href="#" onclick=addEditCourse(this,"add")>
                        <span class="glyphicon glyphicon-plus-sign"></span>
                    </a>';
        }
        ?>
        <hr class="hr">
        <div id="coursesList">
        </div>
    </div>
    <div class="col-sm-6">
        <h4>Students</</h4>
        <a rel='no-refresh' href="#" onclick="addEditStudent(this, 'add')">
            <span class="glyphicon glyphicon-plus-sign"></span>
        </a>
        <hr class="hr">
        <div id="studentsList">
        </div>
    </div>
</div>
<div class="main-container col-sm-8">

</div>

<script>
    var currentUser;
    var students = [];
    var courses = [];
    getAllCourses();
    getAllStudents();
    setTimeout(setMainEnterScreen, 500);
    getCurrentUser();

    function getCurrentUser() {
        $.post("../../Controllers/administrationController.php", {func: "getCurrentUser"}, function (data) {
            currentUser = JSON.parse(data);
        });
    }

    function getAllCourses() {
        $.post("../../Controllers/courseController.php", {func: "getAllCourses"}, function (data) {
            //allCoursesArr = [];
            if (data != "") {
                courses = JSON.parse(data);
                $('#coursesList').html("");
                $.each(courses, function (index, course) {
                    var bussinessCard = `<div class="business-card" onclick="addEditCourse(this, 'edit')">
                                    <div class="media">
                                        <div class="media-left">
                                            <img class="media-object img-circle profile-img" src="Upload\\${course.image}">
                                        </div>
                                        <div class="media-body">
                                            <div class="id">${course.id}</div>
                                            <div class="details left">${course.name}</div>
                                        </div>
                                    </div>
                                </div>`;
                    $('#coursesList').append(bussinessCard);
                    //allCoursesArr.push({id: course.id, name: course.name, image: course.image});
                });
            }
            //setMainEnterScreen();
        });
    }

    function getAllStudents() {
        $.post("../../Controllers/studentController.php", {func: "getAllStudents"}, function (data) {
            allStudentsArr = [];
            if (data != "") {
                students = JSON.parse(data);
                $('#studentsList').html("");
                $.each(students, function (index, student) {
                    var bussinessCard = `<div class="business-card" onclick="addEditStudent(this, 'edit')">
                                                <div class="media">
                                                    <div class="media-left">
                                                        <img class="media-object img-circle profile-img" src="Upload\\${student.image}">
                                                    </div>
                                                    <div class="media-body">
                                                        <div class="id">${student.id}</div>
                                                        <div class="details">${student.name}</div>
                                                        <div class="details">${student.phone}</div>
                                                    </div>
                                                </div>
                                            </div>`;
                    $('#studentsList').append(bussinessCard);
                    allStudentsArr.push({id: student.id, name: student.name, image: student.image});
                });
            }
            //setMainEnterScreen();
        });
    }


    function setMainEnterScreen() {
        $('.main-container')[0].innerHTML = students.length + " students, " + courses.length + " courses...";
    }
    function addEditCourse(selected, action) {
        if (action == "edit") {
            var details = selected.children[0].children[1].children;
            var id = details[0].innerHTML;
            $.post("../../Controllers/courseController.php", {id: id, func: "getCourseByID"}, function (data) {
                var course = JSON.parse(data);
                createFormCourseDetails(id, course.name, course.description, course.image, course.students);
            });
        } else if (action == "add") {
            createFormCourse('add');
        }
    }

    function addEditStudent(selected, action) {
        if (action == "edit") {
            var details = selected.children[0].children[1].children;
            var id = details[0].innerHTML;
            $.post("../../Controllers/studentController.php", {id: id, func: "getStudentByID"}, function (data) {
                console.log("getStudentByID data : " + data);
                var student = JSON.parse(data);
                createFormStudentDetails(id, student.name, student.phone, student.email, student.image, student.courses);
            });
        } else if (action == "add") {
            createFormStudent('add');
        }
    }

    function createFormCourse(action, id = "", name = "", description = "", image = "course_default.png", students = []) {
        var str = `<div class="col-sm-9">
                    <form>
                        <div class="form-group row">
                            <label for="colFormLabelSm" class="col-sm-1 col-form-label col-form-label-sm">Name:</label>
                            <div class="col-sm-11">
                                <input type="text" class="form-control form-control-sm" id="name" name="name" placeholder="Name..." value=${name}>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="colFormLabel" class="col-sm-2 col-form-label">Description:</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" id="description" name="description" placeholder="Description...">${description}</textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="colFormLabelLg" class="col-sm-1 col-form-label col-form-label-lg">Image: </label>
                            <div class="col-sm-11">
                                <input type="file" id="image" name="image" onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])">                
                                <img id="blah" alt="your image" width="100" height="100" src="Upload\\${image}"/>
                            </div>
                        </div>  
                        <div class="id" id=id name="id">${id}</div>
                         <input class="btn btn-primary" type="button" value="Save" onclick="saveOrAddCourse()"/>`;
        if (students.length == 0) {
            str += `<input style="margin-left:10px" class="btn btn-warning" type="button" value="Delete" onclick="deleteCourse()"/>`;
        }
        str += `<h5> Total ` + students.length + ` students taking this course</h5>
        
                    </form></div>'`;
        $('.main-container')[0].innerHTML = str;
    }

    function createFormStudent(action, id = "", name = "", phone = "", email = "", image = "student_default.png", selectedCourses = []) {
        var str = `<div class="col-sm-9">
                    <form>
                        <div class="form-group row">
                            <label for="colFormLabelSm" class="col-sm-1 col-form-label col-form-label-sm">Name:</label>
                            <div class="col-sm-11">
                                <input type="text" class="form-control form-control-sm" id="name" name="name" placeholder="Name..." value=${name}>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="colFormLabel" class="col-sm-1 col-form-label">Phone:</label>
                            <div class="col-sm-11">
                                <input type="text" class="form-control form-control-sm" id="phone" name="name" placeholder="Phone..." value=${phone}>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="colFormLabelLg" class="col-sm-1 col-form-label col-form-label-lg">Email:</label>
                            <div class="col-sm-11">
                                <input type="email" class="form-control form-control-lg" id="email" name="email" placeholder="Email..." value=${email}>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="colFormLabelLg" class="col-sm-1 col-form-label col-form-label-lg">Image: </label>
                            <div class="col-sm-11">
                                <input type="file" id="image" name="image" onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])">                
                                <img id="blah" alt="your image" width="100" height="100" src="Upload\\${image}"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="colFormLabelLg" class="col-sm-2 col-form-label col-form-label-lg">Courses: </label>
                            <div class="col-sm-10">
                                <div id="studentCoursesList" class="flex-container">`;
        str += getCoursesList(selectedCourses);
        str +=
                `</div>
                            </div>
                        </div>`;
        str += `<div class="id" id=id name="id">${id}</div>
                        <input class="btn btn-primary" type="button" value="Save" onclick="saveOrAddStudent();"/>`;
        if (action == "edit") {
            str += '<input style="margin-left:10px" class="btn btn-warning" type="button" value="Delete" onclick="deleteStudent();"/>';
        }
        str += '</form></div>';
        $('.main-container')[0].innerHTML = str;
    }

    function createFormStudentDetails(id = "", name = "", phone = "", email = "", image = "student_default.png", selectedCourses = []) {
        var str = `<div class="business-card">
                    <div class="media">
                        <div class="media-left">
                            <img class="media-object img-circle profile-img" src="Upload\\${image}">
                        </div>
                        <div class="media-body">
                            <div>${name}</div>
                            <div>${phone}</div>
                            <a style="font-size: 13px;" href="#" rel="no-refresh">${email}</a>
                        </div>
                    </div>
                </div>
                <div id="studentCoursesList" class="flex-container">`;
        str += getSelectedCoursesListView(selectedCourses);
        str += `</div>
                 <div class="id" id=id name="id">${id}</div>
                 <input class="btn btn-primary" type="button" value="Edit" onclick="createFormStudent('edit',${id}, '${name}', '${phone}', '${email}', '${image}', [${selectedCourses}]);"/>`;
        str += '</form></div>';
        $('.main-container')[0].innerHTML = str;
    }

    function createFormCourseDetails(id = "", name = "", description = "", image = "course_default.png", students = []) {
        var str = `<div class="business-card">
                    <div class="media">
                        <div class="media-left">
                            <img class="media-object img-circle profile-img" src="Upload\\${image}">
                        </div>
                        <div class="media-body">
                            <div>${name}, ${students.length} students</div>
                            <div>${description}</div>
                        </div>
                    </div>
                </div>
                <div class="flex-container">`;
        str += getStudentsInCourseListView(students);
        str += `</div>`;
        str += `<div class="id" id=id name="id">${id}</div>`;
        if (currentUser.role == "owner" || currentUser.role == "manager")
        {
            str += `<input class="btn btn-primary" type="button" value="Edit" onclick="createFormCourse('edit',${id}, '${name}', '${description}', '${image}', [${students}]);"/>`;
        }
        str += '</form></div>';
        $('.main-container')[0].innerHTML = str;
    }

    function saveOrAddCourse() {
        var id = $('#id').html();
        var name = $('#name').val();
        var description = $('#description').val();
        var file_data = $('#image').prop('files')[0];
        var image = file_data !== undefined ? file_data.name : $('#blah').attr('src').includes("default.png") ? "course_default.png" : $('#blah').attr('src').split('\\')[1];
        $.post("../../Controllers/courseController.php", {id: id, name: name, description: description, image: image, func: (id == "") ? "addCourse" : "saveCourse"}, function (data) {
            console.log("addCourse/saveCourse data: " + data);
            data = JSON.parse(data);
            if (file_data !== undefined) {
                var form_data = new FormData();
                form_data.append('fileToUpload', file_data);
                $.ajax({
                    url: 'upload.php', // point to server-side PHP script 
                    dataType: 'text', // what to expect back from the PHP script, if anything
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: form_data,
                    type: 'post',
                    success: function (php_script_response) {
                        console.log(php_script_response);
                        getAllCourses();
                        createFormCourseDetails(data.id, name, description, image, data.students);
                    }
                });
            } else {
                getAllCourses();
                createFormCourseDetails(data.id, name, description, image, data.students);
            }

        });
    }

    function saveOrAddStudent() {
        var id = $('#id').html();
        var name = $('#name').val();
        var phone = $('#phone').val();
        var email = $('#email').val();
        var selectedCourses = getSelectedCourses();
        var file_data = $('#image').prop('files')[0];
        var image = file_data !== undefined ? file_data.name : $('#blah').attr('src').includes("default.png") ? "student_default.png" : $('#blah').attr('src').split('\\')[1];
        $.post("../../Controllers/studentController.php", {id: id, name: name, phone: phone, email: email, image: image, courses: selectedCourses, func: (id == "") ? "addStudent" : "saveStudent"}, function (data) {
            console.log("saveOrAddStudent : " + data);
            if (file_data !== undefined) {
                var form_data = new FormData();
                form_data.append('fileToUpload', file_data);
                $.ajax({
                    url: 'upload.php', // point to server-side PHP script 
                    dataType: 'text', // what to expect back from the PHP script, if anything
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: form_data,
                    type: 'post',
                    success: function (php_script_response) {
                        console.log(php_script_response);
                        getAllStudents();
                        createFormStudentDetails(data, name, phone, email, image, selectedCourses);
                    }
                });
            } else {
                getAllStudents();
                createFormStudentDetails(data, name, phone, email, image, selectedCourses);
            }
        });
    }

    //function refresh(callback, data, name, phone, email, image, selectedCourses) {
    //    getAllStudents();
    //    callback(data, name, phone, email, image, selectedCourses);
    //}

    function deleteStudent() {
        if (confirm('Are you sure you want to delete this student?')) {
            var id = $('#id').html();
            debugger;
            $.post("../../Controllers/studentController.php", {id: id, func: "deleteStudent"}, function (data) {
                getAllStudents();
                setTimeout(setMainEnterScreen, 500);
            });
        } else {
            // Do nothing!
        }
    }

    function deleteCourse() {
        if (confirm('Are you sure you want to delete this course?')) {
            var id = $('#id').html();
            $.post("../../Controllers/courseController.php", {id: id, func: "deleteCourse"}, function (data) {
                getAllCourses();
                setTimeout(setMainEnterScreen, 500);
            });
        } else {
            // Do nothing!
        }
    }

    function getCoursesList(selectedCourses) {
        var str = "";
        $.each(courses, function (index, course) {
            str += '<label><input type="checkbox" value="' + course.id + '"' + ($.inArray(parseInt(course.id), selectedCourses) !== -1 ? " checked" : "") + '/>' + course.name + '</label>';
        });
        return str;
    }

    function getSelectedCoursesListView(selectedCourses) {
        var str = "";
        $.each(courses, function (index, course) {
            str += $.inArray(course.id, selectedCourses) !== -1 ? "<label><img style='border-radius: 50%;width:40px'src='Upload\\" + course.image + "'/>" + course.name + "</label>" : "";
        });
        return str;
    }

    function getSelectedCourses() {
        var selectedCoursesIds = [];
        var courses = $('#studentCoursesList');
        $.each(courses.children(), function (index, course) {
            if (course.children[0].checked) {
                selectedCoursesIds.push(course.children[0].value);
            }
        });
        return selectedCoursesIds;
    }

    function getStudentsInCourseListView(studentsInCourse) {
        var str = "";
        $.each(students, function (index, student) {
            if ($.inArray(student.id, studentsInCourse) !== -1) {
                str += `<div class="business-card col-sm-3">
                        <div class="media" style="padding:0px;">
                            <div class="media-left" style="padding:0px;">
                                <img class="media-object img-circle profile-img" src="Upload\\${student.image}">
                            </div>
                            <div class="media-body" style="padding:0px;">
                                <div>${student.name}</div>
                            </div>
                        </div>
                    </div>`;
            }
        });
        return str;
    }
</script>