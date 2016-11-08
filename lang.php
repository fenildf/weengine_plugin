<?php
class Lang
{
    public $footer = array();
    public $header = null;
    public function __construct()
    {
        // global $_W;
        // $this->autoFinishOrders();
        // if (is_weixin()) {
        //     m('member')->checkMember();
        // }
    }
    public  function autoFinishOrders()
    {
    	echo "auto newt_finished(oid)";
    }
}

require IWEITE_ROOT."/iweite_vods/language/game.inc.php";
//	class写法：
//		$lang = new Lang();
//	$lang->autoFinishOrders();

//	func写法：
function _l($key){
	// if(define('SITE_LANG')){
	// 	if(SITE_LANG=='game'){
	// 		return $Lang[$key]; 
	// 	}
	// }
	// return $key;
	global $Lang;
	return $Lang[$key];
}

?>