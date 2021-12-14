<?php
require("/var/www/html"."/Client.php");
class Wallet extends Client{

    public function createWallet($idClient, $action, $value, $date, $hour, $idCajero, $source, $comment){
        $sql="INSERT INTO `redesagi_facturacion`.`wallet` (`id`, `id-client`, `action`, `value`, `date`, `hour`, `id-cajero`, `source`, `comment`) VALUES (NULL, '$idClient', '$action', '$value', '$date', '$hour', '$idCajero', '$source', '$comment')";
        //print "\n$sql\n";
        if ($this->mysqli->query($sql) === TRUE) {
            $last_id = $this->mysqli->insert_id;
            $idWallet=$last_id;
        }
        return $idWallet;////
    }
    public function getWallet($idWallet){

    }
    public function updateWallet($idWallet,$param,$value){
        $sql="UPDATE `redesagi_facturacion`.`wallet` SET `$param`='$value' WHERE `id`=$idWallet ";
        if ($this->mysqli->query($sql) === TRUE)
        		return true;
                return false;
    }
    public function deleteWallet($idWallet){
        $sql="DELETE FROM `redesagi_facturacion`.`wallet` WHERE `wallet`.`id` = $idWallet";
        $response=false;
        if ($this->mysqli->query($sql) === TRUE)
        		$response=$this->mysqli->affected_rows==1?["status"=>true,"affected"=>1]:["status"=>true,"affected"=>$this->mysqli->affected_rows];
        return $response;
    }
}
//
//print $wallet->createWallet($idClient=2, $action="substract", $value=50000, $date="2021/11/18", $hour="08:50 am", $idCajero=10, $source="Wallet Class", $comment="nadita")."\n";
// print $wallet->updateWallet("1","source","test from class")==true?"success":"fail!";
// print "\n".$wallet->updateClient($id_client=1,$param="cliente",$value="Adrian Guzmán Bermudez")==true?"success":"fail!"."\n";
// $response=$wallet->deleteWallet($idWallet=1);print $response["status"]==true && $response["affected"]==1?"success":"fail, row affected: {$response["affected"]} "; 
// print $wallet->getClientItem("100","cliente");//wallet-id,wallet-money
?>