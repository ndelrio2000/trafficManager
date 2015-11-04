<?PHP
$config['icingaAuthKey']="d3OuOUFo5slDsfpDsXUXV15Z7SZ";
$config['icingaApiUrl']="http://".$_SERVER['SERVER_ADDR']."/icinga-web/web/api/";
$config['icingaObjectsDir']="/etc/icinga2/conf.d/";
$config['icingaEventHandler']="notify_traffic_manager";



$config['baseDir']="/var/www/tm/";
$config['dbHost']="localhost";
$config['dbUsername']="trafficManager";
$config['dbPassword']="managerTraffic";
$config['dbName']="trafficManager";
$config['icingaDbName']="icinga2idomysql";



$config['dnsServer']="localhost";
$config['dnsKeyName']="trafficmanager";
$config['dnsKeyHash']="FdrDDkrD/2Cel5c8IJFdEd9N71mvXY6lH3slqe7PPvpXme70XM2V3rclvbBdIwvvv2wnrLkKUzhQCflJ4UBz1Q==";
$config['dnsDefaultTtl']="60";
$config['dnsDefaultGlobalTtl']="300";
$config['bindDefinitionsFile']="/etc/bind/named.conf.trafficManager";
$config['bindZonesDir']="/etc/bind/trafficManagerZones/";
$config['bindReloadCommand']=$config['baseDir']."/modules/dns/bin/restart_named";


?>