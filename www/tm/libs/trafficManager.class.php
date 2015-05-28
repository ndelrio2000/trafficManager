<?PHP
/*
Clase que maneja todo lo relacionado a la aplicacion
Mantiene la conexiona a la BD, la Clase Smarty para dibujar las interfaces, informacion de login...


*/

class TrafficManger{

    private $db;

    function TrafficManger(){
	$this->db = new Database($this->getConf('dbHost'), $this->getConf('dbUsername'), $this->getConf('dbPassword'), $this->getConf('dbName'));
	$this->username = "";
	$this->userDescription = "";
	$this->userLastLogin = "";
	
    }

    function getDb(){
	return $this->db;
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


    function get_server_memory_usage(){

        $free = shell_exec('free');
        $free = (string)trim($free);
        $free_arr = explode("\n", $free);
        $mem = explode(" ", $free_arr[1]);
        $mem = array_filter($mem);
        $mem = array_merge($mem);
        $memory_usage = $mem[2]/$mem[1]*100;

        return round($memory_usage,2);
    }

    function get_server_cpu_usage(){

        $load = sys_getloadavg();
        return round($load[0],2);
    }

function get_server_uptime(){
    $uptime = shell_exec("cut -d. -f1 /proc/uptime");
    $days = floor($uptime/60/60/24);
    $hours = $uptime/60/60%24;
    $mins = $uptime/60%60;
    $secs = $uptime%60;
    $resul="$days dias $hours horas $mins minutos y $secs segundos";
    return $resul;
}

    function getHostStatus()
    {

    //Obtengo la informacion de la Api de icinga-web
    $url = $this->getConf('icingaApiUrl')."host/filter[AND(HOST_CUSTOMVARIABLE_NAME|=|trafficManager)]/columns[HOST_NAME|HOST_CURRENT_STATE|HOST_OUTPUT|HOST_LAST_CHECK|HOST_NEXT_CHECK|HOST_CUSTOMVARIABLE_NAME|HOST_CUSTOMVARIABLE_VALUE]/authkey=".$this->getConf('icingaAuthKey')."/json";


    //A number that corresponds to the current state of the host: 0=UP, 1=DOWN, 2=UNREACHABLE.
    $response = file_get_contents($url);
    $results = json_decode ($response,true);

    return $results['result'];

    }

function getHostStatusSummary(){

    //Obtengo la informacion de la Api de icinga-web
    $url = $this->getConf('icingaApiUrl')."host/filter[AND(HOST_CUSTOMVARIABLE_NAME|=|trafficManager)]/columns[HOST_NAME|HOST_CURRENT_STATE|HOST_OUTPUT|HOST_LAST_CHECK|HOST_NEXT_CHECK|HOST_CUSTOMVARIABLE_NAME|HOST_CUSTOMVARIABLE_VALUE]/authkey=".$this->getConf('icingaAuthKey')."/json";


    //A number that corresponds to the current state of the host: 0=UP, 1=DOWN, 2=UNREACHABLE.
    $response = file_get_contents($url);
    $results = json_decode ($response,true);


    //devolver HostUP, HostDown, FilUP, FailDown

    $result= array('UP' => 0, 'DOWN' => 0,'FAILUP' => 0,'FAILDOWN' => 0);

    foreach ($results['result'] as $host){
	if (strpos($host['HOST_NAME'],'_failover') !== false){//es el host de failover
	    if ($host['HOST_CURRENT_STATE'] == 0){
		$result['FAILUP'] = $result['FAILUP'] + 1;
	    }else{
		$result['FAILDOWN'] = $result['FAILDOWN'] + 1;
	    }
	}else{
	    if ($host['HOST_CURRENT_STATE'] == 0){
		$result['UP'] = $result['UP'] + 1;
	    }else{
		$result['DOWN'] = $result['DOWN'] + 1;
	    }
	}
    }

    return $result;

}

    function hostIsUp($hostname)
    {

    //Obtengo la informacion de la Api de icinga-web
    $url = $this->getConf('icingaApiUrl')."host/filter[AND(HOST_NAME|=|".$hostname.")]/columns[HOST_CURRENT_STATE]/authkey=".$this->getConf('icingaAuthKey')."/json";


    //A number that corresponds to the current state of the host: 0=UP, 1=DOWN, 2=UNREACHABLE.
    $response = file_get_contents($url);

    $results = json_decode ($response,true);

    if ($results['result'][0]['HOST_CURRENT_STATE'] == 0){
	return TRUE;
    }else{
	return FALSE;
    }

    }

    //Devuelve la lista de zonas administradas por Traffic Manager.
    function getManagedZones()
    {
    //$this-> db = new Database($this->getConf('dbHost'), $this->getConf('dbUsername'), $this->getConf('dbPassword'), $this->getConf('dbName'));
    $dbb=new Database($this->getConf('dbHost'), $this->getConf('dbUsername'), $this->getConf('dbPassword'), $this->getConf('dbName'));
    $q = "SELECT * FROM managedDnsDomains WHERE active=1 ORDER BY domainame";
    $zonas=$dbb->fetch_all_array($q);
    return $zonas;

    }

    //Devuelve la zona de DNS pasada por parametro.
    function getDnsDomainByName($domainame)
    {
    //$this-> db = new Database($this->getConf('dbHost'), $this->getConf('dbUsername'), $this->getConf('dbPassword'), $this->getConf('dbName'));
    $dbb=new Database($this->getConf('dbHost'), $this->getConf('dbUsername'), $this->getConf('dbPassword'), $this->getConf('dbName'));
    $q = "SELECT * FROM managedDnsDomains WHERE domainame='$domainame'";

    $zonas=$dbb->fetch_all_array($q);

    return $zonas[0];

    }

    //Devuelve la lista de Hosts administradas por Traffic Manager.
    function getManagedHosts()
    {
    //$this-> db = new Database($this->getConf('dbHost'), $this->getConf('dbUsername'), $this->getConf('dbPassword'), $this->getConf('dbName'));
    $dbb=new Database($this->getConf('dbHost'), $this->getConf('dbUsername'), $this->getConf('dbPassword'), $this->getConf('dbName'));
    $q = "SELECT h.id,h.name,h.domainame,s.displayName FROM hosts h, supportedServices s WHERE h.service=s.name ORDER BY domainame,name";
    $hosts=$dbb->fetch_all_array($q);
    return $hosts;

    }

    function getHostById($id)
    {
    //$this-> db = new Database($this->getConf('dbHost'), $this->getConf('dbUsername'), $this->getConf('dbPassword'), $this->getConf('dbName'));
    $dbb=new Database($this->getConf('dbHost'), $this->getConf('dbUsername'), $this->getConf('dbPassword'), $this->getConf('dbName'));
    $q = "SELECT h.id,h.name,h.domainame,h.ipaddress,h.ipmonitaddress,h.failoveripaddress,h.failovermonitaddress,h.service,h.servicefailover,h.checkinterval,s.displayName,h.type FROM hosts h, supportedServices s WHERE h.id=$id AND h.service=s.name";
    $host=$dbb->fetch_all_array($q);
    return $host[0];
    }

    //Devuelve la lista de servicios soportados para hacer chequeos a un host
    function getSupportedServices()
    {
    //$this-> db = new Database($this->getConf('dbHost'), $this->getConf('dbUsername'), $this->getConf('dbPassword'), $this->getConf('dbName'));
    $dbb=new Database($this->getConf('dbHost'), $this->getConf('dbUsername'), $this->getConf('dbPassword'), $this->getConf('dbName'));
    $q = "SELECT name,displayName FROM supportedServices WHERE active=1 ORDER BY name";
    $services=$dbb->fetch_all_array($q);
    return $services;

    }

    //Agrega un host para el monitoreo
		     
    function addHost($hostname,$domainame,$ipaddr,$ipmonitaddr,$service,$failoveripaddr,$failoveripmonitaddr,$servicefailover,$checkinterval,$type){

	$dbb=new Database($this->getConf('dbHost'), $this->getConf('dbUsername'), $this->getConf('dbPassword'), $this->getConf('dbName'));

	$q = "SELECT name FROM hosts WHERE name='$hostname' and domainame='$domainame'";

	$cant=$dbb->num_rows($dbb->query($q));
	if ($cant > 0){
	    $this->tmLog('DEBUG','back','host',"Se obtuvo un error al agregar el host ".$hostname.".".$domainame." nombre de host duplicado");
	    return FALSE;
	}else{

	    $q = "INSERT INTO hosts(name,domainame,ipaddress,ipmonitaddress,service,failoveripaddress,failovermonitaddress,servicefailover,type,checkinterval) VALUES ('$hostname','$domainame','$ipaddr','$ipmonitaddr','$service','$failoveripaddr','$failoveripmonitaddr','$servicefailover','$type',$checkinterval)";
	    if ($dbb->query($q)){

		$dns =new Dns($this->getConf('dnsServer'),$this->getConf('dnsKeyName'),$this->getConf('dnsKeyHash'));

		if($dns->addHost($hostname,$ipaddr,$domainame,$this->getConf('dnsDefaultTtl'),$type)){

		    if ($this->icingaAddHost($hostname,$domainame,$ipaddr,$ipmonitaddr,$service,$failoveripaddr,$failoveripmonitaddr,$servicefailover,$checkinterval)){

			if ($this->icingaReloadConfiguration()){

			    return TRUE;

			}else{

			    $this->tmLog('DEBUG','back','host',"Error al hacer reload de Icinga para agregar el host ".$hostname.".".$domainame);
			    return FALSE;
			}


		    }else{

			$this->tmLog('DEBUG','back','host',"Error en Icinga al agregar el host ".$hostname.".".$domainame);
			return FALSE;
		    }

		}else{

		    $this->tmLog('DEBUG','back','host',"Error en Bind al agregar el host ".$hostname.".".$domainame);
		    return FALSE;
		}


	    }else{

		$this->tmLog('DEBUG','back','host',"Error en base de datos al agregar el host ".$hostname.".".$domainame);
		return FALSE;

	    }

	}
    }

    function editHost($id,$hostname,$domainame,$ipaddr,$ipmonitaddr,$service,$failoveripaddr,$failoveripmonitaddr,$servicefailover,$checkinterval,$type){

	$dbb=new Database($this->getConf('dbHost'), $this->getConf('dbUsername'), $this->getConf('dbPassword'), $this->getConf('dbName'));
	//$q = "UPDATE hosts SET name='$hostname',domainame='$domainame',ipaddress='$ipaddr',failoveripaddress='$failoveripaddr',service='$service',checkinterval=$checkinterval WHERE id=$id";
	//no permito que se cambie ni el nombre ni el dominio. SI se quiere cambiar eso, dar de baja y volver a dar de alta
	$q = "UPDATE hosts SET ipaddress='$ipaddr',ipmonitaddress='$ipmonitaddr',service='$service',failoveripaddress='$failoveripaddr',failovermonitaddress='$failoveripmonitaddr',servicefailover='$servicefailover',checkinterval=$checkinterval WHERE id=$id";
    	
	if ($dbb->query($q)){

	    $dns =new Dns($this->getConf('dnsServer'),$this->getConf('dnsKeyName'),$this->getConf('dnsKeyHash'));

	    if ($dns->updateHost($hostname,$ipaddr,$domainame,$this->getConf('dnsDefaultTtl'))){

		if ($this->icingaDeleteHost($hostname,$domainame)){

		    if ($this->icingaAddHost($hostname,$domainame,$ipaddr,$ipmonitaddr,$service,$failoveripaddr,$failoveripmonitaddr,$servicefailover,$checkinterval)){

			if ($this->icingaReloadConfiguration()){

			    return TRUE;
			}else{

			    $this->tmLog('DEBUG','back','host',"Error al hacer reload de Icinga para agregar el host ".$hostname.".".$domainame);
			    return FALSE;
			}
		    }else{

			$this->tmLog('DEBUG','back','host',"Error en Icinga al agregar el host ".$hostname.".".$domainame);
			return FALSE;
		    }

		}else{

			$this->tmLog('DEBUG','back','host',"Error en Icinga al eliminar el host ".$hostname.".".$domainame);
			return FALSE;

		}

	    }else{

		    $this->tmLog('DEBUG','back','host',"Error en Bind al editar el host ".$hostname.".".$domainame);
		    return FALSE;
	    }

	}else{

		$this->tmLog('DEBUG','back','host',"Error en base de datos al agregar el host ".$hostname.".".$domainame);
		return FALSE;
	}

    } //de la function


    function deleteHost($id){

	$dbb=new Database($this->getConf('dbHost'), $this->getConf('dbUsername'), $this->getConf('dbPassword'), $this->getConf('dbName'));
	$q = "SELECT name,domainame,type FROM hosts WHERE id=$id";

	$host=$dbb->fetch_all_array($q);

	$qd = "DELETE FROM hosts WHERE id=$id";

	if($dbb->query($qd)){

	    $dns =new Dns($this->getConf('dnsServer'),$this->getConf('dnsKeyName'),$this->getConf('dnsKeyHash'));

	    if ($dns->deleteHost($host[0]['name'],$host[0]['domainame'],$host[0]['type'])){

		if ($this->icingaDeleteHost($host[0]['name'],$host[0]['domainame'])){

		    if ($this->icingaReloadConfiguration()){

			return TRUE;

		    }else{
			
			    $this->tmLog('DEBUG','back','host',"Error al hacer reload de Icinga para eliminar el host ".$host[0]['name'].".".$host[0]['domainame']);
			    return FALSE;
		    }

		}else{

			$this->tmLog('DEBUG','back','host',"Error en Icinga al eliminar el host ".$host[0]['name'].".".$host[0]['domainame']);
			return FALSE;
		}
	    }else{

		    $this->tmLog('DEBUG','back','host',"Error en Bind al eliminar el host ".$host[0]['name'].".".$host[0]['domainame']);
		    return FALSE;
	    }

	}else{

		$this->tmLog('DEBUG','back','host',"Error en base de datos al eliminar el host ".$host[0]['name'].".".$host[0]['domainame']);
		return FALSE;
	}	    
}


function addDnsZone($zonename,$minimum,$primaryDns,$seccondaryDns){

	$dbb=new Database($this->getConf('dbHost'), $this->getConf('dbUsername'), $this->getConf('dbPassword'), $this->getConf('dbName'));

	$q = "SELECT domainame FROM managedDnsDomains WHERE domainame='$zonename'";

	$cant=$dbb->num_rows($dbb->query($q));
	if ($cant > 0){
	    $this->tmLog('DEBUG','back','dns',"Se obtuvo un error al agregar el dominio DNS ".$zonename." nombre de dominio duplicado");
	    return FALSE;
	}else{

	    $q = "INSERT INTO managedDnsDomains(domainame,primaryserver,seccondaryserver,minimum,active) VALUES ('$zonename', '$primaryDns', '$seccondaryDns', $minimum, 1)";

	    if ($dbb->query($q)){

		$dns =new Dns($this->getConf('dnsServer'),$this->getConf('dnsKeyName'),$this->getConf('dnsKeyHash'));

		if ($dns->addDnsZone($zonename,$minimum,$primaryDns,$seccondaryDns,$this->getManagedZones())){

		    return TRUE;

		}else{

		    $this->tmLog('DEBUG','back','dns',"Error en Bind al agregar el dominio de DNS ".$zonename);
		    return FALSE;
		}

	    }else{

		$this->tmLog('DEBUG','back','dns',"Error en base de datos al agregar el dominio de DNS ".$zonename);
		return FALSE;

	    }
    }
}

function dnsZoneIsEmpty($zonename){

    $dbb=new Database($this->getConf('dbHost'), $this->getConf('dbUsername'), $this->getConf('dbPassword'), $this->getConf('dbName'));
    $q = "SELECT id FROM hosts WHERE domainame='$zonename'";
    $zones=$dbb->num_rows($dbb->query($q));

    if ($zones > 0){
	return FALSE;
    }else{
	return TRUE;
    }

}



function deleteDnsDomain($zonename){

    //Verifico que no haya ningún host dado de alta en la zona de DNS previo a eliminarla
    if ($this->dnsZoneIsEmpty($zonename)){

	$dbb=new Database($this->getConf('dbHost'), $this->getConf('dbUsername'), $this->getConf('dbPassword'), $this->getConf('dbName'));
	$qd = "DELETE FROM managedDnsDomains WHERE domainame='$zonename'";

	if ($dbb->query($qd)){

	    $dns =new Dns($this->getConf('dnsServer'),$this->getConf('dnsKeyName'),$this->getConf('dnsKeyHash'));

	    if($dns->deleteDomain($zonename,$this->getManagedZones())){

		return TRUE;

	    }else{

		$this->tmLog('DEBUG','back','dns',"Error en Bind al agregar el dominio de DNS ".$zonename);
		return FALSE;
	    }
    
	}else{

	    $this->tmLog('DEBUG','back','dns',"Error en base de datos al eliminar el dominio de DNS ".$zonename);
	    return FALSE;
	}

    }else{
	
	$this->tmLog('DEBUG','back','dns',"Error al eliminar la zona de DNS ".$zonename." la zona no está vacia");
	return FALSE;

    }


}


    //Agrega el host a Icinga. En icinga agrego uno para monitorear la ip principal y otro para monitorear la IP de failover
		       
function icingaAddHost($hostname,$domainame,$ipaddr,$ipmonitaddr,$service,$failoveripaddr,$failoveripmonitaddr,$servicefailover,$checkinterval){

	include_once "/var/www/tm/configs/generalConfigs.php";
	include "trafficManager.Smarty.php";

	//Asigno todas las variables al template de Smarty
	$smarty->assign('hostname',$hostname.".".$domainame);
	$smarty->assign('ipaddr',$ipmonitaddr);
	$smarty->assign('dnsaddress',$ipaddr);
	$smarty->assign('failoverdnsaddress',$failoveripaddr);
	$smarty->assign('domain',$domainame);
	$smarty->assign('check',$service);
	$smarty->assign('checkinterval',$checkinterval);
	$smarty->assign('eventhandler',$this->getConf('icingaEventHandler'));
	$smarty->assign('eventHandlerEnabled',"true");
	$smarty->assign('isFailoverHost',0);

	//Escribo el chequeo para la IP principal Instancio al template y me quedo con la salida
	$output = $smarty->fetch('icingaObject.tpl'); 
	// Escribo la salida a un archivo
	$file= $this->getConf('icingaObjectsDir').$hostname.".".$domainame.".conf";
	if (file_put_contents($file,$output)){

	    //Escribo el chequeo para la IP de failover cambiando las variables
    	    $smarty->assign('hostname',$hostname.".".$domainame."_failover");
	    $smarty->assign('ipaddr',$failoveripmonitaddr);
	    $smarty->assign('dnsaddress',$failoveripaddr);
	    $smarty->assign('eventHandlerEnabled',1);
	    $smarty->assign('check',$servicefailover);
	    $smarty->assign('isFailoverHost',1);
	    // Instancio al template y me quedo con la salida
	    $output = $smarty->fetch('icingaObject.tpl'); 
	    // Escribo la salida a un archivo
	    $file= $this->getConf('icingaObjectsDir').$hostname.".".$domainame."_failover.conf";

	    if (file_put_contents($file,$output)){

		return TRUE;
	    }else{

		$this->tmLog('DEBUG','back','icinga',"Error en Icinga al escribir archivo de configuracion de host ".$file);
		return FALSE;
	    }

	}else{

	    $this->tmLog('DEBUG','back','icinga',"Error en Icinga al escribir archivo de configuracion de host ".$file);
	    return FALSE;

	}
}

function icingaDeleteHost($hostname,$domainame){

	include_once "/var/www/tm/configs/generalConfigs.php";
	//Borro el primario
	$file= $this->getConf('icingaObjectsDir').$hostname.".".$domainame.".conf";

	if (unlink($file)){

    	    //Borro el de failover
	    $file= $this->getConf('icingaObjectsDir').$hostname.".".$domainame."_failover.conf";

	    if (unlink($file)){

    		return TRUE;
	    }else{
		$this->tmLog('DEBUG','back','icinga',"Error en Icinga al eliminar archivo de configuracion de host ".$file);
		return FALSE;
	    }

	}else{
	    $this->tmLog('DEBUG','back','icinga',"Error en Icinga al eliminar archivo de configuracion de host ".$file);
	    return FALSE;
	}

}


function icingaReloadConfiguration(){

    $strCommandfile="/var/run/icinga2/cmd/icinga2.cmd";
    $strCommandString = "[".mktime()."] RESTART_PROCESS;".mktime();
    $timeout = 3;
    $old = ini_set('default_socket_timeout', $timeout);
    $resCmdFile = fopen($strCommandfile,"w");
    ini_set('default_socket_timeout', $old);
    stream_set_timeout($resCmdFile, $timeout);
    stream_set_blocking($resCmdFile, 0);

    if ($resCmdFile){

	fputs($resCmdFile,$strCommandString);
        fclose($resCmdFile);
	
	$this->tmLog('DEBUG','back','icinga',"Se reinicio Icinga");

	return TRUE;

    }else{

	$this->tmLog('DEBUG','back','icinga',"Error al reiniciar Icinga");

	return FALSE;	

    }

}

function goToFailover($hostname, $failoveripaddress, $domainame){

    $dns =new Dns($this->getConf('dnsServer'),$this->getConf('dnsKeyName'),$this->getConf('dnsKeyHash'));

    if ($dns->updateHost($hostname,$failoveripaddress,$domainame,$this->getConf('dnsDefaultTtl'))){

	$dbb=new Database($this->getConf('dbHost'), $this->getConf('dbUsername'), $this->getConf('dbPassword'), $this->getConf('dbName'));

	$qu = "UPDATE hosts SET isInFailover=1 WHERE  name='$hostname' and domainame='$domainame'";

	if ($dbb->query($qu)){

	    return TRUE;
	}else{

	    $this->tmLog('DEBUG','back','icinga',"Error al actualizar la bse de datos para ir a failover con el host".$hostname);
		
	    return FALSE;
	}

    }else{

	$this->tmLog('DEBUG','back','icinga',"Error al actualizar DNS para ir a failover con el host".$hostname);
		
	return FALSE;
    }

}


function backFromFailover($hostname, $ipaddress, $domainame){

    $dns =new Dns($this->getConf('dnsServer'),$this->getConf('dnsKeyName'),$this->getConf('dnsKeyHash'));

    if ($dns->updateHost($hostname,$ipaddress,$domainame,$this->getConf('dnsDefaultTtl'))){

	$dbb=new Database($this->getConf('dbHost'), $this->getConf('dbUsername'), $this->getConf('dbPassword'), $this->getConf('dbName'));

	$qu = "UPDATE hosts SET isInFailover=0 WHERE name='$hostname' AND domainame='$domainame'";

	if($dbb->query($qu)){

    	    return TRUE;
	}else{

	    $this->tmLog('DEBUG','back','icinga',"Error al actualizar la bse de datos para volver de failover con el host".$hostname);
		
	    return FALSE;
	}

    }else{

	$this->tmLog('DEBUG','back','icinga',"Error al actualizar DNS para volver de failover con el host".$hostname);
		
	return FALSE;
    }

}


function icingaShowLog($f_desde,$f_hasta){

    $dbb=new Database($this->getConf('dbHost'), $this->getConf('dbUsername'), $this->getConf('dbPassword'), $this->getConf('icingaDbName'));

    $q = "SELECT * FROM icinga_logentries, icinga_objects WHERE (icinga_logentries.logentry_time BETWEEN '$f_desde' AND '$f_hasta') AND icinga_logentries.object_id = icinga_objects.object_id ORDER BY icinga_logentries.logentry_time DESC";

    $logs=$dbb->fetch_all_array($q);

    return $logs;
}


function icingaShowAuditLog($f_desde,$f_hasta){

    $dbb=new Database($this->getConf('dbHost'), $this->getConf('dbUsername'), $this->getConf('dbPassword'), $this->getConf('dbName'));

    $q = "SELECT * FROM tmlogs WHERE (logdate BETWEEN '$f_desde' AND '$f_hasta') ORDER BY logdate DESC";

    $logs=$dbb->fetch_all_array($q);

    return $logs;
}



function addUser($description,$username,$password1,$password2){

	$dbb=new Database($this->getConf('dbHost'), $this->getConf('dbUsername'), $this->getConf('dbPassword'), $this->getConf('dbName'));

	$q = "SELECT username FROM users WHERE username='$username'";
	$cant=$dbb->num_rows($dbb->query($q));

	if ($cant > 0){
	    $this->tmLog('DEBUG','back','user',"Error al agregar usuario ".$username." usuario duplicado");
	    return FALSE;

	}else{

	    $hashPassword = crypt($password1);

	    if ($hashPassword != (crypt($password2,$hashPassword))){
		$this->tmLog('DEBUG','back','user',"Error al agregar usuario ".$username." las password no coinciden");
		return FALSE;

	    }else{

		$q = "INSERT INTO users(username,password,description) VALUES ('$username', '$hashPassword', '$description')";

		if ($dbb->query($q)){

		    return TRUE;

		}else{

		    $this->tmLog('DEBUG','back','user',"Error al agregar usuario ".$username." a la base de datos");

		    return FALSE;
		}

	    }
	}

}

function editUser($description,$username,$password1,$password2){

	$dbb=new Database($this->getConf('dbHost'), $this->getConf('dbUsername'), $this->getConf('dbPassword'), $this->getConf('dbName'));

	if ($password1 == ""){
		$q = "UPDATE users SET description='$description' WHERE username='$username'";
		if ($dbb->query($q)){
		    return TRUE;
		}else{
		    $this->tmLog('DEBUG','back','user',"Error al editar el usuario ".$username." en la base de datos");
		    return FALSE;
		}
	}else{
	    $hashPassword = crypt($password1);
	    if ($hashPassword != (crypt($password2,$hashPassword))){
		$this->tmLog('DEBUG','back','user',"Error al editar al usuario ".$username." las password no coinciden");
		return FALSE;
	    }else{
		$q = "UPDATE users SET description='$description',password='$hashPassword' WHERE username='$username'";

		if ($dbb->query($q)){
		    return TRUE;
		}else{
		    $this->tmLog('DEBUG','back','user',"Error al editar el usuario ".$username." en la base de datos");
		    return FALSE;
		}

	    }
	}

}

function deleteUser($username){

	$dbb=new Database($this->getConf('dbHost'), $this->getConf('dbUsername'), $this->getConf('dbPassword'), $this->getConf('dbName'));

	$qd = "DELETE FROM users WHERE username='$username'";

	if ($dbb->query($qd)){
	    return TRUE;
	}else{
	    $this->tmLog('DEBUG','back','user',"Error al eliminar el usuario ".$username." en la base de datos");
	    return FALSE;
	}

}


function getUsers(){

    $dbb=new Database($this->getConf('dbHost'), $this->getConf('dbUsername'), $this->getConf('dbPassword'), $this->getConf('dbName'));
    $q = "SELECT username, description,lastlogin FROM users ORDER BY username";
    $users=$dbb->fetch_all_array($q);
    return $users;

}

function getUserByName($username){

    $dbb=new Database($this->getConf('dbHost'), $this->getConf('dbUsername'), $this->getConf('dbPassword'), $this->getConf('dbName'));
    $q = "SELECT username, description,lastlogin FROM users WHERE username='$username'";
    $user=$dbb->fetch_all_array($q);
    return $user[0];
}

function validateUser($username,$password){

    $dbb=new Database($this->getConf('dbHost'), $this->getConf('dbUsername'), $this->getConf('dbPassword'), $this->getConf('dbName'));
    $q = "SELECT username,password,description,lastlogin FROM users WHERE username='$username'";
    $user=$dbb->fetch_all_array($q);

    $passIngresada = crypt($password, $user[0]['password']);

    if( $passIngresada == $user[0]['password']) {  

	    //Actualizar lastLogin y carar variables de TM con sus datos
	    $user=$dbb->fetch_all_array($q);
	    $this->username = $user[0]['username'];
	    $this->userDescription = $user[0]['description'];
	    $this->userLastLogin = $user[0]['lastlogin'];
	    $this->userUpdateLastLogin($username);
    
	    return TRUE;

	}else{

	    $this->tmLog('DEBUG','back','user',"Error de Login con el usuario ".$username." las password ingresada no es valida o el usuario no existe");

	    return FALSE;
	}


}


function userUpdateLastLogin($username){

    $dbb=new Database($this->getConf('dbHost'), $this->getConf('dbUsername'), $this->getConf('dbPassword'), $this->getConf('dbName'));

    $q = "UPDATE users SET lastlogin='".date("Y-m-d H:i:s")."' WHERE username='$username'";

    $r = $dbb->query($q);

}


function tmLog($logtype,$logsource,$logmodule,$description){

    $dbb=new Database($this->getConf('dbHost'), $this->getConf('dbUsername'), $this->getConf('dbPassword'), $this->getConf('dbName'));
    $q = "INSERT INTO tmlogs(logtype,logsource,logmodule,description) VALUES ('$logtype', '$logsource', '$logmodule', '$description')";
    $r = $dbb->query($q);


}

}//de la clase


