<?php
//define('IWEITE_ROOT', str_replace("\\", '/', dirname(dirname(__FILE__))));
define('IWEITE_ROOT', "/Applications/MAMP/htdocs/addons");
define('CACHE_ROOT', IWEITE_ROOT."/iweite_vods/caches/");
define('SITE_ROOT', IWEITE_ROOT."/iweite_vods/");

require "/Applications/MAMP/htdocs/addons/iweite_vods/lang.php";
require "/Applications/MAMP/htdocs/framework/function/global.func.php";
const RES = "../../../../../addons/iweite_vods/template";
global $_GPC;
$classarr = array(
	 array('title' => 'movie'),
	 array('title' => 'tv show'));
$adsarr = array();
$ilist = array('' => 1, );
$setting = array('cnzz' => 2,'title' => 'compile page' );
$copyright = "";
include("index.php");

?>