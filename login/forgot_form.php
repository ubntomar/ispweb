<!DOCTYPE html>
<head>
    <title>PHP Login System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link href="css/style.css" rel="stylesheet" media="screen">
</head>
<body>
    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/jquery.validate.js"></script>
    <div class="logo">
         <h2><?php include('db.php'); echo $logotxt; ?></h2>

    </div>
    <form class="form-horizontal" id="forgot_pwd" method="post">
         <h2>Forgot Password</h2>

        <div class="line"></div>
        <div class="control-group">
            <input type="text" id="username" name="username" placeholder="Username">
        </div>
        <div class="control-group">
            <input type="text" id="email" name="email" placeholder="Email">
        </div>	<a href="registration_form.php" class="btn btn-lg btn-register">Register</a>

        <button
        type="submit" class="btn btn-lg btn-primary btn-sign-in" data-loading-text="Loading...">Password Reset</button>
            <div class="messagebox">
                <div id="alert-message"></div>
            </div>
    </form>
    <script type="text/javascript">
        $(document).ready(function() {

            $('#forgot_pwd').validate({
                debug: true,
                rules: {
                    username: {
                        minlength: 6,
                        required: true,
						
                    },
                    email: {
                        required: true,
                        email: true
                    }
                },
				messages: {
                        username: {
                            required: "Enter your Username"
                        },
                        email: {
                            required: "Enter your Email",
							email: "Enter valid email address"
                        },
                    },
					
				 errorPlacement: function(error, element) {
                        error.hide();
						$('.messagebox').hide();
                        error.appendTo($('#alert-message'));
                        $('.messagebox').slideDown('slow');
                       
						
						
                    },
				highlight: function(element, errorClass, validClass) {
                        $(element).parents('.control-group').addClass('error');
                    },
                    unhighlight: function(element, errorClass, validClass) {
                        $(element).parents('.control-group').removeClass('error');
                        $(element).parents('.control-group').addClass('success');
                    }
            });




            $("#forgot_pwd").submit(function() {

                if ($("#forgot_pwd").valid()) {
                    var data1 = $('#forgot_pwd').serialize();
                    $.ajax({
                        type: "POST",
                        url: "forgot_pwd.php",
                        data: data1,
                        success: function(msg) {
						console.log(msg);
							 $('.messagebox').hide();
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