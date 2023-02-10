<?php
function head(){
    $content=?>
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
            <title>DevXm - Administrador ISP</title>

            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
            <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700|Roboto:300,400,500" rel="stylesheet">
            <link rel="stylesheet" href="https://cdn.datatables.net/1.10.9/css/jquery.dataTables.min.css">
            <link rel="stylesheet" href="https://cdn.datatables.net/responsive/1.0.7/css/responsive.dataTables.min.css">
            <link rel="stylesheet" href="bower_components/alertify/css/alertify.min.css" />

            <link rel="stylesheet" href="bower_components/alertify/css/themes/default.min.css" />
            <link rel="stylesheet" href="css/fontello.css">
            <link rel="stylesheet" href="css/estilos.css">
            <link rel="stylesheet" href="css/dataTables.checkboxes.css">
            <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
            <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
        </head>
    <?php
    return $content;
}

echo $content;



?>