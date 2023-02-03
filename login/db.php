<?php
$pth=explode("/",__DIR__);
array_pop($pth); 
$web_path=implode("/",$pth);
require_once($web_path.'/vendor/autoload.php'); 
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
//important: ipAlive.json is generated and updated from devicePingResponseList.php 
$server = $_ENV['MYSQL_SERVER']; 
$db_user = $_ENV['MYSQL_USER'];  
$db_pwd = $_ENV['MYSQL_PASSWORD']; 
$db_name = $_ENV['MYSQL_DATABASE'];
$table_name = 'users';
$table_name_social = 'users_social';
$table_name_settings= 'admin_settings'; 
//email configuration
$from_address = "admin@devlup.com";
//domain configuration

$url = "http://projects.devlup.com/LoginSystemv42";
//Admin username
$admin_user=$_ENV['admin_user'];
$admin_password=$_ENV['admin_password'];
//Mikrotik defauls 
$defaultIdGruposEmpresa=1;
$awsVpnDefaultUser=$_ENV['awsVpnDefaultUser'];
$awsVpnDefaultPassword=$_ENV['awsVpnDefaultPassword'];
$rb_server_default_user=$_ENV['rb_server_default_user'];
$ubiquiti_default_user=$_ENV['ubiquiti_default_user'];
$ubiquiti_default_password=$_ENV['ubiquiti_default_password'];
$rb_default_user=$_ENV['rb_default_user'];
$rb_default_password=$_ENV['rb_default_password'];
$rb_default_dstnat_port="8080";
$router_default_wanIp_cpe_mktik="192.168.88.100";  
$router_default_wanIp_cpe_ubiquiti="192.168.1.100";
$rb_default_repeater_user=$_ENV['rb_default_repeater_user'];
$rb_default_repeater_password=$_ENV['rb_default_repeater_password'];
$ubiquiti_default_repeater_user=$_ENV['ubiquiti_default_repeater_user'];
$ubiquiti_default_repeater_password=$_ENV['ubiquiti_default_repeater_password'];
//Telegram Settings
$telegramApi=$_ENV['telegramApi'];
$telegramChatid=$_ENV['telegramChatid'];
//PDF API
$pdfKeyApi=$_ENV['pdfKeyApi'];
//Mailer module Endpoind
$endPoint='http://localhost:3001/';

