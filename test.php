<!-- <?php>
//////////////////////////////////////////////////////NO BORRAR SIN ANTES HACER BACKUP////////////////////////////////////////////////////////////////////////////////////////

/interface ethernet
set [ find default-name=ether1 ] comment="***Cll 8 # 35 37 Independencia Casa \
    Paisa 100Mbps/10Mbps-Titular : Omar 181.51.57.226/24.***Se\F1ora Nury- 120\
    Mbps/20Mbps Titular: Yesica 181.51.57.188/24" name=ISP1-Ether1


/interface l2tp-server server
set enabled=yes ipsec-secret=-Agwist2017 use-ipsec=yes

/ip address
add address=181.51.57.226/24  interface=ISP1-Ether1 network=181.51.57.0 comment="Ip publica de do\F1a Nury"
add address=181.58.212.170/24 interface=ISP1-Ether1 network=181.58.212.0

/ip firewall mangle
add action=accept chain=prerouting comment=A dst-address=10.10.11.0/24
add action=accept chain=prerouting comment=A dst-address=192.168.42.0/24
add action=accept chain=prerouting comment=B dst-address=181.58.212.0/24 in-interface=BRIDGE-LAN
add action=accept chain=prerouting comment=C dst-address=181.51.57.0/24  in-interface=BRIDGE-LAN
add action=mark-connection chain=prerouting comment=D connection-mark=no-mark dst-address=181.51.57.226  in-interface=ISP1-Ether1 new-connection-mark=ISP1_conn passthrough=yes
add action=mark-connection chain=prerouting comment=E connection-mark=no-mark dst-address=181.58.212.170 in-interface=ISP1-Ether1 new-connection-mark=ISP2_conn passthrough=yes
add action=mark-connection chain=prerouting comment=G connection-mark=no-mark dst-address-type=!local in-interface=BRIDGE-LAN new-connection-mark=ISP1_conn passthrough=yes per-connection-classifier=both-addresses:2/0
add action=mark-connection chain=prerouting comment=H connection-mark=no-mark dst-address-type=!local in-interface=BRIDGE-LAN new-connection-mark=ISP2_conn passthrough=yes per-connection-classifier=both-addresses:2/1
add action=mark-routing chain=prerouting comment=J connection-mark=ISP1_conn in-interface=BRIDGE-LAN new-routing-mark=to_ISP1 passthrough=yes
add action=mark-routing chain=prerouting comment=K connection-mark=ISP2_conn in-interface=BRIDGE-LAN new-routing-mark=to_ISP2 passthrough=yes
add action=mark-routing chain=output comment=M connection-mark=ISP1_conn new-routing-mark=to_ISP1 passthrough=yes
add action=mark-routing chain=output comment=N connection-mark=ISP2_conn new-routing-mark=to_ISP2 passthrough=yes

/ip route
add check-gateway=ping distance=1 gateway=181.51.57.1 routing-mark=to_ISP1
add check-gateway=ping distance=1 gateway=181.58.212.1 routing-mark=to_ISP2
add distance=2 gateway=181.51.57.1

add distance=1 dst-address=192.168.16.0/24 gateway=192.168.84.8
add distance=1 dst-address=192.168.17.0/24 gateway=192.168.84.8
add distance=1 dst-address=192.168.20.0/24 gateway=192.168.84.8
add distance=1 dst-address=192.168.21.0/24 gateway=192.168.84.8
add distance=1 dst-address=192.168.26.0/24 gateway=192.168.84.8
add distance=1 dst-address=192.168.40.0/24 gateway=192.168.84.8
add distance=1 dst-address=192.168.50.0/24 gateway=192.168.84.8
add distance=1 dst-address=192.168.85.0/24 gateway=192.168.84.8
add distance=1 dst-address=192.168.88.0/24 gateway=192.168.84.8

/////////////////////////////////////////////////////////////////////////////RED MARIA ACACIAS////////////////////////////////////////////////////////////////////////////////

#181.60.60.121/24
/interface ethernet
set [ find default-name=ether1 ] name=ISP1-Ether1


/ip address
add address=181.59.60.27/24 interface=ISP1-Ether1 network=181.59.60.0

