<?php
require 'Models/Administrator.php';
session_start();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Bootstrap Example</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <style>

            .alert {
                padding: 20px;
                background-color: #f44336;
                color: white;
            }

            .closebtn {
                margin-left: 15px;
                color: white;
                font-weight: bold;
                float: right;
                font-size: 22px;
                line-height: 20px;
                cursor: pointer;
                transition: 0.3s;
            }

            .closebtn:hover {
                color: black;
            }
            .flex-container {
                display: flex;
                /*flex-direction: column;*/
                flex-wrap: wrap;
            }
            .flex-container * {
                padding: 10px;
            }
            .left {
                float: left;
            }

            .business-card {
                border: 1px solid #cccccc;
                background: #f8f8f8;
                padding: 10px;
                border-radius: 4px;
                margin-bottom: 10px;
            }

            .profile-img {
                height: 70px;
                background: white;
            }

            .details {
                color: #666666;
                font-size: 13px;
                margin-bottom: 5px;
            }

            .id{
                display: none;
            }
            /* Set height of the grid so .sidenav can be 100% (adjust if needed) */
            .content {height:100vh;}

            /* Set gray background color and 100% height */
            .sidenav {
                background-color: #f1f1f1;
                height: 100%;
                overflow-y: scroll;
            }
            #administratorsList{

            }

            .hr{
                border-top: 1px solid gray;
                margin-top: 0px;
            }
            /* On small screens, set height to 'auto' for sidenav and grid */
            @media screen and (max-width: 767px) {
                .sidenav {
                    height: auto;
                    padding: 15px;
                }
                content {height: auto;} 
            }
            /*login*/
            .panel-login {
                border-color: #ccc;
                -webkit-box-shadow: 0px 2px 3px 0px rgba(0,0,0,0.2);
                -moz-box-shadow: 0px 2px 3px 0px rgba(0,0,0,0.2);
                box-shadow: 0px 2px 3px 0px rgba(0,0,0,0.2);
            }
            .panel-login>.panel-heading {
                color: #00415d;
                background-color: #fff;
                border-color: #fff;
                text-align:center;
            }

            .btn-login {
                background-color: #59B2E0;
                outline: none;
                color: #fff;
                font-size: 14px;
                height: auto;
                font-weight: normal;
                padding: 14px 0;
                text-transform: uppercase;
                border-color: #59B2E6;
            }
            .btn-login:hover,
            .btn-login:focus {
                color: #fff;
                background-color: #53A3CD;
                border-color: #53A3CD;
            }

            #errorLogin{
                display: none;
            }
        </style>
    </head>

    <body>

        <div class="container-fluid">

            <nav class="navbar navbar-inverse">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>                        
                    </button>
                    <img width='70' src="../../Views/Images/logo.png"/>
                </div>
                <div class="collapse navbar-collapse" id="myNavbar">
                    <?php
                    if (isset($_SESSION["logedin"]) && $_SESSION["logedin"] == "true") {
                        echo '<ul class="nav navbar-nav">';
                        echo '<li ><a href="?school" rel="No-Refresh">School</a></li>';
                        $administrator = $_SESSION["administrator"];
                        if ($administrator->role != "sales") {
                            echo '<li><a href="?administration" rel="No-Refresh">Administration</a></li>';
                            //echo '<li><a href="/administration.php">Administrator</a></li>';
                        }
                        echo '</ul>';
                        echo '<ul class="nav navbar-nav navbar-right">';
                        echo '<li>
                                <div>
                                    
                                    <img width="70" src="Upload\\' . $administrator->image . '"/>
                                    <h5 style="float: left;color:#9d9d9d;margin-right:10px;padding-top: 8px;">' . $administrator->name . '<div id="roleLogdin">' . $administrator->role . '</div></h5>
                                </div>
                              </li>';
                        echo '<li><a href="Controllers/loginController.php?logout" rel="No-Refresh"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>';
                        echo '</ul>';
                    }
                    ?>
                </div>
            </nav>
            <div class="content">
                <?php
                // Grabs the URI and breaks it apart in case we have querystring stuff
                $request_uri = explode('?', $_SERVER['REQUEST_URI'], 2);

                //echo $request_uri[0];
                // Route it up!
                if (isset($_SESSION["logedin"]) && $_SESSION["logedin"] == "true") {
                    if (isset($request_uri[1]) && $request_uri[1] == "administration") {
                        require 'Views/administration.php';
                        //header('Location: index.php');
                    } else if (isset($request_uri[1]) && $request_uri[1] == "school") {
                        require 'Views/school.php';
                        //} else if (isset($request_uri[1]) && $request_uri[1] == "login") {
                        //    require 'Views/login.php';
                        //} else if (isset($request_uri[1]) && $request_uri[1] == "userIsNotValid") {
                        //    require 'Views/login.php';
                    } else {
                        //require 'Views/login.php';
                        require 'Views/school.php';
                    }
                } else {
                    require 'Views/login.php';
                    if (isset($request_uri[1]) && $request_uri[1] == "userIsNotValid") {
                        echo '<div class="alert">
                                <span class="closebtn" onclick=this.parentElement.style.display="none";>&times;</span> 
                                <strong>Error!</strong> Username or password is incorrect!
                              </div>';
                    }
                    //header('Location: /login');
                }


                /* if (count($request_uri) == 1) {
                  require 'login.php';
                  } else {
                  echo $request_uri[1];
                  switch ($request_uri[1]) {
                  // Home page
                  case 'logedin':

                  //session_start();
                  $administrator = $_SESSION["administrator"];
                  require 'school.php';
                  //header('Location: ../../Views/index.php');
                  break;
                  case '/Views/inedx.php':
                  require 'administration.php';
                  break;
                  case '/Views/inedx.php':
                  require 'school.php';
                  break;
                  default:
                  require './views/404.php';
                  break;
                  }
                  } */
                ?>
            </div>
        </div>
        <script>

        </script>

    </body>
</html>