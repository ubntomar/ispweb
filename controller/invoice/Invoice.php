<?php 
class Invoice 
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
    public function createInvoice($id, $idClient, $iva, $notas, $valorf, $idPeriodo, $concepto){
        $sql="INSERT INTO `redesagi_facturacion`.`factura_legal` (`id`, `id-afiliado`, `iva`, `notas`, `valorf`, `id-periodo`, `concepto`) VALUES (NULL, '$idClient', '$iva', '$notas', '$valorf', '$idPeriodo', '$concepto')";
        if ($this->mysqli->query($sql) === TRUE) {
                $last_id = $this->mysqli->insert_id;
                $idafiliado=$last_id;
            }
        return $idafiliado;
    }
    public function getInvoice(){

    }
    public function getInvoiceItem($idInvoice,$item){
        $sql="SELECT `$item` FROM `redesagi_facturacion`.`factura_legal` WHERE `id` = '$idInvoice' ";
        if($result=$this->mysqli->query($sql)){
            $row=$result->fetch_assoc();
            $value=$row[$item];
        }
        return $value;
    }
    public function updateInvoice($InvoiceId,$param,$value,$operator="="){
        // print "\nupdateInvoice $InvoiceId,$param,$value \n";
        $sql="UPDATE `redesagi_facturacion`.`factura_legal` set `$param` $operator '$value' WHERE `id`='$InvoiceId' ";
        // print $sql;
        if ($this->mysqli->query($sql) === TRUE){
            $response= true;
        }else{
            $response= false;
        }
        return $response;
        }
    public function deleteInvoice(){

    }
}

// $InvoiceeId=25;//$data["id"]; 
// //Update factura_legal table 
// $param="wallet-money";
// $value="1202";
// $res=$InvoiceObject->updateInvoice($InvoiceeId,$param,$value,$operator="=");
// if($res){
//     echo json_encode("success");
// }else{
//     echo json_encode("fail $InvoiceeId  -- $value "); 
// }



?>