<?php
Class TemplateDark{ 
    public function __construct(){
        return true; 
    }
    public function header($name){
        $content='
        <header>
            <div class="logo">
                <a href="main.php">
                    <h1>DevXm</h1>
                </a>
                <h4>Monitoreo y administraciòn</h4>

            </div>
            <div class="header-box">
                <div class="user">
                    <i class="icon-user"></i><span>'.$name.'</span>
                </div>
                <div class="button-collapse">
                    <button>☰</button>
                </div>
            </div>
        </header>
        
        ';
        return $content;
    }
    public function navTop($role,$path=""){
        $content='
        <nav class="navTop">
            <ul>
                <li><a href="'.($role!='tecnico'?"registerPay.php":"#").'"><i class="icon-money"></i>Registrar Pago</a></li>
                <li><a href="'.$path.($role!='tecnico'?"transacciones.php":"#").'"><i class="icon-print"></i>Transacciones</a></li>
                <li class="border border-primary rounded p-2"><a href="'.$path.($role!='tecnico' && $role!='convenio'?"reclist.php":"#").'"><i class="icon-money"></i>Factura a Empresas</a></li> 
                <li><a href="'.$path.($role!='tecnico'?"wallet.php":"#").'"><i class="icon-money"></i>Billetera</a></li> 
            </ul>
        </nav>
        ';
        return $content; 
    }
    public function navLeft($role,$path="../"){
        $content='
        <nav class="navLeft">
            <ul>
                <li class="selected"><a href="tick.php"><i class="icon-pinboard"></i><span>Tickets</span></a></li>
                <li><a href="'.$path.($role!='tecnico' && $role!='convenio'?"fact.php":"#").'"><i
                            class="icon-docs"></i><span>Facturas</span></a></li>
                <li><a href="'.$path.($role!='tecnico' && $role!='convenio'?"client.php":"#").'"><i
                            class="icon-users"></i><span>Clientes</span></a></li>
                <li><a href="'.$path.($role!='tecnico' && $role!='convenio'?"mktik.php":"#").'"><i
                            class="icon-network"></i><span>Mktik</span></a></li>
                <li><a href="'.$path.($role!='tecnico' && $role!='convenio'?"egr.php":"#").'"><i
                            class="icon-money"></i><span>Egresos</span></a></li>
                <li><a href="'.$path.'../login/logout.php"><i class="icon-logout"></i><span>Salir</span></a></li>
            </ul>
        </nav>
        ';
        
        
        
        return $content;
    }
    public function footer(){
        $content='
        <footer>
            <div>
                <span>DevXm- Adminstraciòn Redes </span>
            </div>
        </footer>
        
        ';
        return $content;
    }
 
} 

?>