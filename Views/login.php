
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="panel panel-login">
            <div class="panel-heading">
                <div class="row">
                    <h1>Login</h1>
                </div>
                <hr>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12">
                        <form id="login-form" action="Controllers/loginController.php" method="post" role="form">
                            <div class="form-group">
                                <input type="email" name="email" id="email" tabindex="1" class="form-control" placeholder="Username" value="" required>
                            </div>
                            <div class="form-group">
                                <input type="password" name="password" id="password" tabindex="2" class="form-control" placeholder="Password" required>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-6 col-sm-offset-3">
                                        <input type="submit" name="login-submit" id="login-submit" tabindex="4" class="form-control btn btn-login" value="Log In">
                                    </div>
                                </div>
                            </div>
                            <div class="alert alert-danger alert-dismissible fade in" id="errorLogin">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                <strong>Error!</strong> Username or password is incorrect.
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>












