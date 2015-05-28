<?php
 /**
 Traffic Manager
 */

//Librerias que necesito
include_once "../../configs/generalConfigs.php";
require_once("../../libs/trafficManager.Smarty.php");
require_once("../../libs/trafficManager.class.php");
require_once("../../libs/misc.php");
require_once("../../libs/database.class.php");
require_once("../../libs/dns.class.php");

session_start();

if (!(isset($_SESSION["trafficManager"]))){

	header("Location: /tm/index.php");
}else{

$modulename="dns";

$smarty->assign('loggedUser',$_SESSION["trafficManager"]->username);
$smarty->assign('loggedUserDescription',$_SESSION["trafficManager"]->userDescription);
$smarty->assign('loggedUserLastLogin',$_SESSION["trafficManager"]->userLastLogin);


//$smarty->assign('sesion',$_SESSION["trafficManager"]);
//$smarty->assign('base',$_SESSION["trafficManager"]->getDb());

if (isset($_GET['action']) ){

    switch ($_GET['action']) {

	case "view":
	    //Muestro el listado de Dominios Administrados
	    $smarty->assign('domains',$_SESSION["trafficManager"]->getManagedZones());
	    $smarty->display('dnsView.tpl');
	    break;
	case "delete":
	    $smarty->assign('accion',$_GET['action']);

	    if (isset($_GET['domainame'])){
	        if ($_SESSION["trafficManager"]->deleteDnsDomain($_GET['domainame'])){

		    $_SESSION["trafficManager"]->tmLog('INFO','web',$modulename,"El usuario ".$_SESSION["trafficManager"]->username." elimino la zona de DNS ".$_GET['domainame']);

    		    $message="La Zona se ha eliminado con Exito";
		    $smarty->assign('message',$message);
	        }else{

		    $_SESSION["trafficManager"]->tmLog('ERR','web',$modulename,"El usuario ".$_SESSION["trafficManager"]->username." obtuvo un error al eliminar la zona de DNS ".$_GET['domainame']);

		    $message="Se ha producido un inconveniente para eliminar la Zona de Dns";

		    $smarty->assign('message',$message);
		}
	    }
	    $smarty->assign('domains',$_SESSION["trafficManager"]->getManagedZones());
	    $smarty->display('dnsView.tpl');
	    break;
	case "seccondaries":
	    //Muestro la explicacion de como configurar los DNS secundarios
	    $smarty->display('dnsSeccondaries.tpl');
	    break;
	default:
	    echo "ERRORRRRRRRR";
    }


}else{
    //Asumo que se esta queriendo agregar una Zona

if (isset($_POST['zonename']) && isset($_POST['minimum']) && isset($_POST['primaryDns']) && isset($_POST['seccondaryDns'])){

    if ($_SESSION["trafficManager"]->addDnsZone($_POST['zonename'],$_POST['minimum'],$_POST['primaryDns'],$_POST['seccondaryDns'])){

	$_SESSION["trafficManager"]->tmLog('INFO','web',$modulename,"El usuario ".$_SESSION["trafficManager"]->username." agrego la zona de DNS ".$_POST['zonename']);

        $message="La Zona se ha agregado con Exito";
	$smarty->assign('message',$message);

    }else{

	$_SESSION["trafficManager"]->tmLog('ERR','web',$modulename,"El usuario ".$_SESSION["trafficManager"]->username." obtuvo un error al agregar la zona de DNS ".$_POST['zonename']);

	$message="Se ha producido un inconveniente para agergar la Zona de DNS";
	$smarty->assign('message',$message);

    }
}else{

    
    //Uso: https://github.com/nghuuphuoc/bootstrapvalidator

    $message="";
    
}

//Muestro el Formulario Estandar para agregar un host
$smarty->assign('title',"Zona de DNS");
$smarty->display('dnsAdd.tpl');

}

}
?>