/ip firewall mangle
add action=accept chain=prerouting comment=A dst-address=10.10.11.0/24
add action=accept chain=prerouting comment=A dst-address=192.168.42.0/24
add action=accept chain=prerouting comment=B dst-address=181.60.60.0/24 in-interface=BRIDGE-LAN
add action=accept chain=prerouting comment=C dst-address=181.59.60.0/24  in-interface=BRIDGE-LAN
add action=mark-connection chain=prerouting comment=D connection-mark=no-mark dst-address=181.60.60.121  in-interface=ISP1-Ether1 new-connection-mark=ISP1_conn passthrough=yes
add action=mark-connection chain=prerouting comment=E connection-mark=no-mark dst-address=181.59.60.27   in-interface=ISP1-Ether1 new-connection-mark=ISP2_conn passthrough=yes
add action=mark-connection chain=prerouting comment=G connection-mark=no-mark dst-address-type=!local in-interface=BRIDGE-LAN new-connection-mark=ISP1_conn passthrough=yes per-connection-classifier=both-addresses:2/0
add action=mark-connection chain=prerouting comment=H connection-mark=no-mark dst-address-type=!local in-interface=BRIDGE-LAN new-connection-mark=ISP2_conn passthrough=yes per-connection-classifier=both-addresses:2/1
add action=mark-routing chain=prerouting comment=J connection-mark=ISP1_conn in-interface=BRIDGE-LAN new-routing-mark=to_ISP1 passthrough=yes
add action=mark-routing chain=prerouting comment=K connection-mark=ISP2_conn in-interface=BRIDGE-LAN new-routing-mark=to_ISP2 passthrough=yes
add action=mark-routing chain=output comment=M connection-mark=ISP1_conn new-routing-mark=to_ISP1 passthrough=yes
add action=mark-routing chain=output comment=N connection-mark=ISP2_conn new-routing-mark=to_ISP2 passthrough=yes

/ip route
add check-gateway=ping distance=1 gateway=181.60.60.1 routing-mark=to_ISP1
add check-gateway=ping distance=1 gateway=181.59.60.1 routing-mark=to_ISP2
add distance=2 gateway=181.60.60.1 -->




