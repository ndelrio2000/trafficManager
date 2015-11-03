#!/usr/bin/php
<?PHP
/*
Event handler Script that receives all the services and host state changes from icinga
It's called from icinga when a monitoring event change ocurrs

command = "/opt/trafficmanager/nagiosplugins/notify_traffic_manager.php $host.name$ $host.vars.domain$ $host.address$ $host.vars.failoveraddress$ $host.state$ $host.state_type$ $host.vars.isfailoverhost$ $host.last_state$ "
/opt/trafficmanager/nagiosplugins/notify_traffic_manager 'SERVICE' $HOSTNAME$ $HOSTADDRESS$ $HOSTADDRESS6$ $HOSTSTATE$ $LASTHOSTSTATE$ $HOSTSTATETYPE$ $HOSTATTEMPT$ $HOSTPERCENTCHANGE$ $LASTHOSTCHECK$ $HOSTOUTPUT$ $LONGHOSTOUTPUT$ $HOSTCHECKCOMMAND$

command_line                   	/opt/trafficmanager/nagiosplugins/notify_traffic_manager.php '$HOSTNAME$' '$HOSTDOMAIN' '$HOSTADDRESS$' '$HOSTADDRESS6$' '$HOSTSTATE$' '$HOSTSTATEID$' '$LASTHOSTSTATE$' '$LASTHOSTSTATEID$' '$HOSTSTATETYPE$' '$HOSTATTEMPT$' '$MAXHOSTATTEMPTS$' '$HOSTPERCENTCHANGE$' '$LASTHOSTCHECK$' '$HOSTOUTPUT$' '$LONGHOSTOUTPUT$' '$HOSTCHECKCOMMAND$' '$SERVICEDESC$' '$SERVICESTATE$' '$SERVICESTATETYPE$'

*/
include ("/opt/trafficmanager/nagiosplugins/libs/database.class.php");
include ("/opt/trafficmanager/nagiosplugins/libs/dns.class.php");
include ("/opt/trafficmanager/nagiosplugins/libs/trafficManager.class.php");


function writelog($message){
    $STDOUT = fopen('/opt/trafficmanager/monitoring.log', 'a');
    fwrite($STDOUT, $message); 
    fclose($STDOUT);
}

$tm = new TrafficManger;

$name = explode (".",$argv[1]);

$hostname = $name[0];
$domainame = $argv[2];
$ipaddress = $argv[3];
$failoveripaddress = $argv[4];
$hoststatus = $argv[5];
$checkstatus = $argv[6];
$isfailoverhost = $argv[7];

$tm->tmLog('DEBUG','cli','em',"Cambio de Estado en $hostname.$domainame. IP: $ipaddress. Failover IP: $failoveripaddress. Estado: $hoststatus/$checkstatus");

if ($isfailoverhost == 0){//El host que se cayo es el principal

    if ($hoststatus == 'UP'){//El estado es UP y el host es el principal

	if ($tm->backFromFailover($hostname,$ipaddress,$domainame)){

	    $tm->tmLog('INFO','cli','em',"Se vuelve de Failover del HOST $hostname.$domainame. IP Primaria: $ipaddress. Estado: $hoststatus/$checkstatus");

	}else{

	    $tm->tmLog('ERR','cli','em',"Problema para volver de Failover del HOST $hostname.$domainame. IP Primaria: $ipaddress. Estado: $hoststatus/$checkstatus");

	}
	    
    }else{//se cayo el host principal

	if ($hoststatus == 'DOWN'){//Pregunto porque puede estar el estado WARN, pero en ese caso no hago nada

	    if ($checkstatus == 'HARD') {// Esta confirmada la caida. El otro estado puede ser SOFT

		$failoverHostname = $hostname.".".$domainame."_failover";

		if ($tm->hostIsUp($failoverHostname)){

		    if ($tm->goToFailover($hostname,$failoveripaddress,$domainame)){

			$tm->tmLog('INFO','cli','em',"Se va a Failover en el HOST $hostname.$domainame. IP de Failover: $failoveripaddress. Estado de HOST principal: $hoststatus/$checkstatus");

		    }else{

			$tm->tmLog('ERR','cli','em',"Problema para ir a Failover en el HOST $hostname.$domainame. IP de Failover: $failoveripaddress. Estado de HOST principal: $hoststatus/$checkstatus");

		    }

		}else{// Tengo que hacer failover, pero el host de failover esta tambien caido

		    $tm->tmLog('WARN','cli','em',"Al intentar ir a Failover en el HOST $hostname.$domainame, se detecta que el host de failover con IP: $failoveripaddress, esta caido. No se toma ninguina accion");

		}

	    }else{//Por el else no hago nada porque podria ocurrir que se caiga temporalmente y que luego levante


		    $tm->tmLog('WARN','cli','em',"Se detecto una caida en el HOST $hostname.$domainame, del tipo SOFT. Se continuan los chequeos para determinar si es definitiva. No se toma ninguina accion.");

	    }

	}

    }

}else{//El que esta noficando un cambio es el host de failover

    writelog("Host: $hostname de FAILOVER, Estado:$hoststatus/$checkstatus\n"); 

    if ($hoststatus == 'UP'){

	$hostPrimario = $hostname.".".$domainame;

	if (!($tm->hostIsUp($hostname.".".$domainame))){

	    if ($tm->goToFailover($hostname,$failoveripaddress,$domainame)){

		$tm->tmLog('INFO','cli','em',"El host de failover de $hostname.$domainame informo un cambio de estado y el host principal esta caido. Se hace cailover a IP de Failover: $failoveripaddress. Estado de HOST de failover: $hoststatus/$checkstatus");

	    }else{

		$tm->tmLog('INFO','cli','em',"Problema al ir a failover con el host de failover de $hostname.$domainame");

	    }

	}else{

	    $tm->tmLog('INFO','cli','em',"El host de failover de $hostname.$domainame informo un cambio de estado $hoststatus/$checkstatus");

	}

    }else{

	$tm->tmLog('INFO','cli','em',"El host de failover de $hostname.$domainame informo un cambio de estado $hoststatus/$checkstatus");

    }
	

}

?>


