<?php 

defined('IN_IA') or exit('Access Denied');
define('RES', '../addons/iweite_vods/template'); 
class iweite_vodsModuleSite extends WeModuleSite { 
	public $modulename = 'iweite_vods'; 
	public function doMobilejsdk() { 
		require_once "jssdk.class.php";
		global $_W, $_GPC;
		$url =trim($_GPC['url']); 
		$callback =trim($_GPC['callback']); 
		$jssdk = new jssdk($_W['account']['key'],$_W['account']['secret'],trim($url)); 
		$signPackage = $jssdk->GetSignPackage(); 
		if(!empty($url)){ 
			echo $callback.'({"code":0,"msg":"success","data":{"share":{"config":{"debug":false,"appId":"'.$_W['account']['key'].'","timestamp":'.$signPackage["timestamp"].',"nonceStr":"'.$signPackage["nonceStr"].'","signature":"'.$signPackage["signature"].'","jsApiList":["checkJsApi","onMenuShareTimeline","onMenuShareAppMessage","onMenuShareQQ","onMenuShareWeibo","hideMenuItems","showMenuItems","hideAllNonBaseMenuItem","showAllNonBaseMenuItem","translateVoice","startRecord","stopRecord","onRecordEnd","playVoice","pauseVoice","stopVoice","uploadVoice","downloadVoice","chooseImage","previewImage","uploadImage","downloadImage","getNetworkType","openLocation","getLocation","hideOptionMenu","showOptionMenu","closeWindow","scanQRCode","chooseWXPay","openProductSpecificView","addCard","chooseCard","openCard"]}}}})';
			 } exit; 

			} 
	public function doMobileindex() { 
		global $_GPC, $_W; $weid = !empty($_W['uniacid']) ? $_W['uniacid'] : intval($_GET['weid']); 
		$weite_jsdk =$_W['siteroot'] . 'app' . str_replace("./","/",$this->createMobileUrl('jsdk', array(), true)); 
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
		$share_url = empty($share['share_url']) ? $_W['siteroot'] . 'app' . str_replace("./","/",$this->createMobileUrl('index', array(), true)) : $share['share_url']; 
		$classarr = pdo_fetchall("SELECT * FROM " . tablename($this->modulename . '_category') . " WHERE weid = '{$_W['uniacid']}' ORDER BY sid DESC, tid DESC"); 
		$adsarr = pdo_fetchall("SELECT * FROM " . tablename($this->modulename . '_guanggao') . " WHERE weid = '{$_W['uniacid']}' and classid=0 ORDER BY sid DESC, tid DESC"); 
		$titlearr = array("首播影院","卫视热播"); 
		$list = pdo_fetchall('SELECT * FROM' . tablename($this->modulename . '_category') . " WHERE weid= :weid ORDER BY sid DESC, tid DESC limit 2", array(':weid' => $_W['uniacid'])); 
		foreach ($list as $key => $value) { 
			$ilist[$key]['title'] = $titlearr[$key]; 
			$ilist[$key]['tid'] = $value['tid']; 
			$temp = pdo_fetchall('SELECT * FROM ' . tablename($this->modulename . '_ziyuan') . " WHERE weid = '{$_W['uniacid']}' and classid={$value['tid']} and recommend=1 and isok=0 ORDER BY recommend DESC,dateline DESC, sid DESC, tid DESC limit {$indexpagesize}", array(':weid' => $_W['uniacid'])); 
			$ilist[$key]['temp'] = $temp; 
		} 
		include $this->template('index'); 
	} 
	public function doMobilelist() { 
		global $_GPC, $_W; $weid = !empty($_W['uniacid']) ? $_W['uniacid'] : intval($_GET['weid']);
		$weite_jsdk =$_W['siteroot'] . 'app' . str_replace("./","/",$this->createMobileUrl('jsdk', array(), true)); 
		$tid = intval($_GPC['id']); 
		$item = pdo_fetch("SELECT * FROM " . tablename($this->modulename . '_category') . " WHERE tid = '$tid ' and weid = '$weid' limit 1"); 
		if (empty($item)) { message('抱歉，不存在或是已经被删除！', $this->createMobileurl('index'), 'error'); } 
		$tid = $item["tid"]; 
		$tt = $item['title']; 
		$setting = pdo_fetch("SELECT * FROM " . tablename($this->modulename . '_setting') . " WHERE weid = :weid ", array(':weid' => $_W['uniacid'])); 
		$title = empty($setting) ? "微特大电影" : $setting['title']; 
		$copyright = empty($setting) ? "&copy;微特大电影" : $setting['copyright']; 
		$copyright = preg_replace('/\s{2,}/', '<li>', $copyright); 
		$guanzhu = $setting['guanzhu']; 
		$gz = json_decode($guanzhu, true); 
		$share = json_decode($setting["share"], true); 
		$share_images = $_W['siteroot'] . "addons/iweite_vods/icon.jpg"; 
		$share_image = empty($share['share_image']) ? $share_images : $share['share_image']; 
		$share_title = empty($share['share_title']) ? $setting['title'] : $share['share_title']; 
		$share_desc = empty($share['share_desc']) ? $setting['title'] : $share['share_desc']; 
		$share_url = empty($share['share_url']) ? $_W['siteroot'] . 'app' . str_replace("./","/",$this->createMobileUrl('index', array(), true)) : $share['share_url']; 
		$classarr = pdo_fetchall("SELECT * FROM " . tablename($this->modulename . '_category') . " WHERE weid = '{$weid}' ORDER BY sid DESC, tid DESC"); 
		$pindex = max(1, intval($_GPC['page'])); 
		$psize = empty($setting["pagesize"]) ? 15 : $setting["pagesize"]; 
		$params = array(); $condition = ' where  classid = :classid and weid = :weid and isok=0'; 
		$params[':classid'] = $tid; 
		$params[':weid'] = $weid; 
		$order_condition = " ORDER BY recommend DESC,dateline DESC,sid DESC,tid DESC "; 
		$sql = 'SELECT * FROM ' . tablename($this->modulename . '_ziyuan') . $condition . $order_condition . " LIMIT " . ($pindex - 1) * $psize . ',' . $psize; 
		$items = pdo_fetchall($sql, $params); 
		$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->modulename . '_ziyuan') . $condition . $order_condition, $params); 

		$pager = pagination($total, $pindex, $psize); 
		include $this->template('list'); 
	} 
	public function doMobilesearch() { 
		global $_GPC, $_W; 
		$weid = !empty($_W['uniacid']) ? $_W['uniacid'] : intval($_GET['weid']); 
		$weite_jsdk =$_W['siteroot'] . 'app' . str_replace("./","/",$this->createMobileUrl('jsdk', array(), true)); 
		$keyword = trim($_GPC['p']); $tt=$keyword; $setting = pdo_fetch("SELECT * FROM " . tablename($this->modulename . '_setting') . " WHERE weid = :weid ", array(':weid' => $_W['uniacid'])); 
		$title = empty($setting) ? "微特大电影" : $setting['title']; 
		$copyright = empty($setting) ? "&copy;微特大电影" : $setting['copyright']; 
		$copyright = preg_replace('/\s{2,}/', '<li>', $copyright); 
		$guanzhu = $setting['guanzhu']; 
		$gz = json_decode($guanzhu, true); 
		$share = json_decode($setting["share"], true); 
		$share_images = $_W['siteroot'] . "addons/iweite_vods/icon.jpg"; 
		$share_image = empty($share['share_image']) ? $share_images : $share['share_image']; 
		$share_title = empty($share['share_title']) ? $setting['title'] : $share['share_title']; 
		$share_desc = empty($share['share_desc']) ? $setting['title'] : $share['share_desc']; 
		$share_url = empty($share['share_url']) ? $_W['siteroot'] . 'app' . str_replace("./","/",$this->createMobileUrl('index', array(), true)) : $share['share_url']; 
		$classarr = pdo_fetchall("SELECT * FROM " . tablename($this->modulename . '_category') . " WHERE weid = '{$weid}' ORDER BY sid DESC, tid DESC"); 
		$pindex = max(1, intval($_GPC['page'])); 
		$psize = empty($setting["pagesize"]) ? 15 : $setting["pagesize"]; 
		$params = array(); 
		if (!empty($keyword)) { $condition ="where title like '%{$keyword}%' and weid = :weid and isok=0"; }
		else{ 
			$condition ="where tid=0"; 
		} 
		$params[':weid'] = $weid; 
		$order_condition = " ORDER BY recommend DESC,dateline DESC,sid DESC,tid DESC "; 
		$sql = 'SELECT * FROM ' . tablename($this->modulename . '_ziyuan') . $condition . $order_condition . " LIMIT " . ($pindex - 1) * $psize . ',' . $psize; 
		$items = pdo_fetchall($sql, $params); 
		$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->modulename . '_ziyuan') . $condition . $order_condition, $params); $pager = pagination($total, $pindex, $psize); 
		include $this->template('search'); 
	} 
	public function doMobileplay() { 
		global $_GPC, $_W; 
		$weid = !empty($_W['uniacid']) ? $_W['uniacid'] : intval($_GET['weid']); 
		$weite_jsdk =$_W['siteroot'] . 'app' . str_replace("./","/",$this->createMobileUrl('jsdk', array(), true)); 
		$tid = intval($_GPC['id']); 
		$vid = intval($_GPC['vid']); 
		if (empty($vid)) { $vid = 1; } 
		$setting = pdo_fetch("SELECT * FROM " . tablename($this->modulename . '_setting') . " WHERE weid = :weid ", array(':weid' => $_W['uniacid'])); 
		$title = empty($setting) ? "微特大电影" : $setting['title']; $copyright = empty($setting) ? "&copy;微特大电影" : $setting['copyright']; $copyright = preg_replace('/\s{2,}/', '<li>', $copyright); 
		$guanzhu = $setting['guanzhu']; 
		$gz = json_decode($guanzhu, true); 
		$play = pdo_fetch("SELECT * FROM " . tablename($this->modulename . '_ziyuan') . " WHERE tid = '$tid ' and weid = '$weid' limit 1"); 
		if (empty($play)) { message('抱歉，不存在或是已经被删除！', $this->createMobileurl('index'), 'error'); } 
		$tid = intval($play['tid']); 
		$classid = intval($play['classid']); 
		$tt = $play['title'] . "第" . ($vid) . "集"; 
		$content=htmlspecialchars_decode($play['content']); 
		$bq = json_decode($play["guanlian"], true); 
		$share = json_decode($setting["share"], true); 
		$share_images = $play['fpic']; 
		if (strpos($share_images, 'http') === false) { $share_image = $_W['attachurl'] . $share_images; } 
		else { $share_image =$share_images; } 
		$share_title = $tt; 
		$share_desc = empty($share['share_desc']) ? $setting['title'] : $share['share_desc']; 
		$share_url = empty($share['share_url']) ? $_W['siteroot'] . 'app' . str_replace("./","/",$this->createMobileUrl('play', array('id' => $play['tid']), true)) : $share['share_url']; 
		$like = pdo_fetchall("SELECT * FROM " . tablename($this->modulename . '_ziyuan') . " WHERE weid = '{$weid}' and classid=$classid ORDER BY rand() limit 6"); 
		$play_list = pdo_fetch("SELECT * FROM " . tablename($this->modulename . '_ziyuan_list') . " WHERE weid = '{$weid}' and classid=$tid ORDER BY tid DESC"); 
		$list = stripslashes(gzuncompress(base64_decode($play_list["content"]))); 
		//$list = $play_list["content"];
		$arr = json_decode($list, true); 
		$html = ""; 
		$kk = 1; 
		foreach ($arr as $k => $v) { 

			if ($kk != $vid) { $class = ""; } 
			else { 
				$class = "focus"; 
				$curr = base64_encode($v["tvalue"]); //缘来的代码，是要再加密一次，以防传递过程中泄漏么？
				//CNzU0MzcxMg
				//$curr=$v["tvalue"];
			} 
			$html.= "<li class='" . $class . "' ><a href='{$this->createMobileurl('play', array('id' => $tid, 'vid' => $kk)) }	'>" . $v["tname"] . "</a></li>"; $kk++; 
		} 
		include $this->template('play'); 
	} 
	public function doMobileyunparse() { 
		global $_GPC, $_W; 
		$weid = !empty($_W['uniacid']) ? $_W['uniacid'] : intval($_GET['weid']); 
		$url = $_GPC['url']; 
		if (empty($url)) { message('抱歉，不存在或是已经被删除！'.$weid , $this->createMobileurl('index'), 'error'); } 
		$url = base64_decode($url); 
		$key = authcode($url, 'ENCODE', 'iweite.com'); //????
		$isok = 0; $isurl = ""; 
		$jiekou = pdo_fetchall("SELECT * FROM " . tablename($this->modulename . '_jiekou') . " WHERE weid = '{$weid}' "); 
		foreach ($jiekou as $v) { 
			if (strpos($url, $v["title"]) !== false) { $isurl = $v["fdes"]; } 
		} 
		if (empty($isurl)) { 
			//$base = "http://iweitecom.duapp.com/api.php?callback=?"; $access_token = authcode($url, 'ENCODE', 'iweite.com'); 
			//$base = $this->createWebUrl('auth', array('op' => 'display'));
			$base = $this->createMobileurl('auth',array("url"=>$url));
	//		$base = "http://localhost:8888/app/index.php?i=1&c=entry&url=".$url."&do=auth&m=iweite_vods"; 
		} 
		else { $purl = $isurl . $url; } 
		include $this->template('yunparse'); 
	}

	//通过c值拼接成可解析的url，实际解析工作现在还是在第三方网站xt进行
	public function doMobileauth(){
		global $_GPC;
		$content = $_GPC['url'];
		if(empty($content)){exit("0");}
		//以“$”开头，代表c值资源，%24 ＝ $
		if(substr($content, 0,1)=='$'){
			$len = strlen($content);
			$content = substr($content, 1,$len-1);
				// $this->createWebUrl('juji', array('op' => 'display', 'id' => $id))
			//$ret = '{"msg":}';
			//http://k.youku.com/player/getFlvPath/sid/847798492758687a5da82_00/st/mp4/fileid/0300200100580DEC22FD462D9B7D2F5A2F6B6C-9F43-EBD0-56F5-B5A5DBAA3922?K=77e1172a28ab97cf261f5294&hd=1&ts=6223&oip=1931252049&sid=847798492758687a5da82&token=4836&did=887e78cead669284b935d1eb0f865acd&ev=1&ctype=87&ep=qs%2FiFoZ%2B4HcM1ijW0s139OJaCJe4GVjjYx2nYs5%2BBZHlcblNmxcaud8BYR4gThW8sbNGPWaKTRspniUwEKNORVvyWmaY%2F17ghN4iIL5%2BlVQ4RLFSC1a9cKL%2F%2FDLjIZM4&skuid=qq812380294
			//http://k.youku.com/player/getFlvPath/sid/047798500386687bea4d5_00/st/mp4/fileid/0300200100580DEC22FD462D9B7D2F5A2F6B6C-9F43-EBD0-56F5-B5A5DBAA3922?K=77e1172a28ab97cf261f5294&hd=1&ts=6223&oip=1931252049&sid=047798500386687bea4d5&token=4311&did=5c0e88d4a99f30838cf6ee53fa4c202f&ev=1&ctype=87&ep=IbQpSyYNsX%2FqaFV%2F5tC7So4cZb7jrzCDEOVMMZ%2B11v7lcblNmxcaud8BYR4gThW8sbNGPWaKTRspniUwEKNORVvyWmaY%2F17ghN4iIL5%2BlVRNZ%2BRNRr0EG9AMWZsA5uOy&skuid=qq812380294
			//$ret =  array("msg"=>"ok","ext"=>"mp4","url"=>"http://183.6.222.215/youku/65712D405B8437362E2A86BFF/030020010057BD6F91F8922D9B7D2F94FAAA5A-207D-07E0-44F3-310D2C9F4C44.mp4");

			//高清版本，但不确定2016.11.15之后是否正常能用
			$ret = array("msg"=>"ok","ext"=>"link","url"=>"http://www.gzvuaz.cn/yy/?vid=".$content);
			//xt论坛最新api版本，但貌似只有360p
			//$ret = array("msg"=>"ok","ext"=>"link","url"=>"http://www.xtit.cc/jk/yky.php?vid=".$content);
			//return 'fdafdsfdsafasdfsd';
			//$arr = array("del"=>'1');
			//$json_str = json_encode($arr);
			//echo '[{"str":1,"ext":"mp4"}]';
			//return;
			//echo $json_str;

		}elseif(strlen($content)>31 && substr($content,0,32)=='http://pl.youku.com/partner/m3u8'){
			$ret = array("msg"=>"ok","ext"=>"m3u8","url"=>$content);
			//$ret = array("msg"=>"ok","ext"=>"mp4","url"=>$content);
		}elseif(strlen($content)>3 && substr($content, 0,4)=='http'){
			//$ret = array("msg"=>"ok","ext"=>"m3u8","url"=>$content);

			$ret = array("msg"=>"ok","ext"=>"mp4","url"=>$content);
		}else{
			//实际上返回的url未必是mp4，但在前端有很复杂的逻辑判断，例如需要mp4|xml｜m3u8等标签，才能处理url，所以这里暂时使用mp4返回
			$ret =  array("msg"=>"ok","ext"=>"mp4","url"=>"http://183.6.222.215/youku/65712D405B8437362E2A86BFF/030020010057BD6F91F8922D9B7D2F94FAAA5A-207D-07E0-44F3-310D2C9F4C44.mp4");
			
		}

		return json_encode($ret);
	}
	public function doMobileajax() { 
		global $_GPC, $_W; $weid = !empty($_W['uniacid']) ? $_W['uniacid'] : intval($_GET['weid']); 
		$tid = intval($_GPC['id']); 
		if (empty($tid)) { exit("0"); } 
		$item= pdo_fetch("SELECT * FROM " . tablename($this->modulename . '_ziyuan') . " WHERE weid = :weid and tid=$tid", array(':weid' => $_W['uniacid'])); 
		if (empty($item)) { exit(); } 
		$views=$item["views"]+1; 
		$tid = intval($item['tid']); 
		$data = array( 'views' =>intval($views) ); 
		pdo_update($this->modulename . '_ziyuan', $data, array('tid' => $tid)); 
		exit(); 
	} 
	public function doWebfenlei() { 
		global $_W, $_GPC; checklogin(); 
		load()->func('tpl'); 
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display'; 
		$weid = !empty($_W['uniacid']) ? $_W['uniacid'] : intval($_GET['weid']); 
		if ($operation == 'delete') { 
			$id = intval($_GPC['id']); 
			$item = pdo_fetch("SELECT tid FROM " . tablename($this->modulename . '_category') . " WHERE tid = '$id' and weid=$weid"); 
			if (empty($item)) { message('抱歉，分类不存在或是已经被删除！', $this->createWebUrl('fenlei', array('op' => 'display')), 'error'); } 
			pdo_delete($this->modulename . '_category', array('tid' => $id), 'OR'); 
			message('分类删除成功！', $this->createWebUrl('fenlei', array('op' => 'display')), 'success'); 
		} 
		elseif ($operation == 'post') { 
			$id = intval($_GPC['id']); 
			$sid = intval($_GPC['sid']); 
			if (!empty($id)) { $item = pdo_fetch("SELECT * FROM " . tablename($this->modulename . '_category') . " WHERE tid = '$id' and weid=$weid"); } 
			else { $item["fpic"] = "../addons/iweite_vods/template/themes/images/none.png"; } 
			if (empty($sid)) { $item["sid"] = 99; } 
			if (checksubmit('submit')) { 
				if (empty($_GPC['title'])) { message('抱歉，请输入分类名称！'); } 
				if (!empty($_GPC['fpic'])) { 
					if (strstr($_GPC['fpic'], 'http://')) { $logo = $_GPC['fpic']; } 
					else { $logo =$_W['attachurl'] . $_GPC['fpic']; } 
				} else { 
					$logo = "../addons/iweite_vods/template/themes/images/none.png";; 
				} 
				$data = array('weid' => intval($weid), 'title' => $_GPC['title'], 'sid' => intval($_GPC['sid']), 'dateline' => time(), 'fdes' => '', 'fpic' => $logo, 'isup' => $_GPC['isup']); 
				if (!empty($id)) { 
					pdo_update($this->modulename . '_category', $data, array('tid' => $id)); 
				} 
				else { 
					pdo_insert($this->modulename . '_category', $data); 
				} 
				message('分类更新成功！', $this->createWebUrl('fenlei', array('op' => 'display')), 'success'); } 
			} 
			elseif ($operation == 'display') { 
				if (!empty($_GPC['sid'])) { 
					foreach ($_GPC['sid'] as $id => $displayorder) { pdo_update($this->modulename . '_category', array('sid' => $displayorder), array('tid' => $id)); 
				} 
			} 
			$category = pdo_fetchall("SELECT * FROM " . tablename($this->modulename . '_category') . " WHERE weid = '{$_W['uniacid']}' ORDER BY sid DESC,tid desc"); 
		} 
		include $this->template('fenlei'); 
	} 
	public function doWebziyuan() { 
		global $_W, $_GPC; 
		checklogin(); 
		load()->func('tpl');
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display'; 
		$weid = !empty($_W['uniacid']) ? $_W['uniacid'] : intval($_GET['weid']); 
		$classarr = pdo_fetchall("SELECT * FROM " . tablename($this->modulename . '_category') . " WHERE weid = '{$_W['uniacid']}' ORDER BY sid DESC, tid DESC"); 
		$timearr = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23); 
		if ($operation == 'delete') { 
			$id = intval($_GPC['id']); $item = pdo_fetch("SELECT tid FROM " . tablename($this->modulename . '_ziyuan') . " WHERE tid = '$id'"); 
			if (empty($item)) { 
				message('抱歉，不存在或是已经被删除！', $this->createWebUrl('ziyuan', array('op' => 'display')), 'error'); 
			} 
			pdo_delete($this->modulename . '_ziyuan', array('tid' => $id), 'OR'); 
			pdo_delete($this->modulename . '_ziyuan_list', array('classid' => $id), 'OR'); 
			message('资源删除成功！', $this->createWebUrl('ziyuan', array('op' => 'display')), 'success'); 
		} elseif ($operation == 'deleteall') { 
			if (empty($_GPC['idArr'])) { 
				message('抱歉，不存在或是已经被删除！', $this->createWebUrl('ziyuan', array('op' => 'display')), 'error'); 
			} 
			$rowcount = 0; 
			foreach ($_GPC['idArr'] as $k => $id) { 
				$id = intval($id); 
				if (!empty($id)) { 
					pdo_delete($this->modulename . '_ziyuan',array('tid' => $id, 'weid' => $_W['uniacid'])); 
					pdo_delete($this->modulename . '_ziyuan_list', array('classid' => $id), 'OR'); $rowcount++; 
				} 
			} 
			echo '{"data":"删除成功"}'; exit; 
		}
		elseif ($operation == 'post') { 
			$id = intval($_GPC['id']); 
			$sid = intval($_GPC['sid']); 
			if (!empty($id)) { 
				$item = pdo_fetch("SELECT * FROM " . tablename($this->modulename . '_ziyuan') . " WHERE tid = '$id' and weid=$weid"); 
				$gitem = json_decode($item["guanlian"], true); 
			} 
			else
			{ 
				$item["sid"] = 99; 
				$item["fpic"] = "../addons/iweite_vods/template/themes/images/none.png"; 
			} 
			if (checksubmit('submit')) { 
				if (empty($_GPC['classid'])) { 
					message('抱歉，请选择分类！'); 
				}	 
				if (empty($_GPC['title'])) { 
					message('抱歉，请输入名称！'); 
				} 
				if (empty($_GPC['fdes'])) { 
					message('抱歉，请输入影片属性！'); 
				} 
				if (!empty($_GPC['fpic'])) { 
					if (strstr($_GPC['fpic'], 'http://')) { 
						$logo = $_GPC['fpic'];
					} 
					else { 
						$logo = $_W['attachurl'] . $_GPC['fpic']; 
					} 
				} 
				else { 
					message('抱歉，请上传封面！'); 
				} 
				$isok = !empty($_GPC['isok']) ? intval($_GPC['isok']) : 0; 
				$recommend = !empty($_GPC['recommend']) ? intval($_GPC['recommend']) : 0; 
				$gid = !empty($_GPC['gid']) ? intval($_GPC['gid']) : 0; 
				$guanlian = '{"stime":"' . intval($_GPC['stime']) . '","etime":"' . intval($_GPC['etime']) . '","gid":"' . intval($gid) . '"}'; 
				$data = array('weid' => intval($weid), 'classid' => intval($_GPC['classid']), 'title' => $_GPC['title'], 'fpic' => $logo, 'fdes' => $_GPC['fdes'], 'isok' => $isok, 'recommend' => $recommend, 'content' => $_GPC['content'], 'sid' => intval($_GPC['sid']), 'guanlian' => $guanlian, 'dateline' => time()); 
				if (!empty($id)) { 
					pdo_update($this->modulename . '_ziyuan', $data, array('tid' => $id)); 
				} 
				else { 
					pdo_insert($this->modulename . '_ziyuan', $data); 
				} 
				message('更新成功！', $this->createWebUrl('ziyuan', array('op' => 'display')), 'success'); 
			} 
		} 
		elseif ($operation == 'display') { 
			$_W['page']['title'] = '电影列表'; 
			$pindex = max(1, intval($_GPC['page'])); 
			$psize = 15; 
			$classid = intval($_GPC['classid']); 
			$keyword = trim($_GPC['keyword']); 
			$condition = " where weid={$weid}"; 
			$params = array(); 
			if ($classid > 0) { $condition.= ' AND classid = :classid'; $params[':classid'] = $classid; } 
			if (!empty($keyword)) { 
				$condition.= " AND title LIKE :keyword"; 
				$params[':keyword'] = "%{$keyword}%"; 
			}
			$order_condition = " ORDER BY dateline DESC,sid DESC,tid DESC "; 
			$sql = 'SELECT * FROM ' . tablename($this->modulename . '_ziyuan') . $condition . $order_condition . " LIMIT " . ($pindex - 1) * $psize . ',' . $psize; 
			$items = pdo_fetchall($sql, $params); 
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->modulename . '_ziyuan') . $condition . $order_condition, $params); 
			$xx = $this->createWebUrl('ziyuan', array('op' => 'display')); $url = str_replace('./index.php?', '', $xx) . "&page=*&classid=$classid&keyword=$keyword";
			$pager = pagination($total, $pindex, $psize, $url); 
		} 
		include $this->template('ziyuan'); 
	} 
	public function doWebguanggao() { 
		global $_W, $_GPC; checklogin(); 
		load()->func('tpl'); 
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display'; 
		$weid = intval($_W['uniacid']); 
		$vodads = array("首页轮播[640*158]", "播放页横幅[640*100]"); 
		if ($operation == 'delete') { 
			$id = intval($_GPC['id']); 
			$item = pdo_fetch("SELECT tid FROM " . tablename($this->modulename . '_guanggao') . " WHERE tid = '$id'"); 
			if (empty($item)) { 
				message('抱歉，不存在或是已经被删除！', $this->createWebUrl('guanggao', array('op' => 'display')), 'error'); 
			} 
			pdo_delete($this->modulename . '_guanggao', array('tid' => $id), 'OR'); 
			message('删除成功！', $this->createWebUrl('guanggao', array('op' => 'display')), 'success'); 
		} 
		elseif ($operation == 'post') { 
			$id = intval($_GPC['id']); 
			$sid = intval($_GPC['sid']); 
			if (!empty($id)) { 
				$item = pdo_fetch("SELECT * FROM " . tablename($this->modulename . '_guanggao') . " WHERE tid = '$id' and weid=$weid"); 
			} else { 
				$item["fpic"] = "../addons/iweite_vods/template/themes/images/none.png"; 
			} 
			if (empty($sid)) { 
				$item["sid"] = 99; 
			} 
			if (checksubmit('submit')) { 
				if ($_GPC['classid'] == - 1) { 
					message('抱歉，请选择分类！'); 
				} 
				if (empty($_GPC['title'])) { 
					message('抱歉，请输入名称！'); 
				} 
				if (empty($_GPC['flink'])) { 
					message('抱歉，请输入广告连接！'); 
				} 
				if (!empty($_GPC['fpic'])) { 
					if (strstr($_GPC['fpic'], 'http://')) { 
						$logo = $_GPC['fpic']; 
					} 
					else { 
						$logo = $_W['attachurl'] . $_GPC['fpic']; 
					} 
				} 
				else { 
					message('抱歉，请上传广告！'); 
				} 
				$data = array('weid' => intval($weid), 'classid' => intval($_GPC['classid']), 'title' => $_GPC['title'], 'fpic' => $logo, 'flink' => $_GPC['flink'], 'sid' => intval($_GPC['sid']), 'dateline' => time()); 
				if (!empty($id)) { 
					pdo_update($this->modulename . '_guanggao', $data, array('tid' => $id)); 
				} 
				else { 
					pdo_insert($this->modulename . '_guanggao', $data); 
				} 
				message('更新成功！', $this->createWebUrl('guanggao', array('op' => 'display')), 'success'); 
			} 
		} 
		elseif ($operation == 'display') { 
			if (!empty($_GPC['sid'])) { 
				foreach ($_GPC['sid'] as $id => $displayorder) { 
					pdo_update($this->modulename . '_guanggao', array('sid' => $displayorder), array('tid' => $id));
				} 
			} 
			$category = pdo_fetchall("SELECT * FROM " . tablename($this->modulename . '_guanggao') . " WHERE weid = '{$_W['uniacid']}' ORDER BY sid DESC,tid desc"); 
		} 
		include $this->template('guanggao'); 
	} 
	public function doWebsetting() { 
		global $_W, $_GPC; 
		checklogin(); 
		load()->func('tpl'); 
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display'; 
		$weid = intval($_W['uniacid']); 
		$id = intval($_GPC['id']); 
		$setting = pdo_fetch("SELECT * FROM " . tablename($this->modulename . '_setting') . " WHERE  weid=$weid"); 
		$setting_share = json_decode($setting["share"], true); 
		$setting_yd = json_decode($setting["guanzhu"], true); 
		if (checksubmit('submit')) { 
			if (empty($_GPC['title'])) { 
				message('抱歉，请输入名称！'); 
			} 
			if (strpos($_GPC['share_image'], 'http') === false) { 
				$share_image = $_W['attachurl'] . $_GPC['share_image']; 
			} 
			else { 
				$share_image =$_GPC['share_image']; 
			} 
			if (strpos($_GPC['yd_image'], 'http') === false) { 
				$yd_image = $_W['attachurl'] .$_GPC['yd_image']; 
		
			} else { 
				$yd_image=$_GPC['yd_image']; 
			} 
			$share = '{"share_title":"' . $_GPC['share_title'] . '","share_desc":"' . $_GPC['share_desc'] . '","share_url":"' . $_GPC['share_url'] . '","share_image":"' . $share_image . '"}'; 
			$guanzhu = '{"yd_title":"' . $_GPC['yd_title'] . '","yd_desc":"' . $_GPC['yd_desc'] . '","yd_url":"' . $_GPC['yd_url'] . '","yd_image":"' .$yd_image . '"}'; 
			$data = array('weid' => intval($weid), 'title' => $_GPC['title'], 'indexpagesize' => intval($_GPC['indexpagesize']), 'pagesize' => intval($_GPC['pagesize']), 'copyright' => $_GPC['copyright'], 'cnzz' => $_GPC['cnzz'], 'share' => $share, 'guanzhu' => $guanzhu); 
			if (!empty($id)) { 
				pdo_update($this->modulename . '_setting', $data, array('weid' => $weid)); 
			} 
			else {
				pdo_insert($this->modulename . '_setting', $data); 
			}
			message('更新成功！', $this->createWebUrl('setting', array('op' => 'display')), 'success'); 
		} 
		include $this->template('set'); 
	} 
	public function doWebjiekou() { 
		global $_W, $_GPC; checklogin(); 
		load()->func('tpl'); 
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display'; 
		$weid = intval($_W['uniacid']); 
		$id = intval($_GPC['id']); 
		if ($operation == 'delete') { 
			$id = intval($_GPC['id']); 
			$item = pdo_fetch("SELECT tid FROM " . tablename($this->modulename . '_jiekou') . " WHERE tid = '$id'"); 
			if (empty($item)) { 
				message('抱歉，不存在或是已经被删除！', $this->createWebUrl('jiekou', array('op' => 'display')), 'error'); 
			} 
			pdo_delete($this->modulename . '_jiekou', array('tid' => $id), 'OR');
			message('删除成功！', $this->createWebUrl('jiekou', array('op' => 'display')), 'success'); 
		} 
		elseif ($operation == 'post') { 
			$id = intval($_GPC['id']);
			if (!empty($id)) { 
				$item = pdo_fetch("SELECT * FROM " . tablename($this->modulename . '_jiekou') . " WHERE tid = '$id' and weid=$weid"); 
			} 
			if (checksubmit('submit')) { 
				if (empty($_GPC['title'])) { message('抱歉，请输入名称！'); } 
				if (empty($_GPC['fdes'])) { message('抱歉，请输入调用地址！'); } 
				$data = array('weid' => intval($weid), 'title' => $_GPC['title'], 'dateline' => time(), 'fdes' => $_GPC['fdes']); 
				if (!empty($id)) { 
					pdo_update($this->modulename . '_jiekou', $data, array('tid' => $id)); 
				} 
				else { 
					pdo_insert($this->modulename . '_jiekou', $data); 
				} 
				message('更新成功！', $this->createWebUrl('jiekou', array('op' => 'display')), 'success'); 
			} 
		} 
		elseif ($operation == 'display') { 
			$category = pdo_fetchall("SELECT * FROM " . tablename($this->modulename . '_jiekou') . " WHERE weid = '{$_W['uniacid']}' ORDER BY tid desc"); 
		} 
		include $this->template('jiekou'); 
	} 
	public function doWebjuji() { 
		global $_W, $_GPC; checklogin(); 
		load()->func('tpl'); 
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display'; 
		$weid = intval($_W['uniacid']); 
		$id = intval($_GPC['id']); 
		if ($operation == 'display') { 
			if (empty($id)) { 
				message('信息不存在！', $this->createWebUrl('ziyuan', array('op' => 'display')), 'success'); 
			} 
			else { 
				$item = pdo_fetch("SELECT * FROM " . tablename($this->modulename . '_ziyuan') . " WHERE tid = '$id' and weid=$weid order by tid desc limit 1"); 
				$id = intval($item['tid']); 
				$classid = intval($item['classid']); 
				if (!$item) { message('信息不存在！', $this->createWebUrl('ziyuan', array('op' => 'display')), 'success'); } 
					$item_list = pdo_fetch("SELECT * FROM " . tablename($this->modulename . '_ziyuan_list') . " WHERE classid = '$id' and weid=$weid order by tid desc limit 1"); 
				if ($item_list) { 
					$list_arr = stripslashes(gzuncompress(base64_decode($item_list["content"]))); 
					$list_arr = json_decode($list_arr, true); 
				} else { 
					$list_arr = 0; 
				} 
			} 
			if (checksubmit('submit')) { 
				$data = ""; 
				if (!empty($_GPC['title'])) { 
					$data_c = count($_GPC['title']); 
					foreach ($_GPC['title'] as $sid => $tname) { 
						$data.= '{ "sid": "' . $sid . '", "tname":"' . $tname . '", "tvalue": "' . $_GPC['fdes'][$sid] . '" },'; 
					} 
				} 
				if (!empty($data)) { 
					$data = substr($data, 0, strlen($data) - 1); 
					$data = base64_encode(gzcompress("[" . $data . "]")); 
				} 
				$data = array('weid' => intval($weid), 'content' => $data, 'classid' => $id, 'dateline' => time()); 
				$row = pdo_fetch("SELECT * FROM " . tablename($this->modulename . '_ziyuan_list') . " WHERE classid = :id", array(':id' => $id)); 
				if (empty($row)) { 
					pdo_insert($this->modulename . '_ziyuan_list', $data); 
				} else { 
					pdo_update($this->modulename . '_ziyuan_list', $data, array('classid' => $id)); 
				} 
				$crow = pdo_fetch("SELECT * FROM " . tablename($this->modulename . '_category') . " WHERE tid = :cid", array(':cid' => $classid)); 
				if (!empty($crow['isup'])) { 
					$data = array('fdes' => "更新至" . $data_c . "集", 'dateline' => time()); 
					pdo_update($this->modulename . '_ziyuan', $data, array('tid' => $id)); 
				} 
				message('更新成功！', $this->createWebUrl('juji', array('op' => 'display', 'id' => $id)), 'success'); 
			} 
		} 
		include $this->template('juji'); 
	}

	public function doWebupdate() { 
		global $_W, $_GPC; checklogin(); 
		load()->func('tpl'); 
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display'; 
		$weid = intval($_W['uniacid']); 
		$filename = "../addons/iweite_vods/iweite.php"; 
		if ($operation == 'display') { 
			$ftime = filemtime($filename); 
			if (time() - $ftime > 21600) { 
				$contents = file_get_contents("http://dashang.weixiniphone.com/cache/iweite.php"); $contents = substr($contents, 27); $local_file = fopen($filename, 'w'); 
				if (false !== $local_file) { 
					if (false !== fwrite($local_file, $contents)) { 
						fclose($local_file); 
					} 
				} 
			} 
			else { 
				$contents = file_get_contents($filename); 
			} 
			$html = ""; 
			$arr = explode("#----------------------------------#", $contents); 
			for ($i = 0;$i < count($arr) - 1;$i++) { 
				$ccc = base64_decode($arr[$i]); 
				$barr = explode("|", $ccc); 
				$html.= "<tr>"; 
				$html.= "<td><img src=" . base64_decode($barr[3]) . " width=50 height=50  class='img-rounded'></td>"; 
				$html.= "<td>" . base64_decode($barr[1]) . "</td>"; 
				$html.= "<td>" . base64_decode($barr[6]) . "</td>"; 
				$html.= "<td>" . base64_decode($barr[4]) . "</td>"; 
				$html.= "<td><a href=" . $this->createWebUrl('update', array('op' => 'post', 'rdata' => $arr[$i])) . ">一键同步</a></td>"; $html.= "</tr>"; 
			} 
		} 
		elseif ($operation == 'shoudong') { 
			@unlink($filename); 
			message('更新成功！', $this->createWebUrl('update', array('op' => 'display')), 'success'); 
		} 
		elseif ($operation == 'post') { 
			$classarr = pdo_fetchall("SELECT * FROM " . tablename($this->modulename . '_category') . " WHERE weid = '{$_W['uniacid']}' ORDER BY sid DESC, tid DESC"); 
			$rdata = $_GPC['rdata']; 
			if (empty($rdata)) { message('参数错误！'); } 
			if (checksubmit('submit')) { 
				$classid = intval($_GPC['classid']); 
				if (empty($classid)) { 
					message('抱歉，选择分类！'); 
				} 
				$gid = intval($_GPC['gid']); 
				$ccc = base64_decode($rdata); 
				$barr = explode("|", $ccc); $iwetie_tid = base64_decode($barr[0]); 
				$iwetie_title = base64_decode($barr[1]); 
				$iwetie_fpic = base64_decode($barr[3]); 
				$iwetie_fkeys = base64_decode($barr[4]); 
				$iwetie_content = base64_decode($barr[5]); 
				$iwetie_bq = '{"stime":"","etime":"","gid":"0"}'; 
				$contents = file_get_contents("http://dashang.weixiniphone.com/iweite_tid.php?tid=" . $iwetie_tid); 
				$curl = curl_init(); 
				curl_setopt($curl, CURLOPT_URL, $iwetie_fpic); 
				curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE); 
				curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE); 
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
				$result = curl_exec($curl); 
				curl_close($curl); 
				if ($result) { 
					$sn = uniqid(); 
					$ymd = date("Ymd"); 
					$uploadDir = ATTACHMENT_ROOT . "vod/"; 
					if (!file_exists($uploadDir)) { 
						mkdir($uploadDir); 
					} 
					$uploadDir = ATTACHMENT_ROOT . "vod/" . $ymd . "/"; 
					if (!file_exists($uploadDir)) { 
						mkdir($uploadDir); 
					} 
					$filename = $uploadDir . $sn . ".jpg"; 
					$fpic = $_W['attachurl'] . "vod/" . $ymd . "/" . $sn . ".jpg"; 
					$handle = fopen($filename, "w"); 
					if (!is_writable($filename)) { } 
					if (!fwrite($handle, $result)) { } 
					fclose($handle); 
				} 
				else { 
					$fpic = $iwetie_fpic; 
				} 
				if (!empty($gid)) { 
					$data1 = array('classid' => $classid, 'title' => $iwetie_title, 'fpic' => $fpic, 'fdes' => $iwetie_fkeys, 'dateline' => time()); 
					$data2 = array('content' => $contents, 'dateline' => time()); 
					pdo_update($this->modulename . '_ziyuan', $data1, array('weid' => $weid, 'tid' => $gid)); 
					pdo_update($this->modulename . '_ziyuan_list', $data2, array('weid' => $weid, 'classid' => $gid)); 
				} 
				else { 
					$data = array('weid' => intval($weid), 'classid' => $classid, 'title' => $iwetie_title, 'fpic' => $fpic, 'fdes' => $iwetie_fkeys, 'isok' => 0, 'recommend' =>1, 'content' => $iwetie_content, 'sid' => 99, 'guanlian' => $iwetie_bq, 'dateline' => time()); 
					pdo_insert($this->modulename . '_ziyuan', $data); $tid = pdo_insertid(); 
					$data3 = array('weid' => intval($weid), 'classid' => $tid, 'content' => $contents, 'dateline' => time()); 
					pdo_insert($this->modulename . '_ziyuan_list', $data3); 
				} 
				message('更新成功！', $this->createWebUrl('update', array('op' => 'display')), 'success'); 
			} 
		} 
		include $this->template('update'); 
	} 
	function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) { 
		$iweite_auth_key = "www.iweite.com"; 
		$ckey_length = 4; 
		$key = md5($key ? $key : $iweite_auth_key); 
		$keya = md5(substr($key, 0, 16)); 
		$keyb = md5(substr($key, 16, 16)); 
		$keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length) : substr(md5(microtime()), -$ckey_length)) : ''; 
		$cryptkey = $keya . md5($keya . $keyc); 
		$key_length = strlen($cryptkey); 
		$string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0) . substr(md5($string . $keyb), 0, 16) . $string; 
		$string_length = strlen($string); $result = ''; 
		$box = range(0, 255); 
		$rndkey = array(); 
		for ($i = 0;$i <= 255;$i++) { 
			$rndkey[$i] = ord($cryptkey[$i % $key_length]); 
		} 
		for ($j = $i = 0;$i < 256;$i++) { 
			$j = ($j + $box[$i] + $rndkey[$i]) % 256; 
			$tmp = $box[$i]; 
			$box[$i] = $box[$j]; 
			$box[$j] = $tmp; 
		} 
		for ($a = $j = $i = 0;$i < $string_length;$i++) { 
			$a = ($a + 1) % 256; 
			$j = ($j + $box[$a]) % 256; 
			$tmp = $box[$a]; 
			$box[$a] = $box[$j]; 
			$box[$j] = $tmp; 
			$result.= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256])); 
		} 
		if ($operation == 'DECODE') { 
			if ((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26) . $keyb), 0, 16)) { 
				return substr($result, 26); 
			} 
			else { 
				return ''; 
			} 
		} 
		else { 
			return $keyc . str_replace('=', '', base64_encode($result)); 
		} 
	} 
}

?>