<?php session_start();?>
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
	<form class="form-horizontal" id="reset_pwd" method="post">
         <h2>Reset Password</h2>

        <div class="line"></div>
	<?php
include "db.php";
$con = mysqli_connect($server, $db_user, $db_pwd, $db_name) //connect to the database server
 or die("Could not connect to mysql because " . mysqli_error());

mysqli_select_db($con, $db_name) //select the database
 or die("Could not select to mysql because " . mysqli_error());

$key = mysqli_real_escape_string($con, $_GET["k"]);
$key=htmlspecialchars($key,ENT_COMPAT);
if (!empty($key)) {

    //query database to check activation code
    $query = "select * from " . $table_name . " where activ_key='$key' and activ_status='2'";
    $result = mysqli_query($con, $query) or die('error');

    if (mysqli_num_rows($result)) {
        $row = mysqli_fetch_array($result);
        if ($row['activ_status'] == '2') {
            $username = trim($row['username']);
            $_SESSION['username'] = $username;
            //html
            ?>



        <div class="control-group">
            <input type="password" id="password1" name="password1" placeholder="Password">
        </div>
        <div class="control-group">
            <input type="password" id="password2" name="password2" placeholder="Retype Password">
        </div>

        <button
        type="submit" class="btn btn-lg btn-primary btn-sign-in" data-loading-text="Loading...">Reset</button>

            <div class="messagebox">
                <div id="alert-message"></div>
            </div>

		<?php
} else {
            echo "<div class=\"messagebox\"><div id=\"alert-message\">You can login</div></div>";
        }

    } else {
        echo "<div class=\"messagebox\"><div id=\"alert-message\">You can login</div></div>";
        //header('Location: $url');
    }
} else {
    echo "<div class=\"messagebox\"><div id=\"alert-message\">error</div></div>";
}

?>

	 </form>
    <script type="text/javascript">
        $(document).ready(function() {

            $('#reset_pwd').validate({
                debug: true,
                rules: {
                    password1: {
                        minlength: 6,
                        required: true
                    },
                    password2: {
                        required: true,
                         minlength: 6,
						 equalTo: "#password1"
                    }
                },
				messages: {
                        password1: {
                            required: "Enter password "
                        },
                        password2: {
                            required: "Retype your password",
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
                        $(element).parents('.control-group').addClass('error');
                    },
                    unhighlight: function(element, errorClass, validClass) {
                        $(element).parents('.control-group').removeClass('error');
                        $(element).parents('.control-group').addClass('success');
                    }
            });




            $("#reset_pwd").submit(function() {

                if ($("#reset_pwd").valid()) {
                    var data1 = $('#reset_pwd').serialize();
                    $.ajax({
                        type: "POST",
                        url: "process_reset.php",
                        data: data1,
                        success: function(msg) {
							 console.log(msg);

							$('.messagebox').hide();
							$('.messagebox').addClass("error-message");
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