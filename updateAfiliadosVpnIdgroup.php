<?php

	include("login/db.php");
	$mysqli = new mysqli($server, $db_user, $db_pwd, $db_name);
	if ($mysqli->connect_errno) {
		echo "Failed to connect to MySQL: " . $mysqli->connect_error;
	}
	mysqli_set_charset($mysqli, "utf8");
	date_default_timezone_set('America/Bogota');
	$today = date("Y-m-d");
	$sql="select * FROM `afiliados` WHERE  1";  
        if($rt=$mysqli->query($sql)){
            if($rt->num_rows){
                while($row=$rt->fetch_assoc()){
                    $ip=$row['ip'];
					$idCliente=$row['id'];
					if($ip){
						$byte3=explode(".",$ip)[2];
						$sql="SELECT * FROM `items_repeater_subnet_group` WHERE  `ip-segment`= $byte3 ";
						if($sg=$mysqli->query($sql)){
							$rw=$sg->fetch_assoc();
							$segmentIp=$rw["ip-segment"];
							$idGroup=$rw["id-repeater-subnets-group"];
							if($idGroup){
								$sqlUpdate="UPDATE `afiliados` SET `id-repeater-subnets-group`=$idGroup WHERE `id`=$idCliente";
							}else{
								print "\n client: {$row['cliente']} ip : $ip  segment:$segmentIp pertenece a grupo: $idGroup \n";
								$sqlUpdate="UPDATE `afiliados` SET `id-repeater-subnets-group`=0 WHERE `id`=$idCliente";
							}
							$mysqli->query($sqlUpdate);
							$sg->free();	
						}
					}else{
						$sqlUpdate="UPDATE `afiliados` SET `id-repeater-subnets-group`=0 WHERE `id`=$idCliente";
						$mysqli->query($sqlUpdate);
					} 
                }
			}
			$rt->free();
		}
	?>