<?php
session_start();

if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {

    if (empty($_SESSION['access_token']) || empty($_SESSION['access_token']['oauth_token']) || empty($_SESSION['access_token']['oauth_token_secret'])) {

        if (!isset($_SESSION['token'])) {

            if (!isset($_SESSION['fb_access_token'])) {

                header('Location: index.php');

                exit;
            }
        }
    }
}

?>





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
         <h2><?php include 'db.php';
echo $logotxt;?></h2>

    </div>
    <form class="form-horizontal" id="change_password_form">
         <h2>Change Password</h2>
		 <div class="line"></div>
        <div class="form-group">
            <input type="text" id="inputuserid" name="username" placeholder="Username" value=<?php echo $_SESSION['username']; ?> disabled>
        </div>
		<div class="form-group">
            <input type="password" id="inputPasswordOld" name="oldpassword" placeholder="Curent Password">
        </div>
        <div class="form-group">
            <input type="password" id="inputPassword" name="password" placeholder="New Password">
        </div>
        <div class="form-group">
            <input type="password" id="inputPassword_2" name="retype_password" placeholder="Retype Password">
        </div>





<button type="submit"
        class="btn btn-lg btn-primary btn-sign-in" data-loading-text="Loading...">Change password</button>
        <div class="messagebox">
            <div id="alert-message"></div>
        </div>
    </form>


	<script>
        $(document).ready(function() {

		jQuery.validator.addMethod("noSpace", function(value, element) {
     return value.indexOf(" ") < 0 && value != "";
  }, "Spaces are not allowed");
/*jQuery.validator.addMethod("maxlength", function (value, element, param) {
    console.log('element= ' + $(element).attr('name') + ' param= ' + param )
    if ($(element).val().length > param) {
        return false;
    } else {
		console.log($(element).val().length);
        return true;
    }
}, "You have reached the maximum number of characters allowed for this field.");
*/

            $("#change_password_form").submit(function() {

                $("#change_password_form").validate({
                    rules: {

                        username: {
                            required: true,
							noSpace: true
                        },
						oldpassword: {
                            required: true,
                            minlength: 6
							//maxlength: 8
                        },
                        password: {
                            required: true,
                            minlength: 6
							//maxlength: 8
                        },
                        retype_password: {
                            required: true,
                            equalTo: "#inputPassword"
                        },
                    },
                    messages: {
                        username: {
                            required: "Enter Username",
                        },
						 oldpassword: {
                            required: "Enter your old password",
                            minlength: "Password must be minimum 6 characters"
							//maxlength: "Password must be maximum 8 characters"

                        },
                        password: {
                            required: "Enter your password",
                            minlength: "Password must be minimum 6 characters"
							//maxlength: "Password must be maximum 8 characters"

                        },
                        retype_password: {
                            required: "Enter confirm password",
                            equalTo: "Passwords must match"
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

                if ($("#change_password_form").valid()) {
                    var data1 = $('#change_password_form').serialize();
                    $.ajax({
                        type: "POST",
                        url: "process_change_password.php",
                        data: data1,
                        success: function(msg) {
                            console.log(msg);
                            $('.messagebox').hide();
							$('#alert-message').html(msg);
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



