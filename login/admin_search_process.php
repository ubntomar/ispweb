<?php

include "db.php";
$con = mysqli_connect($server, $db_user, $db_pwd, $db_name) //connect to the database server
 or die("Could not connect to mysql because " . mysqli_error());

mysqli_select_db($con, $db_name) //select the database
 or die("Could not select to mysql because " . mysqli_error());

$username = mysqli_real_escape_string($con, $_POST["username"]);
$email = mysqli_real_escape_string($con, $_POST["email"]);

		//prevent xss

$username = htmlspecialchars($username,ENT_COMPAT);
$email =  htmlspecialchars($email,ENT_COMPAT);

$query = "SELECT username, case when activ_status='0' then 'Not Activated' when activ_status='1' then 'activated' end as activ_status,  'email' as source FROM " . $table_name . " where email='" . $email . "' or username='" . $username . "' UNION ALL SELECT username,  'activated', source FROM " . $table_name_social . " where email='" . $email . "' or username='" . $username . "'";
$result = mysqli_query($con, $query) or die('error');
if (mysqli_num_rows($result)) //if exist then check for password
{
    echo "<table class=\"table table-bordered\">";

    echo "<thead><tr><th>UserName</th><th>Activation Status</th><th>Source</th></thead>";
    while ($db_field = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td><a href=\"admin_user_info.php?rq=".$db_field['username']."\">" . $db_field['username'] . "</a></td><td> " . $db_field['activ_status'] . " </td><td> <span ";
        if ($db_field['source'] == 'Twitter') {
            echo "class=\"label label-info\"";
        } elseif ($db_field['source'] == 'facebook') {
            echo "class=\"label label-primary\"";
        } elseif ($db_field['source'] == 'Google') {
            echo "class=\"label label-danger\"";
        } else {
            echo "class=\"label label-default\"";
        }
        echo ">" . $db_field['source'] . " </span></td></tr>";

    }
    echo "</table>";

} else {
    die("Username Doesn't exist");
}
