<?php
session_start();
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
		header('Location: ../login/index.php');
		exit;
	} else {
	$user = $_SESSION['username'];
	$name = $_SESSION['name'];
	$lastName = $_SESSION['lastName'];
	$role = $_SESSION['role'];
	$jurisdiccion = $_SESSION['jurisdiccion'];
	$empresa = $_SESSION['empresa'];
    $idCajero = $_SESSION['idCajero'];
    $convenio=$_SESSION['convenio'];
}
if($_SESSION['role']=='tecnico'){
	header('Location: tick.php');
}
require("../login/db.php");
include("../dateHuman.php");

$mysqli = new mysqli($server, $db_user, $db_pwd, $db_name);
if ($mysqli->connect_errno) {
    echo json_encode(["error" => "Error de conexión: " . $mysqli->connect_error]);
    exit;
}

mysqli_set_charset($mysqli, "utf8");
date_default_timezone_set('America/Bogota');
$today = date("Y-m-d");
$yesterday = date("Y-m-d", strtotime("-1 days"));

// ** Parámetros enviados por DataTables **
$draw = intval($_POST['draw']);
$start = intval($_POST['start']);
$length = intval($_POST['length']);
$search_value = $_POST['search']['value'];
$order_column_index = $_POST['order'][0]['column'];
$order_direction = $_POST['order'][0]['dir'];

// Columnas para ordenar
$columns = ['cliente', 'direccion', 'cedula', 'saldo', 'registration-date', 'corte', 'cedula', 'telefono'];
$order_column = $columns[$order_column_index] ?? 'id';

// ** Filtro de jurisdicción y área **
$jurisdiccion = $_SESSION['jurisdiccion'];
$sql = "SELECT * FROM `jurisdicciones` WHERE `jurisdicciones`.`id` = $jurisdiccion";
$arrayidArea = "";

if ($result = $mysqli->query($sql)) {
    $row = $result->fetch_assoc();
    $grupo = $row["id-grupo-area"];
    $result->free();

    $sql = "SELECT * FROM `items_grupo_area` WHERE `items_grupo_area`.`id-grupo` = $grupo";
    if ($result = $mysqli->query($sql)) {
        $num_rows = $result->num_rows;
        $arrayidArea = " AND (";
        $cn = 0;
        while ($row = $result->fetch_assoc()) {
            $cn += 1;
            $idArea = $row["id-area"];
            $arrayidArea .= ($cn == $num_rows) ? "`id_client_area` = $idArea ) " : "`id_client_area` = $idArea OR ";
        }
        $result->free();
    }
}

// Consulta base con filtros
$sql_base = "SELECT * FROM `afiliados` WHERE `id-empresa` = {$_SESSION['empresa']} AND `activo` = 1 AND `eliminar` != 1 $arrayidArea";

// ** Agregar búsqueda si aplica **
if (!empty($search_value)) {
    $sql_base .= " AND (`cliente` LIKE '%$search_value%' OR `cedula` LIKE '%$search_value%' OR `direccion` LIKE '%$search_value%')";
}

// Total de registros sin paginación
$total_query = $mysqli->query($sql_base);
$total_records = $total_query->num_rows;

// Consulta con paginación y ordenación
$sql_paginated = $sql_base . " ORDER BY `$order_column` $order_direction LIMIT $start, $length";
$result = $mysqli->query($sql_paginated);

