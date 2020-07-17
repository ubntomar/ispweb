<?php 
session_start();
if ( !isset($_SESSION['login']) || $_SESSION['login'] !== true) 
		{
		header('Location: login/index.php');
		exit;
		}
else    {
		$user=$_SESSION['username'];
		}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />
    <title>Wisdev-Administrador ISP</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700|Roboto:300,400,500" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.9/css/jquery.dataTables.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/1.0.7/css/responsive.dataTables.min.css" />
    <link rel="stylesheet" href="bower_components/alertify/css/alertify.min.css" />
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.min.css" />
    <link rel="stylesheet" href="bower_components/alertify/css/themes/default.min.css" />
    <link rel="stylesheet" href="css/fontello.css" />
    <link rel="stylesheet" href="css/estilos.css" />
    <link rel="stylesheet" href="css/dataTables.checkboxes.css" />
    <link rel="stylesheet" href="css/animation.css">
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
</head>

<body>
    <div class="container-fluid px-0">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top   ">
            <div class="container img-logo ">
                <img src="img/wisp.png" />
                <!-- Nos sirve  para agregar un logotipo al menu -->
                <a href="main.php" class="navbar-brand link-border">Wisdev</a>

                <!-- Nos permite usar el componente collapse para dispositivos moviles  -->
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar"
                    aria-controls="navbar" aria-expanded="false" aria-label="Menu de Navegacion">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse " id="navbar">
                    <ul class="navbar-nav  navclass ">
                        <li class="nav-item ">
                            <a href="#" class="nav-link"><i class="icon-money"></i>Cierre de caja
                                <span class="sr-only">(Actual)</span></a>
                        </li>
                        <li class="nav-item">
                            <a href="register-pay.php" class="nav-link "><i class="icon-money"></i>Registrar Pago</a>
                        </li>
                        <li class="nav-item  ">
                            <a href="transacciones.php" class="nav-link  "><i class="icon-print "></i>Transacciones</a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link"><i class="icon-mail"></i>Contacto</a>
                        </li>
                        <li class="nav-item">
                            <a href="reclist.php" class="nav-link "><i class="icon-money"></i>Formato Recibo</a>
                        </li>
                    </ul>
                    <div class="ml-auto">
                        <ul class="nav navbar-nav   ">
                            <li class="nav-item ">
                                <a class="nav-link disabled text-white "><i
                                        class="icon-user"></i><?php echo "Hola ".$_SESSION['username']; ?></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

        <div class="row">
            <div class="barra-lateral  col-sm-auto ">
                <nav class="menu d-flex d-sm-block justify-content-center flex-wrap">
                    <a href="tick.php"><i class="icon-pinboard"></i><span>Tickets</span></a>
                    <a href="fact.php"><i class="icon-docs-1"></i><span>Facturas</span></a>
                    <a href="client.php"><i class="icon-users"></i><span>Clientes</span></a>
                    <a href="mktik.php"><i class="icon-network"></i><span>Mktik</span></a>
                    <a href="egr.php"><i class="icon-money"></i><span>Egresos</span></a>
                    <a href="login/logout.php"><i class="icon-logout"></i><span>Salir</span></a>
                </nav>
            </div>

            <main class="main col">
                <div class="row" id="app">
                    <div class="columna col-lg-7">
                        <div class=" nuevo_contenido p-2 border border-info rounded">
                            <div class="d-flex justify-content-center">
                                <h3 class="titulo">Estado de conexión de Clientes</h3>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <div class="d-flex align-items-center">
                                    <div></div>
                                    <div><input type="text" value="" id="search"
                                            class="form-control form-control-sm ml-1" ></div>
                                    <div><button class="icon-search form-control form-control-sm "
                                            ></button></div>
                                </div>
                                
                            </div>
                            
                            <div class="d-flex justify-content-center">
                                <div class="d-inline  mx-1">
                                    <input class="rounded-circle border-0 h2" type="button" name="previus" value='<'>
                                </div>
                                <div class="d-inline mx-1">
                                    <input  class="rounded-circle border-0 h2" type="button"
                                        id='nexxt' value='>'>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="columna col-lg-5">
                        
                        
                        <div class="widget estadisticas">
                            <div>
                                <div class="title-caja text-light  m-o bg-secondary"><span>RED ORLANDO</span></div>
                            </div>
                            <div class="contenedor d-flex flex-wrap">
                                <div class="caja">
                                    <div class="d-flex justify-content-center mb-1">
                                        <h3 class="titulo">Ip disponibles</h3>
                                        <button  class="ml-1 border border-rounded "><i
                                                class="icon-spin6 "></i>
                                        </button>
                                    </div>
                                    <div  class="d-flex justify-content-center">
                                        <div class="border rounded bg-info">
                                            <span class="  p-1 text-light">
                                            </span>
                                            <i class="icon-ok"></i>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </main>
        </div>
    </div>

    <div class="container-fluid 	">
        <div class="row">
            <div class="col text-light bg-dark py-2 d-flex justify-content-center footer-text">
                <p>
                    Copyright ©2014-2017 Wisdev-Administrador ISP -
                    <small>All Rights Reserved.</small>
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col text-light bg-dark  d-flex justify-content-center">
                <p>
                    <i class="icon-facebook-official"></i><i class="icon-twitter-squared"></i>
                </p>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js"
        integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.9/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/responsive/1.0.7/js/dataTables.responsive.min.js">
    </script>
    <script src="bower_components/Popper/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="bower_components/alertify/js/alertify.min.js"></script>
    <script src="bower_components/AutoFormatCurrency/simple.money.format.js"></script>
    <script src="js/dataTables.checkboxes.min.js"></script>
    <script>
    var app = new Vue({
        el: "#app",
        data: {
            stat: false,
            
            
        },
        methods: {
            getUser: function() {
                axios.get('fetchUsers.php', {
                    params: {
                        id: this.id,
                        searchString: this.searchString,
                        searchOption: this.searchOption

                    }
                }).then(response => {
                    this.clientes = response.data
                    console.log(response.data.length - 1)
                    this.totalRows = response.data.length - 1

                }).catch(e => {
                    console.log('error' + e)
                })
            },
            validateIpAddress: function(data) {
                var ipformat =
                    /^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/;
                if (data != null) {
                    if (data.match(ipformat)) {
                        return true;
                    } else {

                        return false;
                    }
                }
                return false;
            }
        },
        mounted() {
            
        },
    });
    </script>
</body>

</html>