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




//Obtiene el estado del sistema para mostrar los Odometros del Dashboard
$memoryStatus=$_SESSION["trafficManager"]->get_server_memory_usage();
$cpuStatus=$_SESSION["trafficManager"]->get_server_cpu_usage();
$serverUptime=$_SESSION["trafficManager"]->get_server_uptime();
$primaryDnsStatus=$_SESSION["trafficManager"]->hostIsUp("dns.localhost");
$seccondaryDnsStatus=FALSE;  //CAMBIARRRRR
//$seccondaryDnsStatus=$_SESSION["trafficManager"]->hostIsUp("dns.localhost");  //CAMBIARRRRR
$icingaStatus=$_SESSION["trafficManager"]->hostIsUp("icinga.localhost");
$httpStatus=$_SESSION["trafficManager"]->hostIsUp("http.localhost");
$mysqlStatus=TRUE;								//CAMBIARRRRR

$smarty->assign('memoryStatus',$memoryStatus);
$smarty->assign('cpuStatus',$cpuStatus);
$smarty->assign('serverUptime',$serverUptime);
$smarty->assign('primaryDnsStatus',$primaryDnsStatus);
$smarty->assign('seccondaryDnsStatus',$seccondaryDnsStatus);
$smarty->assign('icingaStatus',$icingaStatus);
$smarty->assign('httpStatus',$httpStatus);
$smarty->assign('mysqlStatus',$mysqlStatus);

$smarty->assign('hostStatus',$_SESSION["trafficManager"]->getHostStatus());
$smarty->assign('hostStatusSummary',$_SESSION["trafficManager"]->getHostStatusSummary());

$smarty->assign('sesion',$_SESSION["trafficManager"]->getDb());

$smarty->assign('loggedUser',$_SESSION["trafficManager"]->username);
$smarty->assign('loggedUserDescription',$_SESSION["trafficManager"]->userDescription);
$smarty->assign('loggedUserLastLogin',$_SESSION["trafficManager"]->userLastLogin);


$smarty->display('dashboard.tpl');
}
?>