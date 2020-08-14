<?php
session_start();
$urlprev=$_SESSION['urlsource'];
session_destroy();
?>
<!DOCTYPE html>

<head>
    <title>Isp Experts -Administrador ISP -Solucion sencilla para tu negocio </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="../bower_components/bootstrap/dist/css/bootstrap.min.css">
    <link href="css/style.css" rel="stylesheet" media="screen">
    <link href="css/bootstrap-social.css" rel="stylesheet" media="screen">

    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700|Roboto:300,400,500" rel="stylesheet">


</head>

<body>


    <div class="row">
        <div class="columna col bg-dark">

            <div class=" nuevo_contenido">

                <!-- inicio de contenido de pagina -->
                <!-- Form Login -->
                <div>
                    <div class="logo text-light">
                        <h2><?php include('db.php'); echo $logotxt; ?></h2>
                    </div>
                    <form class="form-horizontal" id="login_form">
                        <h2>Bienvenido</h2>

                        <div class="line"></div>
                        <div class="form-group">
                            <input type="text" id="inputEmail" name="username" placeholder="Username">
                        </div>
                        <div class="form-group">
                            <input type="password" id="inputPassword" name="password" placeholder="Password">
                        </div>
                        <a class="forgotten-password-link" href="forgot_form.php">Forgot password?</a>
                        <a href="registration_form.php" class="btn btn-lg btn-register">Register</a>

                        <button id="signin" type="submit" class="btn btn-lg btn-primary btn-sign-in"
                            data-loading-text="Loading...">Sign in</button>
                        <div class="messagebox">
                            <div id="alert-message"></div>
                        </div>
                        <!-- <div class="social">
                            <a href="twitter_connect.php"><img src="img/twitter.png" /></a>
                            <a href="facebook_connect.php"><img src="img/fb.png" /></a>
                            <a href="google_connect.php?=code"><img src="img/gplus.png" /></a>

                        </div> -->
                    </form>
                </div>
                <!-- fin de contenido de pagina -->
            </div>
        </div>
    </div>







    <script src="../bower_components/jquery/dist/jquery.min.js"></script>
    <script src="../bower_components/Popper/popper.min.js"></script>
    <script src="../bower_components/bootstrap/dist/js/bootstrap.js"></script>
    <script type="text/javascript" src="js/jquery.validate.js"></script>

    <script type="text/javascript">
    (function() {
        var po = document.createElement('script');
        po.type = 'text/javascript';
        po.async = true;
        po.src = 'https://apis.google.com/js/client:plusone.js';
        var s = document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(po, s);
    })();
    </script>
    <script>
    $(document).ready(function() {
        jQuery.validator.addMethod("noSpace", function(value, element) {
            return value.indexOf(" ") < 0 && value != "";
        }, "Spaces are not allowed");


        //add validator
        $("#login_form").validate({
            onfocusout: false,
            onkeyup: false,
            onclick: false,
            rules: {
                username: {
                    required: true,
                    noSpace: true
                },
                password: {
                    required: true,
                    minlength: 6
                }
            },

            messages: {
                username: {
                    required: "Enter your username"
                },
                password: {
                    required: "Enter your password",
                    minlength: "Password must be minimum 6 characters"
                },
            },



            errorPlacement: function(error, element) {
                error.hide();
                $('.messagebox').hide();
                error.appendTo($('#alert-message'));
                $('.messagebox').slideDown('slow');



            },
            highlight: function(element, errorClass, validClass) {
                $(element).parents('.form-group').addClass('has-error');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).parents('.form-group').removeClass('has-error');
                $(element).parents('.form-group').addClass('has-success');
            }
        });

        $("#login_form").submit(function() {
            $('.social').hide('slow');
            if ($("#login_form").valid()) {
                $('.messagebox').slideUp('slow');
                var data1 = $('#login_form').serialize();
                $("button").button('loading');
                $.ajax({
                    type: "POST",
                    url: "login.php",
                    data: data1,
                    dataType: 'json',
                    success: function(msg) {
                        if (msg.result == 1) {
                            $('.messagebox').addClass("success-message");
                            $('.message').slideDown('slow');
                            $('#alert-message').text("Logged in.. Redirecting");

                            $('#login_form').fadeOut(5000);
                            //$("button").button('reset');
                            window.location = "members.php?urlprev=<?php echo $urlprev; ?>";
                        } else {
                            $("button").button('reset');
                            console.log(msg.result);
                            $('.messagebox').hide();
                            $('.messagebox').addClass("error-message");
                            $('#alert-message').html(msg.result);
                            $('.messagebox').slideDown('slow');
                        }
                    },
                    error: function(msg) {
                        $("button").button('reset');
                        $('.messagebox').hide();
                        $('.messagebox').addClass("error-message");
                        $('#alert-message').html(msg.result);
                        $('.messagebox').slideDown('slow');
                    }
                });
            }
            return false;
        });

    });
    </script>
</body>

</html>