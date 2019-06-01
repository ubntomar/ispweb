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
 <!-- DataTables CSS -->
    <link href="css/dataTables.bootstrap.css" rel="stylesheet">
   <link href="css/dataTables.css" rel="stylesheet">
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
						<!--<li>
                            <a href="admin_settings.php"><i class="fa fa-gear fa-fw"></i>Settings</a>
                        </li> -->
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
                    <h1 class="page-header"></h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            
           <div class="page-content inset">

            <div class="row">
                <div class="admin_rec">
				<div class="panel panel-default">
				<div class="panel-heading">
                            User details
                        </div>
						<div class="panel-body">
						
                    <?php
					

					$usr=mysqli_real_escape_string($con,$_GET["rq"]);
					$usr=htmlspecialchars($usr,ENT_COMPAT);
					if (!empty($usr))
				{
					

					//query database to check activation code
					$query="select username,email,'email' as source,case when activ_status='0' then 'Not Activated' when activ_status='1' then 'activated' when activ_status='2' then 'reset' end as activ_status from ".$table_name." where username='$usr' union select username,email,source,'activated' as activ_status from ".$table_name_social." where username='$usr'";
					
					$result=mysqli_query($con,$query) or die('error');

						 if (mysqli_num_rows($result))
						 {
							 $row=mysqli_fetch_array($result);
							  
							 ?>
							 
							 <form id="search_user" method="post">
			  <div class="form-group">
				<label for="exampleInputUsername">Username</label>
				<input type="text" id="username" class="form-control" name="username" placeholder="Username" value=<?php echo $row['username']; ?> readonly>
			  </div>
			  <div class="form-group">
				<label for="exampleInputEmail">Email address</label>
				  <input type="text" id="email" name="email" placeholder="Email" class="form-control" value=<?php echo $row['email']; ?> readonly>
			  </div>	
				<div class="form-group">
				<label for="exampleInputsource">Source</label>
				  <input type="text" id="source" name="source" placeholder="Source" class="form-control" value=<?php echo $row['source']; ?> readonly>
			  </div>
				<div class="form-group">
				<label for="exampleInputactiv">Status</label>
				  <input type="text" id="status" name="status" placeholder="status" class="form-control" value=<?php echo $row['activ_status']; ?> readonly>
			  </div>			  


			  </form>
			  
							 <?php
						 }
				}
					?>
					
					
							 
					</div>
					</div>
					
            </div>
        </div>
    </div>
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
   
<script src="js/jquery.validate.js"></script>
    <!-- Custom Theme JavaScript -->
    <script src="js/sb-admin-2.js"></script>

 <!-- DataTables JavaScript -->
    <script src="js/jquery.dataTables.min.js"></script>
	 <script src="js/dataTables.bootstrap.js"></script>
	<script>
    $(document).ready(function() {
        $('#dataTable1').DataTable({
                responsive: true
        });
    });
    </script>

</body>

</html>
