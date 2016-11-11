<?php
class Sim_html
{
	    /**
     * Class constructor
     */
    function __construct() {
    }

    public function index(){

    	
    	$file = IWEITE_ROOT."/iweite_vods/spec/index.html";
    	ob_start();
    	$site = 'baidu.com';
    	//include(IWEITE_ROOT."/iweite_vods/template/mobile/index_shadow.html");
        include(_T('mobile','index_shadow'));
    	//include(IWEITE_ROOT."/iweite_vods/template/mobile/index.html");
        include(_T('mobile','index'));
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
}
?>