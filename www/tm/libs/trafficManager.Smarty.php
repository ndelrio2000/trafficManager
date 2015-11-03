<?PHP
require_once("Smarty/Smarty.class.php");
require("/var/www/tm/configs/generalConfigs.php");
$smarty = new Smarty;
$smarty->template_dir = $config['baseDir']."templates/";
$smarty->compile_dir =  $config['baseDir']."var/templates_c/";
$smarty->cache_dir =    $config['baseDir']."var/cache/";
$smarty->debugging =    false;
$smarty->compile_check =    false;
$smarty->force_compile =    true;
?>