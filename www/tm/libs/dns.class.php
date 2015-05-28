<?PHP
/*
Clase que maneja todo lo relacionado al servicio DNS

*/

class Dns{

    private $dnsServer;
    private $keyName;
    private $keyHash;
    private $nsupdateCommand;
    private $templateToExec;

    function __construct($dnsServer, $keyName, $keyHash) {
        $this -> dnsServer = $dnsServer;
        $this -> keyName = $keyName;
        $this -> keyHash = $keyHash;
	$this -> nsupdateCommand = "/usr/bin/nsupdate -y ".$this->keyName.":".$this->keyHash;
    }


    function getConf($key)
    {
    	include("/var/www/tm/configs/generalConfigs.php");

        if (isset($config[$key])) {
            return $config[$key];
        }else{
            trigger_error("Key $key does not exist", E_USER_NOTICE);
        }
    }

    function addHost($hostname,$ipaddr,$zone,$ttl = 3600,$type){


	if ($type == 'A'){
	    $command = "server $this->dnsServer\nzone $zone\nprereq nxdomain $hostname.$zone\nupdate add $hostname.$zone $ttl A $ipaddr\nsend\n";
	}else{
	    $command = "server $this->dnsServer\nzone $zone\nprereq nxdomain $hostname.$zone\nupdate add $hostname.$zone $ttl A $ipaddr\n";

	    $recordType=substr($type,0,2);
	    $recordPrio=substr($type,-2);

	    if ($recordType == 'MX'){

		$command = $command."update add $zone $ttl $recordType $recordPrio $hostname.$zone\nsend\n";

	    }else{
		//TIpo de registro de DNS no soportado
		return FALSE;
	    }

	}

	if ($this->execute($command)){

	    return TRUE;

	}else{

	    return FALSE;
	}

    }


    function deleteHost($hostname,$zone,$type){

	if ($type == 'A'){

	    $command = "server $this->dnsServer\nzone $zone\nprereq yxdomain $hostname.$zone\nupdate delete $hostname.$zone A\nsend\n";

	}else{

	    $command = "server $this->dnsServer\nzone $zone\nprereq yxdomain $hostname.$zone\nupdate delete $hostname.$zone A\n";

	    $recordType=substr($type,0,2);
	    $recordPrio=substr($type,-2);

	    if ($recordType == 'MX'){

		$command = $command."update delete $zone $recordType $recordPrio $hostname.$zone\nsend\n";

	    }else{

		//Tipo de registro de DNS no soportado
		return FALSE;
	    }
	}

	if ($this->execute($command)){
	    return TRUE;
	}else{
	    return FALSE;
	}



    }

    function updateHost($hostname,$newIpaddr,$zone,$ttl = 3600){

	$command = "server $this->dnsServer\nzone $zone\nprereq yxdomain $hostname.$zone\nupdate delete $hostname.$zone A\nupdate add $hostname.$zone $ttl A $newIpaddr\nsend\n";

	if ($this->execute($command)){
	    return TRUE;
	}else{
	    return FALSE;
	}

    }

    function execute($command){

	$tmpfile="/tmp/tm.nsupdate.".date("YmdHis");
	$fp = fopen($tmpfile, 'w');
	fwrite($fp, $command);
	fclose($fp);
    
	exec ("cat $tmpfile | /usr/bin/nsupdate -y $this->keyName:$this->keyHash",$output,$retval);
	//unlink($tmpfile);

	if ($retval == 0) {
	    return TRUE;
	}else{
	    return FALSE;
	}
    }

function addDnsZone($zonename,$minimum,$primaryDns,$seccondaryDns,$managedZones){

	include_once "/var/www/tm/configs/generalConfigs.php";
	include "trafficManager.Smarty.php";

	//Asigno todas las variables al template de Smarty
	$smarty->assign('dnsDefaultGlobalTtl',$this->getConf('dnsDefaultGlobalTtl'));
	$smarty->assign('zonename',$zonename);
	$smarty->assign('minimum',$minimum);
	$smarty->assign('primaryDns',$primaryDns);
	$smarty->assign('seccondaryDns',$seccondaryDns);

	//Escribo el archivo de la zona de DNS
	// Instancio al template y me quedo con la salida
	$output = $smarty->fetch('dnsZone.tpl'); 
	// Escribo la salida a un archivo
	$file= $this->getConf('bindZonesDir').$zonename.".conf";
	if (file_put_contents($file,$output)){

	    //Rescribo el archivo de definicion global de zonas de TM
	    if ($this->rewriteDefinitionsFile($managedZones)){
		return TRUE;
	    }else{
		return FALSE;
	    }
	}else{
	    return FALSE;
	}
}

function rewriteDefinitionsFile($managedZones){

	include_once "/var/www/tm/configs/generalConfigs.php";
	include "trafficManager.Smarty.php";

	$smarty->assign('zones',$managedZones);
	$smarty->assign('bindZonesDir',$this->getConf('bindZonesDir'));
	$smarty->assign('dnsKeyName',$this->getConf('dnsKeyName'));

	//Escribo el archivo de la zona de DNS
	// Instancio al template y me quedo con la salida
	$output = $smarty->fetch('named.conf.trafficManager.tpl'); 
	// Escribo la salida a un archivo
	$file= $this->getConf('bindDefinitionsFile');
	if (file_put_contents($file,$output)){

	    exec($this->getConf('bindReloadCommand'),$output,$retval);

	    if ($retval == 0) {

		return TRUE;

	    }else{

		return FALSE;
	    }

	}else{

	    return FALSE;
	}

}


function deleteDomain($zonename,$managedZones){


    include_once "/var/www/tm/configs/generalConfigs.php";
    //Borro el primario
    $file= $this->getConf('bindZonesDir').$zonename.".conf";
    if (unlink($file)){
	unlink($file.".jnl");
	//Reescribo el archivo de definicion global de zonas de TM
	return $this->rewriteDefinitionsFile($managedZones);

    }else{

	return FALSE;
    }

}


}