<?php
class Sim_html
{
    //public $modulename ＝ "";
	    /**
     * Class constructor
     */
    function __construct() {
    }
    public $modulename='';
    public function index($content){
        //echo "html.index";

            	
    	//$file = IWEITE_ROOT."/iweite_vods/spec/index.html";
        //暂时先放在addon目录下（由于css res变量的重定向问题）.样式才能正常显示，以后再改吧
        $file = IWEITE_ROOT . "/index.html";
        //echo "ob start";
    	ob_start();
        //echo "ob fuck";
    	$site = 'baidu.com';
        global $_W,$_GPC;
    	//include(IWEITE_ROOT."/iweite_vods/template/mobile/index_shadow.html");
        //include(_T('mobile','index_shadow'));
    	//include(IWEITE_ROOT."/iweite_vods/template/mobile/index.html");
        //include(_T('mobile','index'));
        //echo "content>";

        $weid = !empty($_W['uniacid']) ? $_W['uniacid'] : intval($_GET['weid']); 
        $weite_jsdk =$_W['siteroot'] . 'app' . str_replace("./","/",_MobileUrl('jsdk', array(), true)); 
        $setting = pdo_fetch("SELECT * FROM " . tablename($this->modulename . '_setting') . " WHERE weid = :weid ", array(':weid' => $_W['uniacid'])); $title = empty($setting) ? "微特大电影" : $setting['title']; $copyright = empty($setting) ? "&copy;微特大电影" : $setting['copyright']; 
        $copyright = preg_replace('/\s{2,}/', '<li>', $copyright); 
        $share = json_decode($setting["share"], true); 
        if(empty($setting['indexpagesize'])){ $indexpagesize=6; }
        else{ 
            $indexpagesize=$setting['indexpagesize']; 
        } 
        $share_images = $_W['siteroot'] . "addons/iweite_vods/icon.jpg"; 
        $share_image = empty($share['share_image']) ? $share_images : $share['share_image']; 
        $share_title = empty($share['share_title']) ? $setting['title'] : $share['share_title']; 
        $share_desc = empty($share['share_desc']) ? $setting['title'] : $share['share_desc']; 
        $share_url = empty($share['share_url']) ? $_W['siteroot'] . 'app' . str_replace("./","/",_MobileUrl('index', array(), true)) : $share['share_url']; 
        $classarr = pdo_fetchall("SELECT * FROM " . tablename($this->modulename . '_category') . " WHERE weid = '{$_W['uniacid']}' ORDER BY sid DESC, tid DESC"); 
        $adsarr = pdo_fetchall("SELECT * FROM " . tablename($this->modulename . '_guanggao') . " WHERE weid = '{$_W['uniacid']}' and classid=0 ORDER BY sid DESC, tid DESC"); 
        //$titlearr = array("首播影院","卫视热播"); 
        //$titlearr = array(_l('recommand1'),_l('recommand2')); 
        $list = pdo_fetchall('SELECT * FROM' . tablename($this->modulename . '_category') . " WHERE weid= :weid ORDER BY sid DESC, tid DESC limit 2", array(':weid' => $_W['uniacid'])); 
        foreach ($list as $key => $value) { 
            $ilist[$key]['title'] = $titlearr[$key]; 
            $ilist[$key]['tid'] = $value['tid']; 
            $temp = pdo_fetchall('SELECT * FROM ' . tablename($this->modulename . '_ziyuan') . " WHERE weid = '{$_W['uniacid']}' and classid={$value['tid']} and recommend=1 and isok=0 ORDER BY recommend DESC,dateline DESC, sid DESC, tid DESC limit {$indexpagesize}", array(':weid' => $_W['uniacid'])); 
            $ilist[$key]['temp'] = $temp; 
        } 
        include($content->_T('mobile','index'));
        //echo "content yes>";
    	//$data = "ttt";
    	$data = ob_get_contents();
        ob_clean();

    	file_put_contents($file, $data);
        if(!is_writable($file)) {
            // $file = str_replace(WWW_ROOT,'',$file);
            // MSG(L('file').'：'.$file.'<br>'.L('not_writable'));
            echo "not writeable to ".$file;
        }

        //echo "sim_html_index() 11";
    }

    public function gen_list($content){
        $file = IWEITE_ROOT . "/list.html";
        ob_start();
        global $_GPC, $_W; 
        $weid = !empty($_W['uniacid']) ? $_W['uniacid'] : intval($_GET['weid']);
        $weite_jsdk =$_W['siteroot'] . 'app' . str_replace("./","/",_MobileUrl('jsdk', array(), true)); 
        $tid = intval($_GPC['id']); 
        echo $tid;
        $item = pdo_fetch("SELECT * FROM " . tablename($this->modulename . '_category') . " WHERE tid = '$tid ' and weid = '$weid' limit 1"); 
        if (empty($item)) { message('抱歉，不存在或是已经被删除！'.$tid, _Mobileurl('index'), 'error'); } 
        // $tid = $item["tid"]; 
        // $tt = $item['title']; 
        // $setting = pdo_fetch("SELECT * FROM " . tablename($this->modulename . '_setting') . " WHERE weid = :weid ", array(':weid' => $_W['uniacid'])); 
        // $title = empty($setting) ? "微特大电影" : $setting['title']; 
        // $copyright = empty($setting) ? "&copy;微特大电影" : $setting['copyright']; 
        // $copyright = preg_replace('/\s{2,}/', '<li>', $copyright); 
        // $guanzhu = $setting['guanzhu']; 
        // $gz = json_decode($guanzhu, true); 
        // $share = json_decode($setting["share"], true); 
        // $share_images = $_W['siteroot'] . "addons/iweite_vods/icon.jpg"; 
        // $share_image = empty($share['share_image']) ? $share_images : $share['share_image']; 
        // $share_title = empty($share['share_title']) ? $setting['title'] : $share['share_title']; 
        // $share_desc = empty($share['share_desc']) ? $setting['title'] : $share['share_desc']; 
        // $share_url = empty($share['share_url']) ? $_W['siteroot'] . 'app' . str_replace("./","/",_MobileUrl('index', array(), true)) : $share['share_url']; 
        // $classarr = pdo_fetchall("SELECT * FROM " . tablename($this->modulename . '_category') . " WHERE weid = '{$weid}' ORDER BY sid DESC, tid DESC"); 
        // $pindex = max(1, intval($_GPC['page'])); 
        // $psize = empty($setting["pagesize"]) ? 15 : $setting["pagesize"]; 
        // $params = array(); $condition = ' where  classid = :classid and weid = :weid and isok=0'; 
        // $params[':classid'] = $tid; 
        // $params[':weid'] = $weid; 
        // $order_condition = " ORDER BY recommend DESC,dateline DESC,sid DESC,tid DESC "; 
        // $sql = 'SELECT * FROM ' . tablename($this->modulename . '_ziyuan') . $condition . $order_condition . " LIMIT " . ($pindex - 1) * $psize . ',' . $psize; 
        // $items = pdo_fetchall($sql, $params); 
        // $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->modulename . '_ziyuan') . $condition . $order_condition, $params); 

        // $pager = pagination($total, $pindex, $psize); 
        //include $this->template('list'); 
        include($content->_T('mobile','list'));
        
        $data = ob_get_contents();
        ob_clean();

        file_put_contents($file, $data);
        if(!is_writable($file)) {
            // $file = str_replace(WWW_ROOT,'',$file);
            // MSG(L('file').'：'.$file.'<br>'.L('not_writable'));
            echo "not writeable to ".$file;
        }
    }
}
?>