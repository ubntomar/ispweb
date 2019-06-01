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
    <h2><?php include('db.php');
        echo $logotxt; ?></h2>

</div>
<form class="form-horizontal" id="admin_form">
    <h2>Admin Sign In</h2>

    <div class="line"></div>
    <div class="form-group">
        <input type="text" id="inputEmail" name="username" placeholder="Admin Username">
    </div>
    <div class="form-group">
        <input type="password" id="inputPassword" name="password" placeholder="Admin Password">
    </div>

    <button type="submit" class="btn btn-lg btn-primary btn-sign-in"
            data-loading-text="Loading...">Sign in
    </button>
    <div class="messagebox">
        <div id="alert-message"></div>
    </div>
</form>
<script>
    $(document).ready(function () {


        $("#admin_form").submit(function () {

            $("#admin_form").validate({
                rules: {
                    username: {
                        required: true
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


                errorPlacement: function (error, element) {
                    error.hide();
                    $('.messagebox').hide();
                    error.appendTo($('#alert-message'));
                    $('.messagebox').slideDown('slow');


                },
                highlight: function (element, errorClass, validClass) {
                    $(element).parents('.form-group').addClass('has-error');
                },
                unhighlight: function (element, errorClass, validClass) {
                    $(element).parents('.form-group').removeClass('has-error');
                    $(element).parents('.form-group').addClass('has-success');
                }
            });

            if ($("#admin_form").valid()) {
                var data1 = $('#admin_form').serialize();
                $.ajax({
                    type: "POST",
                    url: "admin_check.php",
                    data: data1,
                    dataType: 'json',
                    success: function (msg) {
                        console.log(msg.result);
                        if (msg.result == 1) {
                            $('.messagebox').addClass("success-message");
                            $('.message').slideDown('slow');
                            $('#alert-message').text("Logged in.. Redirecting");

                            $('#admin_form').fadeOut(5000);
                            window.location = "admin_home.php"
                        } else {
                            $('.messagebox').hide();
                            $('.messagebox').addClass("error-message");
                            $('#alert-message').text(msg.result);
                            $('.messagebox').slideDown('slow');
                        }
                    }
                });
            }
            return false;
        });
    });
</script>
</body>

</html>