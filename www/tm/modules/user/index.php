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

$smarty->assign('loggedUser',$_SESSION["trafficManager"]->username);
$smarty->assign('loggedUserDescription',$_SESSION["trafficManager"]->userDescription);
$smarty->assign('loggedUserLastLogin',$_SESSION["trafficManager"]->userLastLogin);

$modulename="user";

if (isset($_GET['action']) ){

    switch ($_GET['action']) {

	case "view":
	    //Muestro el listado de Usuarios
	    $smarty->assign('users',$_SESSION["trafficManager"]->getUsers());
	    $smarty->display('userView.tpl');
	    break;
	case "edit":
	    if (isset($_POST['description']) && isset($_POST['username']) && isset($_POST['password1']) && isset($_POST['password2'])){

	        if ($_SESSION["trafficManager"]->editUser($_POST['description'],$_POST['username'],$_POST['password1'],$_POST['password2'])){

		    $_SESSION["trafficManager"]->tmLog('INFO','web',$modulename,"El usuario ".$_SESSION["trafficManager"]->username." edito el usuario ".$_POST['username']);

    		    $message="El usuario se ha editado con Exito";
		    $smarty->assign('message',$message);
	        }else{

		    $_SESSION["trafficManager"]->tmLog('INFO','web',$modulename,"El usuario ".$_SESSION["trafficManager"]->username." obtuvo un error al editar el usuario ".$_POST['username']);

		    $message="Se ha producido un inconveniente para editar el Usuario";
		    $smarty->assign('message',$message);
		}
		$smarty->assign('users',$_SESSION["trafficManager"]->getUsers());
		$smarty->display('userView.tpl');
	    }else{
		//Obtengo los datos del Usuario de la BD
		$user = $_SESSION["trafficManager"]->getUserByName($_GET['username']);
		$smarty->assign('action','edit');
		$smarty->assign('description',$user['description']);
		$smarty->assign('username',$user['username']);
		//Muestro el formulario de add pero con los datos cargados
		$smarty->assign('title',"Editar Usuario");
		$smarty->display('userAdd.tpl');
	    }
	break;
	case "delete":
	    $smarty->assign('accion',$_GET['action']);

	    if (isset($_GET['username'])){
	        if ($_SESSION["trafficManager"]->deleteUser($_GET['username'])){

		    $_SESSION["trafficManager"]->tmLog('INFO','web',$modulename,"El usuario ".$_SESSION["trafficManager"]->username." elimino al usuario ".$_GET['username']);

    		    $message="El usuario se ha eliminado con Exito";
		    $smarty->assign('message',$message);
	        }else{

		    $_SESSION["trafficManager"]->tmLog('INFO','web',$modulename,"El usuario ".$_SESSION["trafficManager"]->username." obtuvo un error al eliminar al usuario ".$_GET['username']);

		    $message="Se ha producido un inconveniente para eliminar el Host";
		    $smarty->assign('message',$message);
		}
	    }
	    $smarty->assign('users',$_SESSION["trafficManager"]->getUsers());
	    $smarty->display('userView.tpl');
	    break;
	default:
	    echo "ERRORRRRRRRR";
    }


}else{
    //Asumo que se esta queriendo agregar un usuario

if (isset($_POST['description']) && isset($_POST['username']) && isset($_POST['password1']) && isset($_POST['password2'])){
        if ($_SESSION["trafficManager"]->addUser($_POST['description'],$_POST['username'],$_POST['password1'],$_POST['password2'])){

	$_SESSION["trafficManager"]->tmLog('INFO','web',$modulename,"El usuario ".$_SESSION["trafficManager"]->username." agrego al usuario ".$_POST['username']);

        $message="El usuario se ha agregado con Exito";
	$smarty->assign('message',$message);

    }else{

	$_SESSION["trafficManager"]->tmLog('INFO','web',$modulename,"El usuario ".$_SESSION["trafficManager"]->username." obtuvo un error al agregar al usuario ".$_POST['username']);

	$message="Se ha producido un inconveniente para agergar el usuario";
	$smarty->assign('message',$message);

    }
}else{

    
    //Uso: https://github.com/nghuuphuoc/bootstrapvalidator

    $message="";
    
}

//Muestro el Formulario Estandar para agregar un host

$smarty->assign('title',"AgregarUsuario");
$smarty->display('userAdd.tpl');

}


}
?>