<?php 
class Repeater 
{
    public $mysqli;
    public function __construct($server, $db_user, $db_pwd, $db_name){
        $response= true;
        $this->mysqli = new mysqli($server, $db_user, $db_pwd, $db_name);
		if ($this->mysqli->connect_errno) {
	    	echo "Failed to connect to MySQL: ";
            $response= false;//
			}	
		mysqli_set_charset($this->mysqli,"utf8");
		date_default_timezone_set('America/Bogota'); 
        return $response;
    }
    public function getServersList(){
        $response=null;
        $sql="SELECT * from `redesagi_facturacion`.`vpn_targets` WHERE `active`='1' ";
        if($result=$this->mysqli->query($sql)){
            while($row=$result->fetch_assoc()){
                $id=$row["id"];$fecha=$row["fecha"];$serverName=$row["server-name"];$serverIp=$row["server-ip"];$username=$row["username"];$password=$row["password"];$idRepeaterSubnetGroup=$row["id-repeater-subnet-group"];$idEmpresa=$row["id-empresa"];$idGruposEmpresa=$row["id-grupos-empresa"];
                $sql="SELECT * FROM `redesagi_facturacion`.`items_repeater_subnet_group` WHERE `id-repeater-subnets-group`='$idRepeaterSubnetGroup' ";
                if($queryResult=$this->mysqli->query($sql)){
                    $rowAray=$queryResult->fetch_Assoc();
                    $ipSegment=$rowAray["ip-segment"];
                    $ping=$rowAray["ping"];
                    $pingDate=$rowAray["ping-date"];
                    $remoteRoute=$rowAray["remote-route"];
                    $queryResult->free(); 
                }
                if(explode(".",$serverIp)[3]!=1){
                    $response[]=[ "id"=>"$id","fecha"=>"$fecha","serverName"=>"$serverName","serverIp"=>"$serverIp","username"=>"$username","password"=>"$password","idRepeaterSubnetGroup"=>"$idRepeaterSubnetGroup","idEmpresa"=>"$idEmpresa","idGruposEmpresa"=>"$idGruposEmpresa","ipSegment"=>"$ipSegment","ping"=>"$ping","ping-date"=>"$pingDate","remote-route"=>"$remoteRoute" ];
                }
            }
            $result->free();
        }
        return $response;
    }
    public function getRepeaterItem($table='vpn_targets',$item,$serverIp){
        $response=null;
        $sql="SELECT * from `redesagi_facturacion`.`$table` WHERE `$item`='$serverIp '";
        if($result=$this->mysqli->query($sql)){
            $row=$result->fetch_assoc();
            $response=$row["id"];
            $result->free();
        }
        return $response;
    }
    public function updateRepeater($tableName,$item,$operator="=",$value,$idTarget){
        $sql="UPDATE `redesagi_facturacion`.`$tableName` set `$item` $operator '$value' WHERE `id`='$idTarget' ";
        // print $sql;
        if ($this->mysqli->query($sql) === TRUE){
            $response= true;
        }else{
            $response= false;
        }
        return $response;
    }
    
}

// $repeaterObject=new Repeater("localhost", "mikrotik", "-Agwist2017Agwist1.", "redesagi_facturacion");
// $res=$repeaterObject->getServersList();//($table='vpn_targets',"server-ip","192.168.21.1");
// if($res){
//     var_dump(json_encode($res,JSON_PRETTY_PRINT));
// }else{
//     echo json_encode("fail"); 
// }

?>