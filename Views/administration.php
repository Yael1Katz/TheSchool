<!--<div class="col-sm-2 sidenav">
    <div>
        <h4>Administrators
            <a rel="no-refresh" href="#" onclick="addEditAdministrator(this, 'add')">
                <span class="glyphicon glyphicon-plus-sign"></span>
            </a>
            <hr class="hr">
        </h4>
    </div>
    <div id="administratorsList">
    </div>
</div>
<div class="main-container col-sm-10">
</div>-->


<script>
    var currentUser;
    getAllAdministrators();
    getCurrentUser();
    function getCurrentUser() {
        $.post("../../Controllers/administrationController.php", {func: "getCurrentUser"}, function (data) {
            debugger;
            currentUser = JSON.parse(data);
        });
    }
    function getAllAdministrators() {
        $.post("../../Controllers/administrationController.php", {func: "getAllAdministrators"}, function (data) {
            debugger;
            var administrators = JSON.parse(data);
            var str = `<div class="col-sm-2 sidenav">
             <div>
             <h4>Administrators</small</h4>
             <a rel='no-refresh' href="#" onclick="addEditAdministrator(this,'add')">
             <span class="glyphicon glyphicon-plus-sign"></span>
             </a>
             <hr class="hr">
             </div>
             <div id="administratorsList">
             </div>
             </div>
             <div class="main-container col-sm-10">
                 Total ${administrators.length} administrators
             </div>`;

            $('.content')[0].innerHTML = "";
            $('.content')[0].innerHTML = str;
            $.each(administrators, function (index, administrator) {
                var bussinessCard = `<div class="business-card" onclick="addEditAdministrator(this, 'edit')">
                                                <div class="media">
                                                    <div class="media-left">
                                                        <img class="media-object img-circle profile-img" src="Upload\\` + administrator.image + `">
                                                    </div>
                                                    <div class="media-body">
                                                        <div class="id">${administrator.id}</div>
                                                        <div class="details left">${administrator.name}</div>
                                                        <div class="details left">,&nbsp</div>
                                                        <div class="details">${administrator.role}</div>
                                                        <div class="details">${administrator.phone}</div>
                                                        <a style="font-size: 13px;" href="#" rel="no-refresh">${administrator.email}</a>
                                                    </div>
                                                </div>
                                            </div>`;
                $('#administratorsList').append(bussinessCard);
            });
        });

    }
    function addEditAdministrator(selected, action) {
        if (action == "edit") {
            var details = selected.children[0].children[1].children;
            var id = details[0].innerHTML;
            $.post("../../Controllers/administrationController.php", {id: id, func: "getAdministratorByID"}, function (data) {
                var administrator = JSON.parse(data);
                createForm(action, id, administrator.name, administrator.phone, administrator.email, administrator.role, administrator.password, administrator.image);
            });
        } else if (action == "add") {
            createForm(action);
        }
    }

    function createForm(action, id = "", name = "", phone = "", email = "", role = "Choose...", password = "", image = "default.png") {
        var str = `<div class="col-sm-9">
                    <form>
                        <div class="form-group row">
                            <label for="colFormLabelSm" class="col-sm-1 col-form-label col-form-label-sm">Name:</label>
                            <div class="col-sm-11">
                                <input type="text" class="form-control form-control-sm" id="name" name="name" placeholder="Name..." value=` + name + `>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="colFormLabel" class="col-sm-1 col-form-label">Phone:</label>
                            <div class="col-sm-11">
                                <input type="text" class="form-control" id="phone" name="phone" placeholder="Phone..." value=${phone}>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="colFormLabelLg" class="col-sm-1 col-form-label col-form-label-lg">Email:</label>
                            <div class="col-sm-11">
                                <input type="email" class="form-control form-control-lg" id="email" name="email" placeholder="Email..." value=${email}>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="colFormLabelLg" class="col-sm-1 col-form-label col-form-label-lg">Password:</label>
                            <div class="col-sm-11">
                                <input type="password" name="password" class="form-control form-control-lg" id="password" placeholder="Password..." value=${password}>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputState" class="col-sm-1 col-form-label col-form-label-lg">Role:</label>
                            <div class="col-sm-11">
                                <select name="role" id="role" class="form-control form-control-lg">`

        if (currentUser.role == "manager" && currentUser.email == email) {
            str += `<option value="manager">manager</option>`;
        } else {
            str += `<option value="sales">sales</option>
                                            <option value="manager">manager</option>`;
        }

        if (currentUser.role == "owner") {
            str += `<option value="owner">owner</option>`;
        }
        str += `</select>
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
                        <input class="btn btn-primary" type="submit" value="Save" onclick="saveOrAddAdministrator();return false;"/>`;
        if (action == "edit" && (currentUser.role == "owner" || (currentUser.role == "manager" && currentUser.email != email))) {
            str += '<input style="margin-left:10px" class="btn btn-warning" type="submit" value="Delete" onclick="deleteAdministrator();return false;"/>';
        }
        str += '</form></div>';
        $('.main-container')[0].innerHTML = str;
        $('#role').val(role);
    }

    function saveOrAddAdministrator() {
        var id = $('#id').html();
        var name = $('#name').val();
        var phone = $('#phone').val();
        var email = $('#email').val();
        var role = $('#role').val();
        var password = $('#password').val();

        debugger;
        var file_data = $('#image').prop('files')[0];
        var image = file_data !== undefined ? file_data.name : $('#blah').attr('src').includes("default.png") ? "default.png" : $('#blah').attr('src').split('\\')[1];

        $.post("../../Controllers/administrationController.php", {id: id, name: name, phone: phone, email: email, role: role, password: password, image: image, func: (id == "") ? "addAdministrator" : "saveAdministrator"}, function (data) {
            debugger;
            if (data == "") {
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
                            getAllAdministrators();
                        }
                    });
                } else {
                    getAllAdministrators();
                }
            } else {
                alert(data);
            }

        });
    }

    function deleteAdministrator() {
        if (confirm('Are you sure you want to delete this administrator?')) {
            var id = $('#id').html();
            $.post("../../Controllers/administrationController.php", {id: id, func: "deleteAdministrator"}, function (data) {
                debugger;
                getAllAdministrators();
            });
        } else {
            // Do nothing!
        }
    }
</script>


