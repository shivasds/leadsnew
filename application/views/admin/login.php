<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Vineeth N Krishnan">
    <meta name="description" content="Login page for fullbasket property" />

    <!-- SEO Meta Start-->

    <!-- Schema.org markup for Google+ -->
    <meta itemprop="name" content="Login">
    <meta itemprop="description" content="This is the page description">
    <meta itemprop="image" content="http://www.example.com/image.jpg">

    <!-- Twitter Card data -->
    <meta name="twitter:card" content="product">
    <meta name="twitter:site" content="@way2vineeth">
    <meta name="twitter:title" content="Page Title">
    <meta name="twitter:description" content="Page description less than 200 characters">
    <meta name="twitter:creator" content="@way2vineeth">
    <meta name="twitter:image" content="http://www.example.com/image.jpg">
    <meta name="twitter:data1" content="">
    <meta name="twitter:label1" content="Price">
    <meta name="twitter:data2" content="Black">
    <meta name="twitter:label2" content="Color">

    <!-- Open Graph data -->
    <meta property="og:title" content="Title Here" />
    <meta property="og:type" content="article" />
    <meta property="og:url" content="http://www.example.com/" />
    <meta property="og:image" content="http://example.com/image.jpg" />
    <meta property="og:description" content="Description Here" />
    <meta property="og:site_name" content="Site Name, i.e. Moz" />
    <meta property="og:price:amount" content="15.00" />
    <meta property="og:price:currency" content="USD" />

    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Login</title>

    <!-- Reset for cros site render -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <!-- Google fonts -->
    <link href="https://fonts.googleapis.com/css?family=Lato:400,700|Roboto" rel="stylesheet">
    
    <!-- Page style -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/login.css">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
      <![endif]-->
  </head>
  <body>
    <div class="background">
    
    <div  class="col-sm-12 text-center" id="loginlogo"> </div>
    </div>

    <section class="login_wrapper animated zoomIn">



        <form action="<?php echo base_url()?>login/admin" method="POST" enctype="multipart/form-data" role="form">
            <h1>Admin Login</h1>
            <div class="login_row">

                <!-- Alert Row -->
                <!-- <div class="login_row_group">
                    <div class="alert alert-success  animated shake">
                        Yeah! Logged in
                    </div>
                </div> -->

                <?php if($error){ ?>
                    <!-- Alert Row -->
                    <div class="login_row_group">
                        <div class="alert alert-error  animated bounceIn">
                            <?php echo $message; ?>
                        </div>
                    </div>
                <?php } ?>

                <!-- Alert Row -->
                <!-- <div class="login_row_group">
                    <div class="alert alert-info  animated tada">
                        Hello! please activate your account first
                    </div>
                </div> -->

                <!-- Username Row -->
                <div class="login_row_group">
                    <div class="lrg_control">
                        <div class="lrg_addon lrg_icon_user">
                        </div>
                        <input type="text" name="username" placeholder="Username" class="form-control" required>
                    </div>
                    <div class="clear-fix"></div>
                </div>


                <!-- Password Row -->
                <div class="login_row_group">
                    <div class="lrg_control">
                        <div class="lrg_addon lrg_icon_lock">
                        </div>
                        <input type="password" name="password" placeholder="Password" required >
                    </div>
                    <div class="clear-fix"></div>
                </div>

                <?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
                <br />

                <button type="submit" class="login_button">
                    Login
                </button>
                <button class="btn btn-primary btn-block btn-large" id="forget_pass" data-toggle="modal" data-target="#myModal">Forgot password?</button>

            </div>

            
        </form>
        
    </section>
    <div id="myModal" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Forgot Password?</h4>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-success" style="display: none;" id="success_alert">
                            <strong>Success!</strong> Acc details sent to email.
                        </div>
                        <div class="alert alert-danger" style="display: none;" id="error_alert">
                            <strong>Error!</strong> Acc doesn't exists.
                        </div>
                        <form>
                            <div class="form-group">
                                <label for="email">Email address:</label>
                                <input type="email" class="form-control" id="email">
                            </div>
                            <button type="submit" id="forget_pass_submit" class="btn btn-default">Submit</button>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>

            </div>
        </div>
        <div  class="col-sm-12 text-center" id="loginlogo"> </div>
  </body>
  <script type="text/javascript">
        $("#forget_pass_submit").click(function(e){
            e.preventDefault();
            if($('#email').val() == ""){
                $('#email').focus();
                return false;
            }
            $.post( "<?php echo base_url()?>login/forget_pass", { email: $('#email').val() }, function(response){
                if(response == "success"){
                    $("#success_alert").show();
                    $("#error_alert").hide();
                }
                else{
                    $("#success_alert").hide();
                    $("#error_alert").show();
                }
            } );
        });
        $("#forget_pass").click(function(e){
            e.preventDefault();
        });
    </script>
  </html>