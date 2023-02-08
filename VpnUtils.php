<?php
//include("login/db.php");//
class VpnUtils
{
    private $mysqli;

    public function __construct($server, $db_user, $db_pwd, $db_name){
        $this->mysqli = new mysqli($server, $db_user, $db_pwd, $db_name);
		if ($this->mysqli->connect_errno) {
	    	echo "Failed to connect to MySQL: ";
            return false;
			}	
		mysqli_set_charset($this->mysqli,"utf8");
		date_default_timezone_set('America/Bogota'); 
    }
    public function getAreaCode($ip){//legacy
    
    }
    public function getServerIp($idGroup){
        $sql="SELECT `server-ip` FROM  `redesagi_facturacion`.`vpn_targets` WHERE `id-repeater-subnet-group`=$idGroup";
		if($rt=$this->mysqli->query($sql)){
			$row=$rt->fetch_assoc();
			return $row['server-ip'];
		}
		return false;
    }
    public function updateGroupId($id,$ip){
        $status=true;
        $sql="select * FROM `redesagi_facturacion`.`afiliados` WHERE  `id`=$id ";  
        if($rt=$this->mysqli->query($sql)){
            if($rt->num_rows){
                while($row=$rt->fetch_assoc()){
					$idCliente=$row['id'];
					if($ip){
						$byte3=explode(".",$ip)[2];
						$sql="SELECT * FROM `items_repeater_subnet_group` WHERE  `ip-segment`= $byte3 ";
						if($sg=$this->mysqli->query($sql)){
							$rw=$sg->fetch_assoc();
							$segmentIp=$rw["ip-segment"];
							$idGroup=$rw["id-repeater-subnets-group"];
							if($idGroup){
								$sqlUpdate="UPDATE `afiliados` SET `id-repeater-subnets-group`=$idGroup WHERE `id`=$idCliente";
							}else{
								//print "\n client: {$row['cliente']} ip : $ip  segment:$segmentIp pertenece a grupo: $idGroup \n";
								$sqlUpdate="UPDATE `afiliados` SET `id-repeater-subnets-group`=0 WHERE `id`=$idCliente";
                                $status=false;
							}
							$this->mysqli->query($sqlUpdate);
							$sg->free();	
						}else{
                            $status=false;
                        }
					}else{
						$sqlUpdate="UPDATE `afiliados` SET `id-repeater-subnets-group`=0 WHERE `id`=$idCliente";
						$this->mysqli->query($sqlUpdate);
                        $status=false; 
					} 
                }
			}else{
                $status=false;
            }
			$rt->free();
		}else{
            $status=false;
        }
        return ($status)?$idGroup:false;
    }
}

//$object=new VpnUtils($server, $db_user, $db_pwd, $db_name);
//$object->updateGroupId("808","192.168.30.150");
?>