$tokenToInstalledUserNew=$_ENV['tokenToInstalledUserNew'];
$tokenToNotInstalledUserNewYet=$_ENV['tokenToNotInstalledUserNewYet'];
$tokenToPaymentDone=$_ENV['tokenToPaymentDone'];
$endPointNewuser=$_ENV['endPointNewuser'];
$mailEndPoint=$_ENV['mailEndPoint']; 
$mailCompany=$_ENV['mailCompany'];
//sms Onurix     //Se debe configurar el servidor en caso de que la ip publica cambie.
$smsKey=$_ENV['smsKey']; 
$prefixCode="+57";
/////////// 
//strings 
//login  
$msg_pwd_error = 'PasswYennyMoraord incorrect';
$msg_un_error = 'Username Doesn\'t exist';
$msg_email_1 = 'User Account not yet activated.';
$msg_email_2 = 'Click here to resend activation email';
//Registration form
$msg_reg_user = 'Username taken.Kindly choose different username';
$msg_reg_email = 'Email Already registered';
$msg_reg_activ = 'Activation code has been successfully sent to your Email Address';
//Admin login
$msg_admin_pwd = 'Incorect password';
$msg_admin_user = 'Username Doesn\'t exist';
//LOGO text
$logotxt = "IspExperts";
//Twitter Configuration
define('CONSUMER_KEY', 'CONSUMER_KEY_HERE');
define('CONSUMER_SECRET', 'CONSUMER_SECRET_HERE');
define('OAUTH_CALLBACK', $url . '/twitter_callback.php');
//Google Configuration
$Clientid = 'TYPE_CLIENTID_HERE';
$Email_address = 'TYPE_EMAILADDRESS_HERE';
$Client_secret = 'TYPE_CLIENT_SECRET_HERE';
$Redirect_URIs = $url . '/google_connect.php';
$apikeys = 'TYPE_API_KEYS_HERE';
//facebook configuration
$fbappid = 'FB_APP_ID';
$fbsecret = 'FB_SECRET';
//Sms Messages
$SmsCronjsonCte1='
	{
		"data": [
					{
						"fecha": "7/01/2023",
						"message":"Estimado Usario su factura de Internet está vencida favor acercarce a la oficina Cll 13 8-47 Guamal Meta y evite suspensión del servicio"
					},
					{
						"fecha": "11/01/2023",
						"message":"Estimado Usario su factura de Internet está vencida favor acercarce a la oficina Cll 13 8-47 Guamal Meta y evite suspensión del servicio"
					},
					{
						"fecha": "14/01/2023",
						"message":"Estimado Usario su factura de Internet está vencida favor acercarce a la oficina Cll 13 8-47 Guamal Meta y evite suspensión del servicio"
					},
					{
						"fecha": "7/02/2023",
						"message":"Estimado Usario su factura de Internet está vencida favor acercarce a la oficina Cll 13 8-47 Guamal Meta y evite suspensión del servicio"
					},
					{
						"fecha": "11/02/2023",
						"message":"Estimado Usario su factura de Internet está vencida favor acercarce a la oficina Cll 13 8-47 Guamal Meta y evite suspensión del servicio"
					},
					{
						"fecha": "14/02/2023",
						"message":"Estimado Usario su factura de Internet está vencida favor acercarce a la oficina Cll 13 8-47 Guamal Meta y evite suspensión del servicio"
					},
					{
						"fecha": "7/03/2023",
						"message":"Estimado Usario su factura de Internet está vencida favor acercarce a la oficina Cll 13 8-47 Guamal Meta y evite suspensión del servicio"
					},
					{
						"fecha": "11/03/2023",
						"message":"Estimado Usario su factura de Internet está vencida favor acercarce a la oficina Cll 13 8-47 Guamal Meta y evite suspensión del servicio"
					},
					{
						"fecha": "14/03/2023",
						"message":"Estimado Usario su factura de Internet está vencida favor acercarce a la oficina Cll 13 8-47 Guamal Meta y evite suspensión del servicio"
					},
					{
						"fecha": "7/04/2023",
						"message":"Estimado Usario su factura de Internet está vencida favor acercarce a la oficina Cll 13 8-47 Guamal Meta y evite suspensión del servicio"
					},
					{
						"fecha": "11/04/2023",
						"message":"Estimado Usario su factura de Internet está vencida favor acercarce a la oficina Cll 13 8-47 Guamal Meta y evite suspensión del servicio"
					},
					{
						"fecha": "14/04/2023",
						"message":"Estimado Usario su factura de Internet está vencida favor acercarce a la oficina Cll 13 8-47 Guamal Meta y evite suspensión del servicio"
					},
					{
						"fecha": "9/05/2023",
						"message":"Estimado Usario su factura de Internet está vencida favor acercarce a la oficina Cll 13 8-47 Guamal Meta y evite suspensión del servicio"
					},
					{
						"fecha": "12/05/2023",
						"message":"Estimado Usario su factura de Internet está vencida favor acercarce a la oficina Cll 13 8-47 Guamal Meta y evite suspensión del servicio"
					},
					{
						"fecha": "16/05/2023",
						"message":"Estimado Usario su factura de Internet está vencida favor acercarce a la oficina Cll 13 8-47 Guamal Meta y evite suspensión del servicio"
					},
					{
						"fecha": "7/06/2023",
						"message":"Estimado Usario su factura de Internet está vencida favor acercarce a la oficina Cll 13 8-47 Guamal Meta y evite suspensión del servicio"
					},
					{
						"fecha": "11/06/2023",
						"message":"Estimado Usario su factura de Internet está vencida favor acercarce a la oficina Cll 13 8-47 Guamal Meta y evite suspensión del servicio"
					},
					{
						"fecha": "14/06/2023",
						"message":"Estimado Usario su factura de Internet está vencida favor acercarce a la oficina Cll 13 8-47 Guamal Meta y evite suspensión del servicio"
					},
					{
						"fecha": "7/07/2023",
						"message":"Estimado Usario su factura de Internet está vencida favor acercarce a la oficina Cll 13 8-47 Guamal Meta y evite suspensión del servicio"
					},
					{
						"fecha": "11/07/2023",
						"message":"Estimado Usario su factura de Internet está vencida favor acercarce a la oficina Cll 13 8-47 Guamal Meta y evite suspensión del servicio"
					},
					{
						"fecha": "14/07/2023",
						"message":"Estimado Usario su factura de Internet está vencida favor acercarce a la oficina Cll 13 8-47 Guamal Meta y evite suspensión del servicio"
					},
					{
						"fecha": "8/08/2023",
						"message":"Estimado Usario su factura de Internet está vencida favor acercarce a la oficina Cll 13 8-47 Guamal Meta y evite suspensión del servicio"
					},
					{
						"fecha": "12/08/2023",
						"message":"Estimado Usario su factura de Internet está vencida favor acercarce a la oficina Cll 13 8-47 Guamal Meta y evite suspensión del servicio"
					},
					{
						"fecha": "15/08/2023",
						"message":"Estimado Usario su factura de Internet está vencida favor acercarce a la oficina Cll 13 8-47 Guamal Meta y evite suspensión del servicio"
					},
					{
						"fecha": "7/09/2023",
						"message":"Estimado Usario su factura de Internet está vencida favor acercarce a la oficina Cll 13 8-47 Guamal Meta y evite suspensión del servicio"
					},
					{
						"fecha": "12/09/2023",
						"message":"Estimado Usario su factura de Internet está vencida favor acercarce a la oficina Cll 13 8-47 Guamal Meta y evite suspensión del servicio"
					},
					{
						"fecha": "15/09/2023",
						"message":"Estimado Usario su factura de Internet está vencida favor acercarce a la oficina Cll 13 8-47 Guamal Meta y evite suspensión del servicio"
					},
					{
						"fecha": "7/10/2023",
						"message":"Estimado Usario su factura de Internet está vencida favor acercarce a la oficina Cll 13 8-47 Guamal Meta y evite suspensión del servicio"
					},
					{
						"fecha": "11/10/2023",
						"message":"Estimado Usario su factura de Internet está vencida favor acercarce a la oficina Cll 13 8-47 Guamal Meta y evite suspensión del servicio"
					},
					{
						"fecha": "14/10/2023",
						"message":"Estimado Usario su factura de Internet está vencida favor acercarce a la oficina Cll 13 8-47 Guamal Meta y evite suspensión del servicio"
					},
					{
						"fecha": "7/11/2023",
						"message":"Estimado Usario su factura de Internet está vencida favor acercarce a la oficina Cll 13 8-47 Guamal Meta y evite suspensión del servicio"
					},
					{
						"fecha": "11/11/2023",
						"message":"Estimado Usario su factura de Internet está vencida favor acercarce a la oficina Cll 13 8-47 Guamal Meta y evite suspensión del servicio"
					},
					{
						"fecha": "14/11/2023",
						"message":"Estimado Usario su factura de Internet está vencida favor acercarce a la oficina Cll 13 8-47 Guamal Meta y evite suspensión del servicio"
					},
					{
						"fecha": "7/12/2023",
						"message":"Estimado Usario su factura de Internet está vencida favor acercarce a la oficina Cll 13 8-47 Guamal Meta y evite suspensión del servicio"
					},
					{
						"fecha": "12/12/2023",   
						"message":"Estimado Usario su factura de Internet está vencida favor acercarce a la oficina Cll 13 8-47 Guamal Meta y evite suspensión del servicio"
					},
					{
						"fecha": "14/12/2023",
						"message":"Estimado Usario su factura de Internet está vencida favor acercarce a la oficina Cll 13 8-47 Guamal Meta y evite suspensión del servicio"
					}
				]
	}
