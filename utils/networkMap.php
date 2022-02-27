<?php
 
  
$json='{
	"map": [{
		"Red-Retiro": [{
			"ruta": [
				{
					"name": "Vpn Entre Cloud Server y Leo Acacias  ",
					"ip": "192.168.42.11",
					"type": "Comunicacion Princiapl"
				},
				{
					"name": "Public Ip Red Leo ",
					"ip": "181.60.60.57",
					"type": "Modem de Claro"
				},
				{
					"name": "Server-Retiro",
					"ip": "192.168.30.1",
					"type": "Rb Admin"
				},
				{
					"name": "Cpe->Retiro ",
					"ip": "192.168.30.205",
					"type": "ptp"
				},
				{
					"name": "Retiro->Leo ",
					"ip": "192.168.30.209",
					"type": "ptp"
				},
				{
					"name": "Ap Caño Cachirre ¿?- Ojo por q puede ser daño la rb donde estoy conectado lo que genera que toda la 30 esté caida.",
					"ip": "192.168.30.13",
					"type": "ptp"
				},
				{
					"name": "Cpe Caño Cachirre",
					"ip": "192.168.30.248",
					"type": "ptp"
				},
				{
					"name": "Rb Admin Caño Cachirre",
					"ip": "192.168.30.2",
					"type": "ptp"
				}
			]
		}],
        "Red-Montecristo": [{
			"ruta": [
				{
					"name": "Vpn Entre Cloud Server y Maria Acacias   ",
					"ip": "192.168.42.10",
					"type": "Comunicacion Princiapl"
				},
				{
					"name": "Public Ip Maria ",
					"ip": "181.60.60.121",
					"type": "Modem de Claro"
				},
				{
					"name": "Rb-Server-Maria",
					"ip": "192.168.65.1",
					"type": "Rb Admin"
				},
				{
					"name": "Cpe Maria->Retiro ",
					"ip": "192.168.65.2",
					"type": "ptp"
				},
				{
					"name": "Ap Retiro->Maria . ",
					"ip": "192.168.65.3",
					"type": "ptp"
				},
				{
					"name": "Ptp Ap Retiro->Guamal Enlace principal 1 de 3 (Maria)",
					"ip": "192.168.65.111",
					"type": "ptp"
				},
				{
					"name": "Ptp Cpe Guamal->Retiro Enlace principal 1 de 3 (Maria)",
					"ip": "192.168.65.6",
					"type": "ptp"
				},
				{
					"name": "Servidor principal Guamal",
					"ip": "192.168.21.1",
					"type": "ptp"
				},
				{
					"name": "Cpe ptp Guamal->Montecristo",
					"ip": "192.168.21.7",
					"type": "ptp"
				},
				{
					"name": "Ap Ptp Montecristo->Guamal. Apagado todo Montecristo???",
					"ip": "192.168.21.44",
					"type": "ptp"
				},
				{
					"name": "Rb Switch (Montecristo) 1",
					"ip": "192.168.21.35",
					"type": "ptp"
				},
				{
					"name": "Rb Switch (Montecristo) 2",
					"ip": "192.168.21.36",
					"type": "ptp"
				},
				{
					"name": "Rb Switch (Montecristo) 3",
					"ip": "192.168.21.37",
					"type": "ptp"
				},
				{
					"name": "Ap ptp San Lorenzo",
					"ip": "192.168.21.110",
					"type": "ptp"
				},
				{
					"name": "Ap Guamal Castillo",
					"ip": "192.168.21.200",
					"type": "ptp"
				},
				{
					"name": "Netmetal Ap Humadea",
					"ip": "192.168.21.140",
					"type": "ptp"
				},
				{
					"name": "Ap Violetas 5280 nv2",
					"ip": "192.168.21.160",
					"type": "ptp"
				},
				{
					"name": "Ap Humadea 5445 Ubiquiti",
					"ip": "192.168.21.22",
					"type": "ptp"
				}
			]
		}],
        "Red-Orlando": [{
			"ruta": [{
					"name": "Public Ip Maria ",
					"ip": "181.60.60.121",
					"type": "Modem de Claro"
				},
				{
					"name": "Rb-Server-Maria",
					"ip": "192.168.65.1",
					"type": "Rb Admin"
				},
				{
					"name": "Cpe->Retiro ",
					"ip": "192.168.65.2",
					"type": "ptp"
				},
				{
					"name": "Retiro->Maria . ",
					"ip": "192.168.65.3",
					"type": "ptp"
				},
				{
					"name": "Retiro->Guamal Enlace principal 1 de 3 (Maria)",
					"ip": "192.168.65.111",
					"type": "ptp"
				},
				{
					"name": "Guamal-Retiro Enlace principal 1 de 3 (Maria)",
					"ip": "192.168.65.6",
					"type": "ptp"
				},
				{
					"name": "Servidor principal Guamal",
					"ip": "192.168.21.1",
					"type": "ptp"
				},
				{
					"name": "Cpe ptp Guamal->Orlando",
					"ip": "192.168.26.160",
					"type": "ptp"
				},
				{
					"name": "Ap Ptp Orlando->Guamal",
					"ip": "192.168.26.161",
					"type": "ptp"
				},
				{
					"name": "Rb Switch (Orlando) 1",
					"ip": "192.168.26.88",
					"type": "ptp"
				},
				{
					"name": "Rb Switch (Orlando) 2",
					"ip": "192.168.26.91",
					"type": "ptp"
				},
				{
					"name": "Rb Switch (Orlando) 3",
					"ip": "192.168.26.92",
					"type": "ptp"
				},
				{
					"name": "Netbox Orlando->San Juan",
					"ip": "192.168.26.8",
					"type": "ptp"
				},
				{
					"name": "Red Orlando antena Antena 5080",
					"ip": "192.168.26.9",
					"type": "ptp"
				},
				{
					"name": "Red Orlando Antena 5450",
					"ip": "192.168.26.10",
					"type": "ptp"
				},
				{
					"name": "Red Orlando Antena Ap Danubio mktik Lhg Xl",
					"ip": "192.168.26.20",
					"type": "ptp"
				},
				{
					"name": "Red Orlando Ap Arenales",
					"ip": "192.168.26.2",
					"type": "ptp"
				},
				{
					"name": "Red Orlando Ap 5590",
					"ip": "192.168.26.18",
					"type": "ptp"
				},
				{
					"name": "Red Orlando Ap El Carmen Guamal",
					"ip": "192.168.26.202",
					"type": "ptp"
				},
				{
					"name": "Red Orlando Ap Granada",
					"ip": "192.168.26.203",
					"type": "ptp"
				}
			]
		}] 
	}] 
}';

