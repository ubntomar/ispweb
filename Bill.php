<?php 
class Bill 
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
        return true;
        
    }
    public function createBill($id_client,$periodo,$notas,$valorf,$valorp,$saldo,$cerrado,$fechaPago,$iva,$descuento,$fechaCierre,$vencidos){
        //print "\n generar_factura id_client,periodo,notas,valorf,valorp,saldo,cerrado $id_client,$periodo,$notas,$valorf,$valorp,$saldo,$cerrado";

        $sql1 = "INSERT INTO `redesagi_facturacion`.`factura` (`id-factura`, `id-afiliado`, `fecha-pago`, `iva`, `notas`, `descuento`, `valorf`, `valorp`, `saldo`, `cerrado`, `fecha-cierre`, `vencidos`, `periodo`) VALUES 
																		  (NULL,'$id_client', NULLIF('$fechaPago', ''), '$iva', '$notas', '$descuento', '$valorf', '$valorp', '$saldo', '$cerrado', NULLIF('$fechaCierre', '') , '$vencidos', '$periodo');";
        //print "\n $sql1 \n";
        if($this->mysqli->query($sql1)==true)
            return true;
        else return false;	

    }
    public function getBill($idClient,$aditionalCondition="1"){
        $array=null;
        $sql="SELECT * FROM `redesagi_facturacion`.`factura` WHERE `id-afiliado`='$idClient' AND $aditionalCondition ";
        if($result=$this->mysqli->query($sql)){
            while ($row=$result->fetch_assoc()) {
                $array[]=$row;
            }
        }
        return $array;
    }
    public function updateBill($idClient,$item,$value){
        $sql="UPDATE `redesagi_facturacion`.`factura` SET `factura`.`$item` = '$value' WHERE `factura`.`id-afiliado` = '$idClient' ";
        if($this->mysqli->query($sql)==true)
            return true;
        else return false;
    }
    public function deleteBill($idBill){
        $sql="DELETE FROM `redesagi_facturacion`.`factura` WHERE `factura`.`id-factura`=$idBill";
        if($this->mysqli->query($sql)==true)
            return true;
        else return false;
    }
}


?>