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
//2016.11.16 remark by sidney
//这个方法是搬过来的，还不完善
//这里假定调用代码在/addons
//而且假定。。。。生成的html也在addons
//明显$segment也没有多个字段，为'entry',for app/index.php用
//其他情况这个方法会出错
function _murl($segment, $params = array(), $noredirect = true, $addhost = false) {
    global $_W;
    //list($controller, $action, $do) = explode('/', $segment);
    $controller = $segment;
    if (!empty($addhost)) {
        $url = $_W['siteroot'] . 'app/';
    } else {
        $url = './';
    }
    $str = '';
    if(uni_is_multi_acid()) {
        $str = "&j={$_W['acid']}";
    }
    //$url .= "index.php?i={$_W['uniacid']}{$str}&";
    $url .= $params['do'] . ".html?i=1";
    // if (!empty($controller)) {
    //     $url .= "c={$controller}&";
    // }
    // if (!empty($action)) {
    //     $url .= "a={$action}&";
    // }
    // if (!empty($do)) {
    //     $url .= "do={$do}&";
    // }
    if (!empty($params)) {
        $queryString = http_build_query($params, '', '&');
        $url .= $queryString;
        if ($noredirect === false) {
            $url .= '&wxref=mp.weixin.qq.com#wechat_redirect';
        }
    }
    return $url;
}
function _murl_default($segment, $params = array(), $noredirect = true, $addhost = false) {
    global $_W;
    //list($controller, $action, $do) = explode('/', $segment);
    $controller = $segment;
    if (!empty($addhost)) {
        $url = $_W['siteroot'] . 'app/';
    } else {
        $url = '../../../app/';
    }
    $str = '';
    if(uni_is_multi_acid()) {
        $str = "&j={$_W['acid']}";
    }
    $url .= "index.php?i={$_W['uniacid']}{$str}&";
    if (!empty($controller)) {
        $url .= "c={$controller}&";
    }
    if (!empty($action)) {
        $url .= "a={$action}&";
    }
    if (!empty($do)) {
        $url .= "do={$do}&";
    }
    if (!empty($params)) {
        $queryString = http_build_query($params, '', '&');
        $url .= $queryString;
        if ($noredirect === false) {
            $url .= '&wxref=mp.weixin.qq.com#wechat_redirect';
        }
    }
    return $url;
}

function _wurl($segment, $params = array()) {
    list($controller, $action, $do) = explode('/', $segment);
    $url = '../app/index.php?';
    if (!empty($controller)) {
        $url .= "c={$controller}&";
    }
    if (!empty($action)) {
        $url .= "a={$action}&";
    }
    if (!empty($do)) {
        $url .= "do={$do}&";
    }
    if (!empty($params)) {
        $queryString = http_build_query($params, '', '&');
        $url .= $queryString;
    }
    return $url;
}
function _MobileUrl($do, $query = array(), $noredirect = true) {
    global $_W;
    $query['do'] = $do;
    //$query['m'] = strtolower($this->modulename);
    $query['m'] = strtolower('iweite_vods');
    if($do=='search' || $do=='play'){
        //search没有静态页面
        return _murl_default('entry',$query,$noredirect);
    }
    else{
        return _murl('entry', $query, $noredirect);    
    }
    
}


