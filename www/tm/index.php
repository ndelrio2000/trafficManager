<?php
 /**
 Traffic Manager
 */

//Librerias que necesito
include_once "configs/generalConfigs.php";
require_once("libs/trafficManager.Smarty.php");
require_once("libs/trafficManager.class.php");
require_once("libs/database.class.php");
require_once("libs/dns.class.php");

session_start();
$modulename='login';

//Instancio a la logica de mi app

if ((isset($_GET['action'])) and $_GET['action'] == "logout"){
    $_SESSION["trafficManager"]->tmLog('INFO','web',$modulename,"El usuario ".$_SESSION["trafficManager"]->username." abandono la sesion");
    if ((isset($_SESSION["trafficManager"]))){
	session_destroy();
	$_SESSION["trafficManager"]=NULL;
	$message="Se abandono la sesion correctamente";
	$smarty->assign('message',$message);

    }
}

if ((isset($_GET['usuario'])) && (isset($_GET['password']))){
    //Creo e inicializo la sesion y variables que se van a utilizar en la aplicacion

    $_SESSION["trafficManager"] = new TrafficManger;

    //Valido el usuario y si es valido lo mando al dashboard. Esta funcion tambien actualiza el Last Login y setea variables de sesion
    if ($_SESSION["trafficManager"]->validateUser($_GET['usuario'],$_GET['password'])){
//    if (TRUE){

	$_SESSION["trafficManager"]->tmLog('INFO','web',$modulename,"El usuario ".$_GET['usuario']." inicio sesion");
	header("Location: modules/dashboard/index.php");

    }else{

	$_SESSION["trafficManager"]->tmLog('ERR','web',$modulename,"Intento Fallido de sesion con nombre de usuario ".$_GET['usuario']);
	//No es usuario valido, destruyo la sesion
	session_destroy();
	$_SESSION["trafficManager"]=NULL;
	header("Location: /tm/index.php");

	//$message="Usuario o contrasenia invalidos";
	//$smarty->assign('message',$message);
        //$smarty->display('login.tpl');
    }
}else{
    //Muestro pantalla de Login
    $smarty->display('login.tpl');
}
?>