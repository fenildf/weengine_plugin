<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
<meta http-equiv="Cache-Control" content="max-age=10">
<title><?php  echo $tt;?>_<?php  echo $setting['title'];?></title>
<link rel="stylesheet" type="text/css" href="../../<?php echo RES;?>/themes/css/mui.min.css" />
<link rel="stylesheet" type="text/css" href="../../<?php echo RES;?>/themes/css/app.min.css?v=2.01" />
<script type="text/javascript" charset="utf-8" src="../../<?php echo RES;?>/themes/js/zepto.min.js"></script>
<script type="text/javascript" charset="utf-8" src="../../<?php echo RES;?>/themes/js/zepto.picLazyLoad.min.js"></script>
</head>
<body>
<div class="mui-inner-wrap">
<!-- 主页面标题 -->
<header class="mui-bar mui-bar-nav">
  <div class="game-fl diy-nav-bar "> 
<a class="diy-control-item " href="<?php  echo _Mobileurl('index')?>">首页</a>
<?php  if(is_array($classarr)) { foreach($classarr as $item) { ?>
<a class="diy-control-item <?php  if($item['tid']==$tid) { ?> mui-active<?php  } ?>" href="<?php  echo _Mobileurl('list', array('id'=>$item['tid']))?>"><?php  echo $item['title'];?></a>
<?php  } } ?>
</div>
</header>

<div class="mui-content">
<div class="profile_info"> <span class="radius_avatar profile_avatar"><img src="<?php  echo $gz['yd_image'];?>"></span> <strong class="profile_nickname"><?php  echo $gz['yd_title'];?></strong>
  <p class="profile_desc"><?php  echo $gz['yd_desc'];?></p>
  <div class="profile_opr"> <a id="weui_btn" class="weui_btn weui_btn_primary" href="<?php  echo $gz['yd_url'];?>">进入公众号</a> </div>
</div>


<ul class="mui-table-view mui-grid-view mui-grid-9 main-content" style="margin-top:5px">
  <h5 class="mui-content-padded"> <span class="content-title">
   <?php  echo $tt;?></span> </h5>
  <ul class="mui-table-view mui-grid-view mui-grid-9 list" id="list-content">
   			   
		
		<?php  if(is_array($items)) { foreach($items as $row) { ?>	   
		<li class="mui-table-view-cell mui-media mui-col-xs-4"> <a href="<?php  echo _Mobileurl('play', array('id'=>$row['tid']))?>"> <span style="position:absolute; right:0; bottom:40px; height:20px; line-height:20px; background:#FF6600; color:#FFFFFF; display:block; padding:0px 3px;  overflow:hidden; font-size:11px">
     <?php  echo $row['fdes'];?></span><img src="../../<?php echo RES;?>/themes/images/iweite.png"  data-original="<?php  echo $row['fpic'];?>" class="grid-img lazy"/>
            <img class="game-corner" src="../../<?php echo RES;?>/themes/images/14470348816dL25.png" style="display: block">
            <div class="type-title">
        <?php  echo $row['title'];?></div>
      </a> </li>
	  <?php  } } ?>
      </ul>

  <div class="pagination"  style="text-align:center;"> 
	<?php  echo $pager;?>
	</div>
</ul>

<ul class="mui-table-view mui-grid-view mui-grid-9 main-content" style="margin-top:5px; padding-bottom:15px; text-align:center; color:#333; border-top:1px solid #d5d5d5" id=footer>
<li class="mui-table-view-cell" style="font-size:12px; line-height:180%; height:25px; text-align:center"> 
<?php  echo $copyright;?>
</ul>
<script type="text/javascript">
    $(function(){
		$("img.lazy").picLazyLoad({ 
			threshold : 200
		}); 
    });
	
</script>

<!-- {1php echo register_jssdk(false);} --> <!-- 放到其他引入的 js 之前 -->
<script>
	wx.ready(function () {
		var shareData = {
            title: '<?php  echo $share_title;?>',
            desc: '<?php  echo $share_desc;?>',
            link: '<?php  echo $share_url;?>',
            imgUrl: '<?php  echo $share_image;?>'
        };
		
		 wx.hideAllNonBaseMenuItem();
                wx.showMenuItems({
                    menuList: [
                        'menuItem:share:appMessage',
                        'menuItem:share:timeline',
						'menuItem:share:qq',
                        'menuItem:share:QZone'
                    ]
             });
		
		wx.onMenuShareAppMessage(shareData);
		wx.onMenuShareTimeline(shareData);
		wx.onMenuShareQQ(shareData);
		wx.onMenuShareQZone(shareData);
		
	});
	</script>
<div style="display:none">
<?php  if($setting['cnzz']) { ?>
<script src="<?php  echo $setting['cnzz'];?>"></script>
<?php  } ?>
</div>
</body>
</html>