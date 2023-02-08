<?php
require __DIR__.'/vendor/autoload.php';
use \ConvertApi\ConvertApi;
require("../login/db.php");
require("../Email.php");
ConvertApi::setApiSecret($pdfKeyApi); 
$fromFormat = 'web';
$conversionTimeout = 180;
$dir = sys_get_temp_dir()."/bill";
$result = ConvertApi::convert(
    'pdf',
    [
        'Url' => 'https://www.ispexperts.com/factura_new_cli.php?rpp=1&idc=856',
        'FileName' => 'web-example'
    ],
    $fromFormat,
    $conversionTimeout
);
$savedFiles = $result->saveFiles($dir);
echo "The web page PDF saved to\n";
print_r($savedFiles); 
$obj=new Email("http://localhost:3001/newuser");
$response=$obj->emailToInstalledNewUser($emailArray=[
    "fullName"=> "Jorge Hernandez",
    "paymentDay"=> "1 al 7",
    "periodo"=> "Febrero",
    "valorPlan"=> "50000",
    "template"=>"d-4bdc152f4ac04ddfbacd49948f570213",
    "idClient"=>"958",
    "email"=>"omar.a.hernandez.d@gmail.com"
    ]);
print "response:".$response;
var_dump($response);//

?>