function _WebUrl($do, $query = array()) {
    $query['do'] = $do;
    $query['m'] = strtolower($this->modulename);
    return _wurl('site/entry', $query);
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


function _C($class){
    static $static_class = array();

       //判断是否存在类，存在则直接返回
    if (isset($static_class[$class])) {
        return $static_class[$class];
    }


    $name = "Sim_".$class;
        //require_once(COREFRAME_ROOT.'app/'.$m.'/libs/function/'.$filename.'.func.php');
    require_once(IWEITE_ROOT."/iweite_vods/language/".$class.".class.php");
    
    //$static_class[$class] = isset($param) ? new $name($param) : new $name();
    $static_class[$class] = new $name();
    return $static_class[$class];


}

function _T($m,$template){
    $a=array();
    //*** Wuzhicms cache func *********************
    $tpl_file  = 'template/' .$m . "/" .$template . '.html';
    // if(file_exists(SITE_ROOT.$tpl_file)){
    //     exit('Template does not exists:' . SITE_ROOT. $tpl_file);
    // }
    if (!is_writable(CACHE_ROOT . 'templates/')) {
        exit(CACHE_ROOT . 'templates/ 目录不可写');
    }
    // $cache_path = CACHE_ROOT . 'templates/' . $style . '/' . $m . '/';
    // if (!is_dir($cache_path)) {
    //     mkdir($cache_path, 0777, true);
    // } elseif (!is_writable($cache_path)) {
    //     exit($cache_path . ' 目录不可写');
    // }
    $cache_file = CACHE_ROOT . 'templates/' . $m . '/' . $template.'.php';    
    

    $data = file_get_contents(SITE_ROOT.$tpl_file);

    $data = _template_parse($data);
    //default common func have problem, no time to anaylise, remember
    //from web/common/template.func.php //or app/common/template.func.php
    //these func have IN_IA validation, so use simple parse func as 
    //_template_parse();

    $templatelen = @file_put_contents($cache_file, $data);

    //*** We7 cache func *******************
    //$cache_file = CACHE_ROOT . 'templates/' . $style . '/' . $m . '/' . $template . '.php';
    //if (!file_exists($cache_file)) {
        //$tpl_file = 'templates/' . $style . '/' . $m . '/' . $template . '.html';
        // if (file_exists(COREFRAME_ROOT . $tpl_file)) {
        //     exit('Please update template cache!');
        // } elseif ($mb) {
        //     $cache_file = CACHE_ROOT . 'templates/' . $style . '/' . $m . '/' . $tmp . '.php';
        //     if (!file_exists($cache_file)) {
        //         $tpl_file = 'templates/' . $style . '/' . $m . '/' . $tmp . '.html';
        //         if (file_exists(COREFRAME_ROOT . $tpl_file)) {
        //             exit('Please update template cache!');
        //         } else {
        //             exit('Template does not exists:' . $tpl_file);
        //         }
        //     } elseif (AUTO_CACHE_TPL) {
        //         $c_template = load_class('template');
        //         $c_template->cache_template($m, $tmp, $style);
        //     }
        // } else {
        //     exit('Template does not exists:' . $tpl_file);
        // }
    //} 
    // elseif (AUTO_CACHE_TPL) {
    //     $c_template = load_class('template');
    //     $c_template -> cache_template($m, $template, $style);
    // }

    return $cache_file;
    //from web/common/template.func.php
    //or app/common/template.func.php
    //return template_parse
}

function _template_parse($str, $inmodule = false) {
    $str = preg_replace('/<!--{(.+?)}-->/s', '{$1}', $str);
    $str = preg_replace('/{template\s+(.+?)}/', '<?php (!empty($this) && $this instanceof WeModuleSite || '.intval($inmodule).') ? (include $this->template($1, TEMPLATE_INCLUDEPATH)) : (include template($1, TEMPLATE_INCLUDEPATH));?>', $str);
    $str = preg_replace('/{php\s+(.+?)}/', '<?php $1?>', $str);
    
    $str = preg_replace('/{if\s+(.+?)}/', '<?php if($1) { ?>', $str);
    $str = preg_replace('/{else}/', '<?php } else { ?>', $str);
    $str = preg_replace('/{else ?if\s+(.+?)}/', '<?php } else if($1) { ?>', $str);
    $str = preg_replace('/{\/if}/', '<?php } ?>', $str);
    $str = preg_replace('/{loop\s+(\S+)\s+(\S+)}/', '<?php if(is_array($1)) { foreach($1 as $2) { ?>', $str);
    $str = preg_replace('/{loop\s+(\S+)\s+(\S+)\s+(\S+)}/', '<?php if(is_array($1)) { foreach($1 as $2 => $3) { ?>', $str);
    $str = preg_replace('/{\/loop}/', '<?php } } ?>', $str);
    $str = preg_replace('/{(\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)}/', '<?php echo $1;?>', $str);
    $str = preg_replace('/{(\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff\[\]\'\"\$]*)}/', '<?php echo $1;?>', $str);
    $str = preg_replace('/{url\s+(\S+)}/', '<?php echo url($1);?>', $str);
    $str = preg_replace('/{url\s+(\S+)\s+(array\(.+?\))}/', '<?php echo url($1, $2);?>', $str);
    $str = preg_replace_callback('/<\?php([^\?]+)\?>/s', "template_addquote", $str);
    $str = preg_replace('/{([A-Z_\x7f-\xff][A-Z0-9_\x7f-\xff]*)}/s', '<?php echo $1;?>', $str);
    $str = str_replace('{##', '{', $str);
    $str = str_replace('##}', '}', $str);
    /*$str = "<?php defined('IN_IA') or exit('Access Denied');?>" . $str;*/
    return $str;
}
function w_template_parse($template){
    if (version_compare(PHP_VERSION, '5.5.0', '<')) {
        $template = preg_replace("/\{block=([0-9]+)\}/ie", "self::block('\\1')", $template);
        $template = preg_replace("/\{hook:(\w+?)(\s+(.+?))?\}/ie", "self::hooktags('\\1', '\\3')", $template);
        $template = preg_replace("/\{wz:(\w+)\s+([^}]+)\}/ie", "self::syntax_parse('$1','$2')", $template);
        $template = preg_replace("/\{\/wz\}/ie", "self::endof_syntax_parse()", $template);
        $template = preg_replace("/\{(\\$[a-zA-Z0-9_\[\]\'\"\$\x7f-\xff]+)\}/es", "self::addquote('<?php echo \\1;?>')", $template);
    } else {
        $template = preg_replace_callback("/\{block=([0-9]+)\}/i", "self::block", $template);
        $template = preg_replace_callback("/\{hook:(\w+?)(\s+(.+?))?\}/i", "self::hooktags", $template);
        $template = preg_replace_callback("/\{wz:(\w+)\s+([^}]+)\}/i", "self::syntax_parse", $template);
        $template = preg_replace_callback("/\{\/wz\}/i", "self::endof_syntax_parse", $template);
        $template = preg_replace("/\{(\\$[a-zA-Z0-9_\[\]\'\"\$\x7f-\xff]+)\}/s", "<?php echo \\1;?>", $template);
    }
    $template = preg_replace("/\{T\s+(.+)\}/", "<?php if(!isset(\$siteconfigs)) \$siteconfigs=get_cache('siteconfigs'); include T(\\1); ?>", $template);
    $template = preg_replace("/\{if\s+(.+?)\}/", "<?php if(\\1) { ?>", $template);
    $template = preg_replace("/\{else\}/", "<?php } else { ?>", $template);
    $template = preg_replace("/\{elseif\s+(.+?)\}/", "<?php } elseif (\\1) { ?>", $template);
    $template = preg_replace("/\{\/if\}/", "<?php } ?>", $template);

    $template = preg_replace("/\{\+\+(.+?)\}/", "<?php ++\\1; ?>", $template);
    $template = preg_replace("/\{\-\-(.+?)\}/", "<?php ++\\1; ?>", $template);
    $template = preg_replace("/\{(.+?)\+\+\}/", "<?php \\1++; ?>", $template);
    $template = preg_replace("/\{(.+?)\-\-\}/", "<?php \\1--; ?>", $template);
    $template = preg_replace("/\{php\s+(.+)\}/", "<?php \\1?>", $template);
    $template = preg_replace("/\{loop\s+(\S+)\s+(\S+)\}/", "<?php \$n=1;if(is_array(\\1)) foreach(\\1 AS \\2) { ?>", $template);
    $template = preg_replace("/\{loop\s+(\S+)\s+(\S+)\s+(\S+)\}/", "<?php \$n=1; if(is_array(\\1)) foreach(\\1 AS \\2 => \\3) { ?>", $template);
    $template = preg_replace("/\{\/loop\}/", "<?php \$n++;}?>", $template);

    $template = preg_replace("/\{([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff:]*\(([^{}]*)\))\}/", "<?php echo \\1;?>", $template);
    $template = preg_replace("/\{\\$([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff:]*\(([^{}]*)\))\}/", "<?php echo \\1;?>", $template);
    $template = preg_replace("/\{(\\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\}/", "<?php echo \\1;?>", $template);
    $template = preg_replace("/\{([A-Z_\x7f-\xff][A-Z0-9_\x7f-\xff]*)\}/s", "<?php echo \\1;?>", $template);

    $template = preg_replace("/\<\?(\s{1})/is", "<?php\\1", $template);
    $template = preg_replace("/\<\?\=(.+?)\?\>/is", "<?php echo \\1;?>", $template);
    /*$template = "<?php defined('IN_WZ') or exit('No direct script access allowed'); ?>" . $template;*/
    return $template;

}
?>