//  $cc=",
//  "Red-Paisa-Arturo": [{
// 	 "ruta": [{
// 			 "name": "Public Ip Paisa ",
// 			 "ip": "181.51.57.226",
// 			 "type": "Modem de Claro"
// 		 },
// 		 {
// 			 "name": "Rb-Server-paisa",
// 			 "ip": "192.168.84.1",
// 			 "type": "Rb Admin Paisa Acacias"
// 		 },
// 		 {
// 			 "name": "Cpe->Retiro ",
// 			 "ip": "192.168.84.2",
// 			 "type": "ptp"
// 		 },
// 		 {
// 			 "name": "Retiro->Paisa . ",
// 			 "ip": "192.168.84.3",
// 			 "type": "ptp"
// 		 },
// 		 {
// 			 "name": "Retiro Rb Bridge",
// 			 "ip": "192.168.84.4",
// 			 "type": "ptp"
// 		 },
// 		 {
// 			 "name": "Retiro->Guamal  Enlace principal 2 de 3 (Paisa)",
// 			 "ip": "192.168.84.6",
// 			 "type": "ptp"
// 		 },
// 		 {
// 			 "name": "Guamal->Retiro Enlace principal 2 de 3 (paisa)",
// 			 "ip": "192.168.84.7",
// 			 "type": "ptp"
// 		 }
// 	 ]
//  }]"


?>