'; 
$SmsCronjsonCte15='
	{
		"data": [
					{
						"fecha": "24/01/2023",
						"message":"Estimado Usario su factura de Internet está vencida favor acercarce a la oficina Cll 13 8-47 Guamal Meta y evite suspensión del servicio"
					},
					{
						"fecha": "26/01/2023",
						"message":"Estimado Usario su factura de Internet está vencida favor acercarce a la oficina Cll 13 8-47 Guamal Meta y evite suspensión del servicio"
					},
					{
						"fecha": "28/01/2023",
						"message":"Estimado Usario su factura de Internet está vencida favor acercarce a la oficina Cll 13 8-47 Guamal Meta y evite suspensión del servicio"
					},
					{
						"fecha": "24/02/2023",
						"message":"Estimado Usario su factura de Internet está vencida favor acercarce a la oficina Cll 13 8-47 Guamal Meta y evite suspensión del servicio"
					},
					{
						"fecha": "26/02/2023",
						"message":"Estimado Usario su factura de Internet está vencida favor acercarce a la oficina Cll 13 8-47 Guamal Meta y evite suspensión del servicio"
					},
					{
						"fecha": "28/02/2023",
						"message":"Estimado Usario su factura de Internet está vencida favor acercarce a la oficina Cll 13 8-47 Guamal Meta y evite suspensión del servicio"
					},
					{
						"fecha": "24/03/2023",
						"message":"Estimado Usario su factura de Internet está vencida favor acercarce a la oficina Cll 13 8-47 Guamal Meta y evite suspensión del servicio"
					},
					{
						"fecha": "28/03/2023",
						"message":"Estimado Usario su factura de Internet está vencida favor acercarce a la oficina Cll 13 8-47 Guamal Meta y evite suspensión del servicio"
					},
					{
						"fecha": "31/03/2023",
						"message":"Estimado Usario su factura de Internet está vencida favor acercarce a la oficina Cll 13 8-47 Guamal Meta y evite suspensión del servicio"
					},
					{
						"fecha": "25/04/2023",
						"message":"Estimado Usario su factura de Internet está vencida favor acercarce a la oficina Cll 13 8-47 Guamal Meta y evite suspensión del servicio"
					},
					{
						"fecha": "28/04/2023",
						"message":"Estimado Usario su factura de Internet está vencida favor acercarce a la oficina Cll 13 8-47 Guamal Meta y evite suspensión del servicio"
					},
					{
						"fecha": "30/04/2023",
						"message":"Estimado Usario su factura de Internet está vencida favor acercarce a la oficina Cll 13 8-47 Guamal Meta y evite suspensión del servicio"
					},
					{
						"fecha": "24/05/2023",
						"message":"Estimado Usario su factura de Internet está vencida favor acercarce a la oficina Cll 13 8-47 Guamal Meta y evite suspensión del servicio"
					},
					{
						"fecha": "27/05/2023",
						"message":"Estimado Usario su factura de Internet está vencida favor acercarce a la oficina Cll 13 8-47 Guamal Meta y evite suspensión del servicio"
					},
					{
						"fecha": "31/05/2023",
						"message":"Estimado Usario su factura de Internet está vencida favor acercarce a la oficina Cll 13 8-47 Guamal Meta y evite suspensión del servicio"
					},
					{
						"fecha": "24/06/2023",
						"message":"Estimado Usario su factura de Internet está vencida favor acercarce a la oficina Cll 13 8-47 Guamal Meta y evite suspensión del servicio"
					},
					{
						"fecha": "27/06/2023",
						"message":"Estimado Usario su factura de Internet está vencida favor acercarce a la oficina Cll 13 8-47 Guamal Meta y evite suspensión del servicio"
					},
					{
						"fecha": "30/06/2023",
						"message":"Estimado Usario su factura de Internet está vencida favor acercarce a la oficina Cll 13 8-47 Guamal Meta y evite suspensión del servicio"
					},
					{
						"fecha": "25/07/2023",
						"message":"Estimado Usario su factura de Internet está vencida favor acercarce a la oficina Cll 13 8-47 Guamal Meta y evite suspensión del servicio"
					},
					{
						"fecha": "28/07/2023",
						"message":"Estimado Usario su factura de Internet está vencida favor acercarce a la oficina Cll 13 8-47 Guamal Meta y evite suspensión del servicio"
					},
					{
						"fecha": "30/07/2023",
						"message":"Estimado Usario su factura de Internet está vencida favor acercarce a la oficina Cll 13 8-47 Guamal Meta y evite suspensión del servicio"
					},
					{
						"fecha": "24/08/2023",
						"message":"Estimado Usario su factura de Internet está vencida favor acercarce a la oficina Cll 13 8-47 Guamal Meta y evite suspensión del servicio"
					},
					{
						"fecha": "29/08/2023",
						"message":"Estimado Usario su factura de Internet está vencida favor acercarce a la oficina Cll 13 8-47 Guamal Meta y evite suspensión del servicio"
					},
					{
						"fecha": "31/08/2023",
						"message":"Estimado Usario su factura de Internet está vencida favor acercarce a la oficina Cll 13 8-47 Guamal Meta y evite suspensión del servicio"
					},
					{
						"fecha": "24/09/2023",
						"message":"Estimado Usario su factura de Internet está vencida favor acercarce a la oficina Cll 13 8-47 Guamal Meta y evite suspensión del servicio"
					},
					{
						"fecha": "27/09/2023",
						"message":"Estimado Usario su factura de Internet está vencida favor acercarce a la oficina Cll 13 8-47 Guamal Meta y evite suspensión del servicio"
					},
					{
						"fecha": "30/09/2023",
						"message":"Estimado Usario su factura de Internet está vencida favor acercarce a la oficina Cll 13 8-47 Guamal Meta y evite suspensión del servicio"
					},
					{
						"fecha": "24/10/2023",
						"message":"Estimado Usario su factura de Internet está vencida favor acercarce a la oficina Cll 13 8-47 Guamal Meta y evite suspensión del servicio"
					},
					{
						"fecha": "27/10/2023",
						"message":"Estimado Usario su factura de Internet está vencida favor acercarce a la oficina Cll 13 8-47 Guamal Meta y evite suspensión del servicio"
					},
					{
						"fecha": "31/10/2023",
						"message":"Estimado Usario su factura de Internet está vencida favor acercarce a la oficina Cll 13 8-47 Guamal Meta y evite suspensión del servicio"
					},
					{
						"fecha": "24/11/2023",
						"message":"Estimado Usario su factura de Internet está vencida favor acercarce a la oficina Cll 13 8-47 Guamal Meta y evite suspensión del servicio"
					},
					{
						"fecha": "27/11/2023",
						"message":"Estimado Usario su factura de Internet está vencida favor acercarce a la oficina Cll 13 8-47 Guamal Meta y evite suspensión del servicio"
					},
					{
						"fecha": "30/11/2023",
						"message":"Estimado Usario su factura de Internet está vencida favor acercarce a la oficina Cll 13 8-47 Guamal Meta y evite suspensión del servicio"
					},
					{
						"fecha": "23/12/2023",
						"message":"Estimado Usario su factura de Internet está vencida favor acercarce a la oficina Cll 13 8-47 Guamal Meta y evite suspensión del servicio"
					},
					{
						"fecha": "29/12/2023",
						"message":"Estimado Usario su factura de Internet está vencida favor acercarce a la oficina Cll 13 8-47 Guamal Meta y evite suspensión del servicio"
					},
					{
						"fecha": "30/12/2023",
						"message":"Estimado Usario su factura de Internet está vencida favor acercarce a la oficina Cll 13 8-47 Guamal Meta y evite suspensión del servicio"
					}
				]
	}
';
?>

