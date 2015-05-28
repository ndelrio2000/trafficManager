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

$modulename="host";

$smarty->assign('loggedUser',$_SESSION["trafficManager"]->username);
$smarty->assign('loggedUserDescription',$_SESSION["trafficManager"]->userDescription);
$smarty->assign('loggedUserLastLogin',$_SESSION["trafficManager"]->userLastLogin);


//$smarty->assign('sesion',$_SESSION["trafficManager"]);
//$smarty->assign('base',$_SESSION["trafficManager"]->getDb());

if (isset($_GET['action']) ){

    switch ($_GET['action']) {

	case "view":
	    //Muestro el listado de Hosts Administrador
	    $smarty->assign('hosts',$_SESSION["trafficManager"]->getManagedHosts());
	    $smarty->display('hostsView.tpl');
	    break;
	case "edit":
	    if (isset($_POST['hostname']) && isset($_POST['ipaddr']) && isset($_POST['failoveripaddr']) && isset($_POST['domainame']) && isset($_POST['service']) && isset($_POST['checkinterval']) && isset($_POST['type']) && isset($_POST['ipmonitaddr']) && isset($_POST['failoveripmonitaddr']) && isset($_POST['servicefailover']) ){

	        if ($_SESSION["trafficManager"]->editHost($_POST['id'],$_POST['hostname'],$_POST['domainame'],$_POST['ipaddr'],$_POST['ipmonitaddr'],$_POST['service'],$_POST['failoveripaddr'],$_POST['failoveripmonitaddr'],$_POST['servicefailover'],$_POST['checkinterval'],$_POST['type'])){

		    $_SESSION["trafficManager"]->tmLog('INFO','web',$modulename,"El usuario ".$_SESSION["trafficManager"]->username." edito el host ".$_POST['hostname'].".".$_POST['domainame']);

    		    $message="El host se ha editado con Exito";
		    $smarty->assign('message',$message);
	        }else{

		    $_SESSION["trafficManager"]->tmLog('ERR','web',$modulename,"El usuario ".$_SESSION["trafficManager"]->username." obtuvo un error al editar el host ".$_POST['hostname'].".".$_POST['domainame']);

		    $message="Se ha producido un inconveniente para editar el Host";
		    $smarty->assign('message',$message);
		}
		$smarty->assign('hosts',$_SESSION["trafficManager"]->getManagedHosts());
		$smarty->display('hostsView.tpl');
	    }else{
		//Obtengo los datos del Host de la BD
		$host = $_SESSION["trafficManager"]->getHostById($_GET['id']);
		$smarty->assign('action','edit');
		$smarty->assign('hostname',$host['name']);
		$smarty->assign('domainame',$host['domainame']);
		$smarty->assign('type',$host['type']);
		$smarty->assign('ipaddr',$host['ipaddress']);
		$smarty->assign('ipmonitaddr',$host['ipmonitaddress']);
		$smarty->assign('service',$host['service']);
		$smarty->assign('failoveripaddr',$host['failoveripaddress']);
		$smarty->assign('failoveripmonitaddr',$host['failovermonitaddress']);
		$smarty->assign('servicefailover',$host['servicefailover']);
		$smarty->assign('checkinterval',$host['checkinterval']);
		$smarty->assign('id',$_GET['id']);
		//Muestro el formulario de add pero con los datos cargados
		$zones=$_SESSION["trafficManager"]->getManagedZones();
		$smarty->assign('zones',$zones);
		$services=$_SESSION["trafficManager"]->getsupportedServices();
		$smarty->assign('services',$services);
		$smarty->assign('title',"Editar Host");
		$smarty->display('hostAdd.tpl');
	    }
	break;
	case "delete":
	    $smarty->assign('accion',$_GET['action']);

	    if (isset($_GET['id'])){
	        if ($_SESSION["trafficManager"]->deleteHost($_GET['id'])){

		    $_SESSION["trafficManager"]->tmLog('INFO','web',$modulename,"El usuario ".$_SESSION["trafficManager"]->username." elimino el host con id ".$_GET['id']);

    		    $message="El host se ha eliminado con Exito";
		    $smarty->assign('message',$message);
	        }else{

		    $_SESSION["trafficManager"]->tmLog('ERR','web',$modulename,"El usuario ".$_SESSION["trafficManager"]->username." obtuvo un error al eliminar el host con id ".$_GET['id']);

		    $message="Se ha producido un inconveniente para eliminar el Host";
		    $smarty->assign('message',$message);
		}
	    }
	    $smarty->assign('hosts',$_SESSION["trafficManager"]->getManagedHosts());
	    $smarty->display('hostsView.tpl');
	    break;
	default:
	    echo "ERRORRRRRRRR";
    }


}else{
    //Asumo que se esta queriendo agregar un host
if (isset($_POST['hostname']) && isset($_POST['ipaddr']) && isset($_POST['failoveripaddr']) && isset($_POST['domainame']) && isset($_POST['service']) && isset($_POST['checkinterval']) && isset($_POST['type']) && isset($_POST['ipmonitaddr']) && isset($_POST['failoveripmonitaddr']) && isset($_POST['servicefailover']) ){

    if ($_SESSION["trafficManager"]->addHost($_POST['hostname'],$_POST['domainame'],$_POST['ipaddr'],$_POST['ipmonitaddr'],$_POST['service'],$_POST['failoveripaddr'],$_POST['failoveripmonitaddr'],$_POST['servicefailover'],$_POST['checkinterval'],$_POST['type'])){
					    
	$_SESSION["trafficManager"]->tmLog('INFO','web',$modulename,"El usuario ".$_SESSION["trafficManager"]->username." agrego el host ".$_POST['hostname'].".".$_POST['domainame']);

        $message="El host se ha agregado con Exito";
	$smarty->assign('message',$message);

    }else{

	$_SESSION["trafficManager"]->tmLog('ERR','web',$modulename,"El usuario ".$_SESSION["trafficManager"]->username." obtuvo un error al agregar el host ".$_POST['hostname'].".".$_POST['domainame']);

	$message="Se ha producido un inconveniente para agergar el Host";
	$smarty->assign('message',$message);

    }
}else{

    
    //Uso: https://github.com/nghuuphuoc/bootstrapvalidator

    $message="";
    
}

//Muestro el Formulario Estandar para agregar un host
$zones=$_SESSION["trafficManager"]->getManagedZones();
$smarty->assign('zones',$zones);
$services=$_SESSION["trafficManager"]->getsupportedServices();
$smarty->assign('services',$services);

$smarty->assign('title',"Agregar Host");
$smarty->display('hostAdd.tpl');

}


}
?>