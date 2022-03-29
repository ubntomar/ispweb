<?php
class Html{
    public function __construct(){

    } 
    public function head($path=""){ 
        $content='
        <head>
            <meta charset="UTF-8" />
            <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />
            <title>Wisdev-Administrador ISP</title>
            <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700|Roboto:300,400,500" rel="stylesheet" />
            <link rel="stylesheet" href="'.$path.'css/bootstrap-css/bootstrap.css"> 
            <link rel="stylesheet" type="text/css" href="'.$path.'css/jquery.dataTables.css">
            <link rel="stylesheet" href="'.$path.'css/dataTables.checkboxes.css">
            <link rel="stylesheet" href="'.$path.'css/fontello.css">
            <link rel="stylesheet" href="'.$path.'css/animation.css">
            <link rel="stylesheet" href="'.$path.'css/alertify.min.css">   
            <link rel="stylesheet" href="'.$path.'css/style.css">
        </head>
        ';
        return $content; 
    }
    public function jsScript($path=""){
        $content='
        <script type="text/javascript" src="'.$path.'js/jquery-3.6.0.min.js"></script> 
        <script type="text/javascript" src="'.$path.'js/bootstrap-js/bootstrap.bundle.js"></script>
        <script type="text/javascript" charset="utf8" src="'.$path.'js/jquery.dataTables.js"></script>
        <script type="text/javascript" src="'.$path.'js/dataTables.responsive.min.js"></script>
        <script type="text/javascript" src="'.$path.'js/dataTables.fixedHeader.min.js"></script>
        <script type="text/javascript" src="'.$path.'js/dataTables.checkboxes.min.js"></script>  
        <script type="text/javascript" src="'.$path.'js/vue.js"></script>  
        <script type="text/javascript" src="'.$path.'js/alertify.min.js"></script>   
        <script type="text/javascript" src="'.$path.'js/simple.money.format.js"></script>    
        <script type="text/javascript" src="'.$path.'js/axios.min.js"></script>    
        ';
        
        
        
        return $content;
    }
}





?>