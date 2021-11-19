<?php
class Html{
    public function __construct(){

    }
    public function head($path=""){
        $content='
        <head>
            <meta charset="UTF-8" />
            <meta name="viewport"
                content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />
            <title>Wisdev-Administrador ISP</title>

            <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.css">
            <link rel="stylesheet" href="'.$path.'css/fontello.css" />
            <link rel="stylesheet" href="'.$path.'css/animation.css">
            <link rel="stylesheet" href="'.$path.'css/bootstrap.css"> 
            <link rel="stylesheet" href="'.$path.'css/style.css" />
            <link rel="stylesheet" href="'.$path.'css/dataTables.checkboxes.css">
            <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700|Roboto:300,400,500" rel="stylesheet">
            
        </head>
        ';
        return $content; 
    }
    public function jsScript($path=""){
        $content='
        <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/responsive/1.0.7/js/dataTables.responsive.min.js"></script>
        <script src="https://cdn.datatables.net/fixedheader/3.1.5/js/dataTables.fixedHeader.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
        <script src="https://unpkg.com/axios/dist/axios.min.js"></script> 
        ';
        
        
        
        return $content;
    }
}





?>