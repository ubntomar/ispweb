<?php session_start();
   //logout if session not active
   if (!isset($_SESSION['admin'])) {
       header('Location: admin_login.php');
   }
   ?><!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta name="description" content="">
      <meta name="author" content="">
      <title>PHP Login System</title>
      <!-- Bootstrap Core CSS -->
      <link href="css/bootstrap.min.css" rel="stylesheet">
      <!-- MetisMenu CSS -->
      <link href="css/metisMenu.css" rel="stylesheet">
      <!-- Custom CSS -->
      <link href="css/sb-admin-2.css" rel="stylesheet">
      <!-- Custom Fonts -->
      <link href="css/font-awesome.css" rel="stylesheet" type="text/css">
      <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
      <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
      <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
      <![endif]-->
   </head>
   <body>
      <?php
         include("db.php");
         $con=mysqli_connect($server, $db_user, $db_pwd,$db_name) //connect to the database server
         or die ("Could not connect to mysql because ".mysqli_error());
         
         mysqli_select_db($con,$db_name)  //select the database
         or die ("Could not select to mysql because ".mysqli_error());
         
         $selectall = "select * from admin_settings LIMIT 1";
         $result = mysqli_query($con,$selectall);
         if($result){ //table exists
         $db_field = mysqli_fetch_assoc($result);
         $count = mysqli_num_rows($result);
         
         if ($count ==0) {  //new table not configured. copy the setting from db.php to admin_settings table
         $insert_sql="insert into admin_settings values(1,'$logotxt','$from_address','$admin_user','$admin_password','$url',1,'".CONSUMER_KEY."','".CONSUMER_SECRET."',1,'$Clientid','$Email_address','$Client_secret','$apikeys',1,'$fbappid','$fbsecret')";
         //	echo $insert_sql;  || !$db_field['admin_configured']
         if (!mysqli_query($con, $insert_sql)) {
         die('Error: ' . mysqli_error($con));
         }
         else{
         $fileContent = file_get_contents ('db.php');
         $txt="\n"."\$configured=TRUE; // set false to read from db.php and TRUE to read from admin_settings table" . "\n" ;
         file_put_contents ('db.php', $fileContent.$txt);
         $selectall = "select * from admin_settings LIMIT 1";
         $result = mysqli_query($con,$selectall);
         $db_field = mysqli_fetch_assoc($result);
         
         }
         }
         else {  //table exists. 
         
         }
         }
         else {
         echo '<a class="btn btn-danger" href="'.$url."/install_create_tables.php"."\">Click to create settings table</a>";
         }
         
         ?>
      <div id="wrapper">
      <!-- Navigation -->
      <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
         <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.php">Home</a>
         </div>
         <!-- /.navbar-header -->
         <ul class="nav navbar-top-links navbar-right">
            <li class="dropdown">
               <a class="dropdown-toggle" data-toggle="dropdown" href="#">
               <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
               </a>
               <ul class="dropdown-menu dropdown-user">
                  <!--<li><a href="admin_profile.php"><i class="fa fa-user fa-fw"></i> Admin Profile</a>
                     </li>
                     <li><a href="admin_settings.php"><i class="fa fa-gear fa-fw"></i> Settings</a>
                     </li>
                     <li class="divider"></li> -->
                  <li><a href="logout.php"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                  </li>
               </ul>
               <!-- /.dropdown-user -->
            </li>
            <!-- /.dropdown -->
         </ul>
         <!-- /.navbar-top-links -->
         <div class="navbar-default sidebar" role="navigation">
            <div class="sidebar-nav navbar-collapse">
               <ul class="nav" id="side-menu">
                  <li class="sidebar-search">
                     <div class="input-group custom-search-form">
                        <input type="text" class="form-control" placeholder="Search...">
                        <span class="input-group-btn">
                        <button class="btn btn-default" type="button">
                        <i class="fa fa-search"></i>
                        </button>
                        </span>
                     </div>
                     <!-- /input-group -->
                  </li>
                  <li>
                     <a href="admin_home.php"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
                  </li>
                  <li>
                     <a href="admin_user_list.php"><i class="fa fa-table fa-fw"></i> User list</a>
                  </li>
                  <li>
                     <a href="admin_user_search.php"><i class="fa fa-search fa-fw"></i> Search user</a>
                  </li>
                  <li>
                     <a href="admin_user_add.php"><i class="fa fa-plus fa-fw"></i>Add new user</a>
                  </li>
                  <li>
                     <a href="admin_settings.php"><i class="fa fa-gear fa-fw"></i>Settings</a>
                  </li>
                  <li>
                     <a href="logout.php"><i class="fa fa-sign-out fa-fw"></i>Logout</a>
                  </li>
               </ul>
            </div>
            <!-- /.sidebar-collapse -->
         </div>
         <!-- /.navbar-static-side -->
      </nav>
      <div id="page-wrapper">
      <div class="row">
         <div class="col-lg-12">
            <h1 class="page-header">Settings</h1>
         </div>
         <!-- /.col-lg-12 -->
      </div>
      <!-- /.row -->
      <div class="row">
         <div class="col-lg-12">
            <div class="row">
               <div class="col-lg-6">
                  <div class="panel panel-default">
                     <div class="panel-heading">
                        Basic settings
                     </div>
                     <div class="panel-body">
                        <form  id="change_settings">
                           <div class="form-group">
                              <label>Logo Text</label>
                              <input type="text" id="logotxt" class="form-control" name="logotxt" placeholder="Logo Text" value=<?php echo $db_field['admin_logotxt']; ?> >
                           </div>
                           <div class="form-group">
                              <label>From email address</label>
                              <input type="text" id="admin_email_from" name="admin_email_from" placeholder="Email" class="form-control" value=<?php echo $db_field['admin_email_from']; ?> >
                           </div>
                           <div class="form-group">
                              <label>Admin user id</label>
                              <input type="text" id="admin_userid" name="admin_userid" placeholder="Admin user id" class="form-control" value=<?php echo $db_field['admin_userid']; ?> >
                           </div>
                           <div class="form-group">
                              <label>Admin password</label>
                              <input type="text" id="admin_password" name="admin_password" placeholder="Admin password" class="form-control" value=<?php echo $db_field['admin_password']; ?> >
                           </div>
                           <div class="form-group">
                              <label>Site URL</label>
                              <input type="text" id="admin_site_url" name="admin_site_url" placeholder="Site URL" class="form-control" value=<?php echo $db_field['admin_site_url']; ?> >
                           </div>
                     </div>
                  </div>
                  <div class="panel panel-default">
                  <div class="panel-heading">
                  Facebook button settings
                  </div>
                  <div class="panel-body">
                  <fieldset>
                  <div class="form-group">
                  <label>Enable Facebook?</label>
                  <select class="form-control" name="admin_facebook_enable">
                  <option value="1" <?php if($db_field['admin_facebook_enable']) echo "selected"; ?>>Yes</option>
                  <option value="0" <?php if(!$db_field['admin_facebook_enable']) echo "selected"; ?>>No</option>
                  </select>
                  </div>
                  <div class="form-group">
                  <label>App ID</label>
                  <input type="text" id="admin_facebook_appid" name="admin_facebook_appid" placeholder="Facebook App ID" class="form-control" value=<?php echo $db_field['admin_facebook_appid']; ?> >
                  </div>
                  <div class="form-group">
                  <label>App secret</label>
                  <input type="text" id="admin_facebook_secret" name="admin_facebook_secret" placeholder="Facebook  app secret" class="form-control" value=<?php echo $db_field['admin_facebook_secret']; ?> >
                  </div>
                  </fieldset>
                  </div>
                  </div>
                  <!-- /.col-lg-6 (nested) -->
                  <!-- /.panel-body -->
               </div>
               <!-- /.col-lg-6 (nested) -->
               <div class="col-lg-6">
               <div class="panel panel-default">
               <div class="panel-heading">
               Twitter button settings
               </div>
               <div class="panel-body">
               <fieldset>
               <div class="form-group">
               <label>Enable Twitter?</label>
               <select class="form-control" name="admin_twitter_enable">
               <option value="1" <?php if($db_field['admin_twitter_enable']) echo "selected"; ?>>Yes</option>
               <option value="0" <?php if(!$db_field['admin_twitter_enable']) echo "selected"; ?>>No</option>
               </select>
               </div>
               <div class="form-group">
               <label>Consumer key</label>
               <input type="text" id="admin_twitter_consumer_key" name="admin_twitter_consumer_key" placeholder="Twitter Consumer key" class="form-control" value=<?php echo $db_field['admin_twitter_consumer_key']; ?> >
               </div>
               <div class="form-group">
               <label>Consumer secret</label>
               <input type="text" id="admin_twitter_consumer_secret" name="admin_twitter_consumer_secret" placeholder="Twitter  Consumer secret" class="form-control" value=<?php echo $db_field['admin_twitter_consumer_secret']; ?> >
               </div>
               </fieldset>
               </div>
               </div>
               <div class="panel panel-default">
               <div class="panel-heading">
               Google button settings
               </div>
               <div class="panel-body">
               <fieldset>
               <div class="form-group">
               <label>Enable Google?</label>
               <select class="form-control" name="admin_google_enable">
               <option value="1" <?php if($db_field['admin_google_enable']) echo "selected"; ?>>Yes</option>
               <option value="0" <?php if(!$db_field['admin_google_enable']) echo "selected"; ?>>No</option>
               </select>
               </div>
               <div class="form-group">
               <label>App ID</label>
               <input type="text" id="admin_google_client_id" name="admin_google_client_id" placeholder="Google client ID" class="form-control" value=<?php echo $db_field['admin_google_client_id']; ?> >
               </div>
               <div class="form-group">
               <label>Google Email</label>
               <input type="text" id="admin_google_email" name="admin_google_email" placeholder="Google Email ID" class="form-control" value=<?php echo $db_field['admin_google_email']; ?> >
               </div>
               <div class="form-group">
               <label>Client secret</label>
               <input type="text" id="admin_google_clientsecret" name="admin_google_clientsecret" placeholder="Google client secret" class="form-control" value=<?php echo $db_field['admin_google_clientsecret']; ?> >
               </div>
               <div class="form-group">
               <label>Google API keys</label>
               <input type="text" id="admin_google_apikeys" name="admin_google_apikeys" placeholder="Google Api key" class="form-control" value=<?php echo $db_field['admin_google_apikeys']; ?> >
               </div>
               </fieldset>
               </div>
               </div>
               </div>
            </div>
            <button type="submit" class="btn btn-default">Save settings</button>
            <div class="messagebox">
            <div id="alert-message"></div>
            </div>
            </form>
            <!-- /.panel -->
            <br />
            <br /><br />
            <!-- /.col-lg-12 -->
            <!-- /.row -->
         </div>
         <!-- /#page-wrapper -->
      </div>
      <!-- /#wrapper -->
      <!-- jQuery -->
      <script src="js/jquery.js"></script>
      <!-- Bootstrap Core JavaScript -->
      <script src="js/bootstrap.min.js"></script>
      <!-- Metis Menu Plugin JavaScript -->
      <script src="js/metisMenu.js"></script>
      <!-- Custom Theme JavaScript -->
      <script src="js/sb-admin-2.js"></script>
      <script>
         $(document).ready(function() {
         
             $("#change_settings").submit(function() {
                     var data1 = $('#change_settings').serialize();
                     $.ajax({
                         type: "POST",
                         url: "admin_settings_save.php",
                         data: data1,
                         success: function(msg) {
                             console.log(msg);
                             $('.messagebox').hide();
         $('#alert-message').html(msg);
         $('.messagebox').slideDown('slow');
                         }
                     });
                 
                 return false;
             });
         });
      </script>
   </body>
</html>