// Construir respuesta JSON
$data = [];
while ($row = $result->fetch_assoc()) {
    // Datos del cliente
    $idCliente = $row["id"];
    $cedula = $row["cedula"];
    $telefono = $row["telefono"];
    $registration_date = $row["registration-date"];
    $corte = $row["corte"];
    $idGRoup = ($row["id-repeater-subnets-group"] == 0) ? "G-0" : "";
    $standby = $row["standby"];
    $reconectedDate = $row["reconected-date"];
    $ip = $row["ip"];
    $signal = (($row["signal-strenght"] * -1) > 0 && ($row["signal-strenght"] * -1) < 70) ? $row["signal-strenght"] . " buena" : $row["signal-strenght"] . " mala";
    if ($row["signal-strenght"] * 1 == 0) $signal = "?";
    $sshLoginType = ($signal == "?") ? $row["ssh-login-type"] . "-" : $row["ssh-login-type"];
    if ($row["ssh-login-type"] == "router") $signal = "NO APLICA";

    $pingDate = $row["pingDate"];
    $pingResponseTime = $row["ping"];
    $suspenderStatus = (!$row["suspender-list-status"]) ? "FALSO!, algo pasa." : "OK en lista morosos.";

    // Ping status
    $timeElapsed = $pingDate ? (($sincedexDays = get_date_diff($pingDate, $today, 2)) == "") ? "Último ping: Hoy" : "Ping Error desde hace $sincedexDays" : "Sin registro ping";
    $pingCurrentStatus = (($pingDate == $today || $pingDate == $yesterday) && $pingResponseTime) ?
        "<small class='bg-success text-white p-1'>Ping OK->$pingDate:$pingResponseTime ms</small>" :
        "<small class='bg-danger text-white p-1'>$timeElapsed</small>";

    // Calcular meses de diferencia
    $ts1 = strtotime($registration_date);
    $ts2 = strtotime($today);
    $year1 = date('Y', $ts1);
    $year2 = date('Y', $ts2);
    $month1 = date('m', $ts1);
    $month2 = date('m', $ts2);
    $registration_day = date('d', $ts1);
    $diff = ($registration_date != "0000-00-00") ? (($year2 - $year1) * 12) + ($month2 - $month1) : "999";

    // Saldo total
    $sqlt = "SELECT * FROM `factura` WHERE `factura`.`id-afiliado`='$idCliente' AND `factura`.`cerrado`='0' ORDER BY `factura`.`id-factura` DESC";
    $vtotal = 0;
    if ($resultt = $mysqli->query($sqlt)) {
        while ($rowft = $resultt->fetch_assoc()) {
            $vtotal += $rowft["saldo"];
        }
        $resultt->free();
    }

    // Estado visual del saldo
    $style_cell = ($vtotal > 0 && $diff == 0) ? "class='text-warning bg-dark font-weight-bold h6'" : "class='font-weight-bold h6'";
    if ($vtotal > 0 && $diff == 1 && $corte == 1 && $registration_day > 15) {
        $style_cell = "class='text-info bg-dark font-weight-bold h6'";
    }

    // Estado del cliente
    if ($row["eliminar"] == 1) {
        $statusText = "<p><small class='px-1 border border-dark text-secondary rounded'>Inactivo</small></p>";
    } else {
        $statusText = "<p><small class='px-1 border border-primary text-success rounded'>Activo</small></p>";
    }

    $reconectedBox = ($reconectedDate && $reconectedDate != "1999-01-01" && $reconectedDate != NULL) ?
        "<div><small class='px-1 border border-primary text-success rounded'>ULTIMO REGISTRO RECONEXION: $reconectedDate</small></div>" : "";

    // Botón de pago
    $payButton = "<a href='#' class='text-primary icon-client' data-toggle='modal' data-target='#payModal' data-id='$idCliente'><i class='icon-money h3'></i></a>";

    $data[] = [
        "{$row['cliente']} {$row['apellido']} $statusText $reconectedBox",
        "{$row['direccion']} {$row['ciudad']} - {$row['id']}",
        "C.C: {$row['cedula']} Tel: {$row['telefono']}",
        "<small $style_cell>$$vtotal</small>$payButton",
        "<small>$registration_date</small><div class='border border-info rounded p-1 bg-white'><p class='mb-0'><small>ip: $ip</small></p><p class='mb-0'><small>$pingCurrentStatus</small></p><p class='mb-0'><small>Receptor: $sshLoginType</small></p><p class='mb-0'><small>Señal: $signal</small></p></div>",
        "C-{$row["corte"]}*$standby <p><small>$idGRoup</small></p>",
        "<input class='form-control form-control-sm' type='text' value='{$cedula}'>",
        "<input class='form-control form-control-sm' type='text' value='{$telefono}'>"
    ];
}

$response = [
    "draw" => $draw,
    "recordsTotal" => $total_records,
    "recordsFiltered" => $total_records,
    "data" => $data
];

echo json_encode($response);
exit;