<section>
    <div class="section-title">
        <h1>AREA DE PAGOS</h1>
    </div>
    <div class=box-container>

        <div class="box">
            <div class="title">
                <h3><i class="icon-wrench"></i> </h3>
            </div>
            <div>
                <div class="box-content">
                    <table id="clientList" class="display compact stripe cell-border" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Nombre Titular</th>
                                <th>Dirección</th>
                                <th>Antiguedad en meses</th>
                                <th>Saldo</th>
                                <th>Fecha de Ingreso</th>
                                <th>Corte</th>
                                <th>Cedula Titular</th>
                                <th>Telefono</th>
                                <th>Pay</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                        $sql="SELECT * from `jurisdicciones` WHERE `jurisdicciones`.`id` = $jurisdiccion";
                        if($result=$mysqli->query($sql)){
                            $row=$result->fetch_assoc();
                            $grupo=$row["id-grupo-area"];
                            $result->free();
                            $sql=" SELECT * FROM `items_grupo_area` WHERE `items_grupo_area`.`id-grupo` = $grupo";
                            if($result=$mysqli->query($sql)){
                                $num_rows = $result->num_rows;
                                $arrayidArea=" AND (";
                                $cn=0;
                                while($row=$result->fetch_assoc()){
                                    $cn+=1;
                                    $idArea=$row["id-area"];
                                    $arrayidArea.=($cn==$num_rows)? "`id_client_area` = $idArea ) AND `afiliados`.`id-empresa` = $empresa ":"`id_client_area` = $idArea OR";
                                }
                                $result->free();
                            }
                        }
                        $sql = "SELECT * FROM `afiliados` WHERE `afiliados`.`activo`=1 AND `afiliados`.`eliminar`!=1  $arrayidArea  ORDER BY `afiliados`.`id`  ASC LIMIT 20 ";
                        if ($result = $mysqli->query($sql)) {
                            while ($row = $result->fetch_assoc()) {
                                $idCliente = $row["id"];
                                $cedula = $row["cedula"];
                                $telefono = $row["telefono"];
                                $registration_date = $row["registration-date"];
                                $corte = $row["corte"];
                                $idGRoup = ($row["id-repeater-subnets-group"]==0)?"G-0":"";
                                $standby = $row["standby"];
                                $ip=$row["ip"];
                                $pingDate=$row["pingDate"];
                                $pingCurrentStatus=($pingDate==$today) ? "Ping ok!" : "<small class=\"bg-danger text-white p-1\">Ping Error</small>";   
                                $style_cell = "";
                                $style2_cell = "";
                                $ts1 = strtotime($registration_date);
                                $ts2 = strtotime($today);

                                $year1 = date('Y', $ts1);
                                $year2 = date('Y', $ts2);

                                $month1 = date('m', $ts1);
                                $month2 = date('m', $ts2);

                                $registration_day = date('d', $ts1);
                                if ($registration_date != "0000-00-00") {

                                    $diff = (($year2 - $year1) * 12) + ($month2 - $month1);
                                } else
                                    $diff = "999";

                                $sqlt = "SELECT * FROM `factura`  WHERE `factura`.`id-afiliado`='$idCliente' AND `factura`.`cerrado`='0'  ORDER BY `factura`.`id-factura` DESC ";
                                $vtotal = 0;
                                $cont = 0;
                                if ($resultt = $mysqli->query($sqlt)) {
                                    while ($rowft = $resultt->fetch_assoc()) {
                                        $cont += 1;
                                        $idFactura = $rowft["id-factura"];
                                        $periodo = $rowft["periodo"];
                                        $saldo = $rowft["saldo"];
                                        $vtotal += $saldo;
                                    }
                                    $resultt->free();
                                }
                                if ($vtotal > 0 && $diff == 0) {
                                    $style_cell = "class=\"text-warning bg-dark\" ";
                                }
                                if ($vtotal > 0 && $diff == 1 && $corte == 1 && $registration_day > 15) {
                                    $style_cell = "class=\"text-info bg-dark\" ";
                                }

                                if ($row["eliminar"] == 1) {
                                    $statusText = "Inactivo";
                                    $style = "border-dark text-secundary "; 
                                } else {
                                    $style = "border-primary text-success ";
                                    $statusText = "<p><small class=\"px-1 border $style rounded \">Activo</small></p>";
                                }
                                if ($row["suspender"] == 1) {
                                    $today = date("Y-m-d");
                                    $date1 = new DateTime($today);
                                    $date2 = new DateTime($row["suspenderFecha"]);
                                    $days  = $date2->diff($date1)->format('%a'); 
                                    ($days==0)? $d="-Hoy":$d="";
                                    $style = "border-info text-danger ";
                                    $statusText = "<p><small class=\"px-1 border $style rounded \">Cortado-[ $days días $d ]</small></p>";
                                }
                                $textCedula = $cedula;
                                if ($cedula == 0) {
                                    $textCedula = "<input class=\"form-control form-control-sm cedula" . $row["id"] . " px-2\" type=\"text\" value=\"\" >";
                                } else {
                                    $textCedula = "<input class=\"form-control form-control-sm cedula" . $row["id"] . " px-2\" type=\"text\" value=\"$cedula\"  	 >";
                                }

                                $textTelefono = $telefono;
                                if ($telefono == "") {
                                    $textTelefono = "<input class=\"form-control form-control-sm telefono" . $row["id"] . " px-2 \" type=\"text\" value=\"\" >";
                                } else {
                                    $textTelefono = "<input class=\"form-control form-control-sm telefono" . $row["id"] . " px-2 \" type=\"text\" value=\"$telefono\"   >";
                                }

                                $telefono = $row["telefono"]; 
                                echo "<tr class=\"text-center  \">";
                                echo "<td> {$row["cliente"]}  {$row["apellido"]} $statusText </td>";
                                echo "<td><small>{$row["direccion"]} {$row["ciudad"]} -{$row['id']}</small></td>"; 
                                echo "<td>$diff</td>";
                                echo "<td><small 	$style_cell >$$vtotal</small><div><a href=\"#\" class=\"text-primary icon-client \" data-toggle=\"modal\" 	data-target=\"#payModal\" data-id=\"" . $row["id"] . "\"><i class=\"icon-money\"></i></a></div></td>";
                                // echo "<td><small>$registration_date</small><div class=\"border border-info rounded p-1 bg-white\"><p class=\"mb-0\"><small><input type=\"text\" value=\"\" placeholder=\"$ip\" id=\"{$row['id']}\"
                                // class=\"form-control form-control-sm ml-1\"></small></p><button v-on:click=\"ipUpdate({$row['id']})\" class=\"border border-rounded icon-arrows-ccw\"></button>
                                // <p class=\"mb-0\"><small>$pingCurrentStatus</small></p></div></td>"; 
                                echo "<td><small>$registration_date</small><div class=\"border border-info rounded p-1 bg-white\"><p class=\"mb-0\"><small>ip:'$ip'</small></p><p class=\"mb-0\"><small>$pingCurrentStatus</small></p></div></td>";    
                                echo "<td class=\" align-middle \"><small>C-" . $row["corte"] . "*$standby</small><p><small>$idGRoup</small></p></td>";
                                echo "<td class=\" align-middle \">$textCedula</td>"; 
                                echo "<td class=\" align-middle \">$textTelefono</td>";          
                                echo "<td class=\" align-middle \"><a href=\"#\" class=\"text-primary icon-client \" data-toggle=\"modal\" 	data-target=\"#payModal\" data-id=\"" . $row["id"] . "\"><i class=\"icon-money\"></i></a></td>";
                                echo "</tr>";
                            }
                            $result->free(); 
                        }
                        ?>
                            <!-- Modal -->
                            <div class="modal fade" id="payModal" tabindex="-1" role="dialog"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Detalles de Pago</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>

                                        <div class="modal-body">
                                            <div class="fetched-data"></div>

                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" id="cancelbutton" class="btn btn-secondary"
                                                data-dismiss="modal">Cancelar</button>
                                            <button type="button" id="paybutton" class="btn btn-primary">Pagar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </tbody>
                    </table>

                </div>
            </div>
        </div>


    </div>
