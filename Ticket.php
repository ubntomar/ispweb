<?php 
class Ticket 
{
    private $mysqli;
    private $idClient;
    private $today;
    private $hourMin;
    private $administrador;
    public function __construct($server, $db_user, $db_pwd, $db_name,$idClient,$today,$hourMin,$administrador){
        $this->idClient=$idClient;
        $this->today=$today;
        $this->hourMin=$hourMin;
        $this->administrador=$administrador;
        $this->mysqli = new mysqli($server, $db_user, $db_pwd, $db_name);
		if ($this->mysqli->connect_errno) {
	    	echo "Failed to connect to MySQL: ";
            return false;
			}	
		mysqli_set_charset($this->mysqli,"utf8");
		date_default_timezone_set('America/Bogota'); 
        return true;
        
    }
    public function createTicketNewClient($phoneContact,$recomendacion=""){
        $message='Solicitud instalación servicio de Internet Banda Ancha';
        $hora_sugerida="";
        $sql="INSERT INTO `redesagi_facturacion`.`ticket` (`id-cliente`,`telefono-contacto`,`solicitud-cliente`,`fecha-creacion-ticket`,`hora-sugerida`,`hora`,`administrador`,`recomendaciones`,`status`) VALUES ('$this->idClient','$phoneContact','$message','$this->today','$hora_sugerida','$this->hourMin','$this->administrador','$recomendacion','ABIERTO')";
        //print "\n $sql \n";
        if($this->mysqli->query($sql)==true)
            return true;
        else return false;	

    }
    public function getTicket(){

    }
    public function updateTicket(){

    }
    public function deleteTicket(){

    }
}


?>