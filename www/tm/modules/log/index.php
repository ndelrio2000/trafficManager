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


if (!isset($_POST['f_desde']) or !isset($_POST['f_hasta'])){
    $f_desde=date('Y-m-d')." 00:00:00";
    $f_hasta=date('Y-m-d')." 23:59:59";
}else{
    $f_desde=str_replace("/","-",$_POST['f_desde']);
    $f_hasta=str_replace("/","-",$_POST['f_hasta']);
}

    $impr = print_r($_POST,true);

if (isset($_GET['type']) and $_GET['type'] == "audit"){
    $smarty->assign('logs',$_SESSION["trafficManager"]->icingaShowAuditLog($f_desde,$f_hasta));
    $smarty->display('logsAudit.tpl');
}else{
    $smarty->assign('logs',$_SESSION["trafficManager"]->icingaShowLog($f_desde,$f_hasta));
    $smarty->display('logs.tpl');
}

//$smarty->assign('sesion',$_SESSION["trafficManager"]->getDb());



}
?>