</section>










<section>
    <div class="section-title">
        <h1>AREA DE PAGOS</h1>
    </div>
    <div class=box-container>

        <div class="box">
            <div class="title">
                <h3><i class="icon-wrench"></i> </h3>
            </div>
            <div>
                <div class="box-content">
                    <table id="clientList" class="display compact stripe cell-border" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Nombre Titular</th>
                                <th>Dirección</th>
                                <th>Antiguedad en meses</th>
                                <th>Saldo</th>
                                <th>Fecha de Ingreso</th>
                                <th>Corte</th>
                                <th>Cedula Titular</th>
                                <th>Telefono</th>
                                <th>Pay</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
									$sql="SELECT * from `jurisdicciones` WHERE `jurisdicciones`.`id` = $jurisdiccion";
									if($result=$mysqli->query($sql)){
										$row=$result->fetch_assoc();
										$grupo=$row["id-grupo-area"];
										$result->free();
										$sql=" SELECT * FROM `items_grupo_area` WHERE `items_grupo_area`.`id-grupo` = $grupo";
										if($result=$mysqli->query($sql)){
											$num_rows = $result->num_rows;
											$arrayidArea=" AND (";
											$cn=0;
											while($row=$result->fetch_assoc()){
												$cn+=1;
												$idArea=$row["id-area"];
												$arrayidArea.=($cn==$num_rows)? "`id_client_area` = $idArea ) AND `afiliados`.`id-empresa` = $empresa ":"`id_client_area` = $idArea OR";
											}
											$result->free();
										}
									}
									$sql = "SELECT * FROM `afiliados` WHERE `afiliados`.`activo`=1 AND `afiliados`.`eliminar`!=1  $arrayidArea  ORDER BY `afiliados`.`id`  ASC ";
									if ($result = $mysqli->query($sql)) {
										while ($row = $result->fetch_assoc()) {
											$idCliente = $row["id"];
											$cedula = $row["cedula"];
											$telefono = $row["telefono"];
											$registration_date = $row["registration-date"];
											$corte = $row["corte"];
											$idGRoup = ($row["id-repeater-subnets-group"]==0)?"G-0":"";
											$standby = $row["standby"];
											$ip=$row["ip"];
											$pingDate=$row["pingDate"];
											$pingCurrentStatus=($pingDate==$today) ? "Ping ok!" : "<small class=\"bg-danger text-white p-1\">Ping Error</small>";   
											$style_cell = "";
											$style2_cell = "";
											$ts1 = strtotime($registration_date);
											$ts2 = strtotime($today);

											$year1 = date('Y', $ts1);
											$year2 = date('Y', $ts2);

											$month1 = date('m', $ts1);
											$month2 = date('m', $ts2);

											$registration_day = date('d', $ts1);
											if ($registration_date != "0000-00-00") {

												$diff = (($year2 - $year1) * 12) + ($month2 - $month1);
											} else
												$diff = "999";

											$sqlt = "SELECT * FROM `factura`  WHERE `factura`.`id-afiliado`='$idCliente' AND `factura`.`cerrado`='0'  ORDER BY `factura`.`id-factura` DESC";
											$vtotal = 0;
											$cont = 0;
											if ($resultt = $mysqli->query($sqlt)) {
												while ($rowft = $resultt->fetch_assoc()) {
													$cont += 1;
													$idFactura = $rowft["id-factura"];
													$periodo = $rowft["periodo"];
													$saldo = $rowft["saldo"];
													$vtotal += $saldo;
												}
												$resultt->free();
											}
											if ($vtotal > 0 && $diff == 0) {
												$style_cell = "class=\"text-warning bg-dark\" ";
											}
											if ($vtotal > 0 && $diff == 1 && $corte == 1 && $registration_day > 15) {
												$style_cell = "class=\"text-info bg-dark\" ";
											}

											if ($row["eliminar"] == 1) {
												$statusText = "Inactivo";
												$style = "border-dark text-secundary "; 
											} else {
												$style = "border-primary text-success ";
												$statusText = "<p><small class=\"px-1 border $style rounded \">Activo</small></p>";
											}
											if ($row["suspender"] == 1) {
												$today = date("Y-m-d");
												$date1 = new DateTime($today);
												$date2 = new DateTime($row["suspenderFecha"]);
												$days  = $date2->diff($date1)->format('%a'); 
												($days==0)? $d="-Hoy":$d="";
												$style = "border-info text-danger ";
												$statusText = "<p><small class=\"px-1 border $style rounded \">Cortado-[ $days días $d ]</small></p>";
											}
											$textCedula = $cedula;
											if ($cedula == 0) {
												$textCedula = "<input class=\"form-control form-control-sm cedula" . $row["id"] . " px-2\" type=\"text\" value=\"\" >";
											} else {
												$textCedula = "<input class=\"form-control form-control-sm cedula" . $row["id"] . " px-2\" type=\"text\" value=\"$cedula\"  	 >";
											}

											$textTelefono = $telefono;
											if ($telefono == "") {
												$textTelefono = "<input class=\"form-control form-control-sm telefono" . $row["id"] . " px-2 \" type=\"text\" value=\"\" >";
											} else {
												$textTelefono = "<input class=\"form-control form-control-sm telefono" . $row["id"] . " px-2 \" type=\"text\" value=\"$telefono\"   >";
											}

											$telefono = $row["telefono"]; 
											echo "<tr class=\"text-center  \">";
											echo "<td> {$row["cliente"]}  {$row["apellido"]} $statusText</td>";
											echo "<td><small>{$row["direccion"]} {$row["ciudad"]} -{$row['id']}</small></td>"; 
											echo "<td>$diff</td>";
											echo "<td><small 	$style_cell >$$vtotal</small><div><a href=\"#\" class=\"text-primary icon-client \" data-toggle=\"modal\" 	data-target=\"#payModal\" data-id=\"" . $row["id"] . "\"><i class=\"icon-money\"></i></a></div></td>";
											// echo "<td><small>$registration_date</small><div class=\"border border-info rounded p-1 bg-white\"><p class=\"mb-0\"><small><input type=\"text\" value=\"\" placeholder=\"$ip\" id=\"{$row['id']}\"
                                            // class=\"form-control form-control-sm ml-1\"></small></p><button v-on:click=\"ipUpdate({$row['id']})\" class=\"border border-rounded icon-arrows-ccw\"></button>
											// <p class=\"mb-0\"><small>$pingCurrentStatus</small></p></div></td>"; 
											echo "<td><small>$registration_date</small><div class=\"border border-info rounded p-1 bg-white\"><p class=\"mb-0\"><small>ip:'$ip'</small></p><p class=\"mb-0\"><small>$pingCurrentStatus</small></p></div></td>";    
											echo "<td class=\" align-middle \"><small>C-" . $row["corte"] . "*$standby</small><p><small>$idGRoup</small></p></td>";
											echo "<td class=\" align-middle \">$textCedula</td>"; 
											echo "<td class=\" align-middle \">$textTelefono</td>";          
											echo "<td class=\" align-middle \"><a href=\"#\" class=\"text-primary icon-client \" data-toggle=\"modal\" 	data-target=\"#payModal\" data-id=\"" . $row["id"] . "\"><i class=\"icon-money\"></i></a></td>";
											echo "</tr>";
										}
										$result->free(); 
									}
									?>
                            <!-- Modal -->
                            <div class="modal fade" id="payModal" tabindex="-1" role="dialog"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Detalles de Pago</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>

                                        <div class="modal-body">
                                            <div class="fetched-data"></div>

                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" id="cancelbutton" class="btn btn-secondary"
                                                data-dismiss="modal">Cancelar</button>
                                            <button type="button" id="paybutton" class="btn btn-primary">Pagar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </tbody>
                    </table>

                </div>
            </div>
        </div>


    </div>
</section>



















<!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
    Launch demo modal
</button>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                ...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>