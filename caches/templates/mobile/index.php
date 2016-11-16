<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
<meta http-equiv="Cache-Control" content="max-age=10">
<title><?php  echo $setting['title'];?></title>
<meta name="copyright" content="iweite.com" />
<link rel="stylesheet" type="text/css" href="../../<?php echo RES;?>/themes/css/mui.min.css" />
<link rel="stylesheet" type="text/css" href="../../<?php echo RES;?>/themes/css/swiper.min.css" />
<link rel="stylesheet" type="text/css" href="../../<?php echo RES;?>/themes/css/app.min.css?v=2.01" />
<script type="text/javascript" charset="utf-8" src="../../<?php echo RES;?>/themes/js/zepto.min.js"></script>
<script type="text/javascript" charset="utf-8" src="../../<?php echo RES;?>/themes/js/swiper.min.js"></script>
<script type="text/javascript" charset="utf-8" src="../../<?php echo RES;?>/themes/js/zepto.picLazyLoad.min.js"></script>
</head>
<body>

<div class="mui-inner-wrap">
<!-- 主页面标题 -->
<header class="mui-bar mui-bar-nav">
  <div class="game-fl diy-nav-bar "> 
<a class="diy-control-item mui-active" href="<?php  echo _Mobileurl('index');?>">首页</a>
<?php  if(is_array($classarr)) { foreach($classarr as $item) { ?>
<a class="diy-control-item" href="<?php  echo _Mobileurl('list' . $item['tid'])?>"><?php  echo $item['title'];?></a>
<?php  } } ?>

      </div>
</header>

<div class="mui-content">
<section class="diy-content-space-small"></section>
<!--search form-->
<section class="container search diy-content-space-small">
  <form action="" class="form" method="post" data-ui="static" id="search-form">
    <div data-role="input">
      <!-- <input placeholder="输入片名进行搜索"  type="text"  autocomplete="off" autocorrect="off" maxlength="64" name="q" id="search"> -->
      <input placeholder="<?php  echo _l('search_tips')?>"  type="text"  autocomplete="off" autocorrect="off" maxlength="64" name="q" id="search">
      <i class="iconfont icon-search"></i> <i class="iconfont icon-close"></i> </div>
	  
  <button class="ui-btn" data-ui="danger small" type="button" id="search-btn">搜索</button>

  </form>
</section>

<!--slider-->
<div class="swiper-container diy-content-space main-content" id="banner-slider">
  <div class="swiper-wrapper">
        
		<?php  if(is_array($adsarr)) { foreach($adsarr as $item) { ?>
		<div class="swiper-slide"> <a href="<?php  echo $item['flink'];?>"> <img src="<?php  echo $item['fpic'];?>" id="banner-slider-1"> </a> </div>
		<?php  } } ?>
		
       
      </div>
  <div class="swiper-pagination"></div>
</div>


<!-- end slider -->
<ul class="mui-table-view mui-grid-view mui-grid-9 main-content" >
		<?php  if(is_array($ilist)) { foreach($ilist as $row) { ?>
		<h5 class="mui-content-padded"><span class="content-nav-tip tiny-game-tip">精</span> <span class="content-title"><?php  echo $row['title'];?></span> <a class="diy-more" href="<?php  echo _Mobileurl('list' . $row['tid'])?>"> 更多<i class="iconfont icon-right"></i></a> </h5>
  		<ul class="mui-table-view mui-grid-view mui-grid-9 list" id="list-content">
			<?php  if(is_array($row['temp'])) { foreach($row['temp'] as $value) { ?>
			<li class="mui-table-view-cell mui-media mui-col-xs-4"> <a href="<?php  echo _Mobileurl('play', array('id'=>$value['tid']))?>"> <span style="position:absolute; right:0; bottom:40px; height:20px; line-height:20px; background:#FF6600; color:#FFFFFF; display:block; padding:0px 3px;  overflow:hidden; font-size:11px"><?php  echo $value['fdes'];?></span> <img src="../../<?php echo RES;?>/themes/images/iweite.png"  data-original="<?php  echo $value['fpic'];?>" class="grid-img lazy"/> <img class="game-corner" src="../../<?php echo RES;?>/themes/images/14470348816dL25.png" style="display: block">
		  <div class="type-title">
			<?php  echo $value['title'];?></div>
		  </a> </li>
		  <?php  } } ?>
	  <?php  } } ?> 
      </ul>
</ul>


<ul class="mui-table-view mui-grid-view mui-grid-9 main-content" style="margin-top:5px; padding-bottom:15px; text-align:center; color:#333; border-top:1px solid #d5d5d5" id=footer>
<li class="mui-table-view-cell" style="font-size:12px; line-height:180%; height:30px; text-align:center"> 
<?php  echo $copyright;?>
</li>
</ul>
<script type="text/javascript">
	var swiper = new Swiper('#banner-slider', {
			pagination: '.swiper-pagination',
			paginationClickable: true,
			autoplay:'2000',
			loop: true
		});
		$('#search-btn').click(function(){
			var $search=$('#search');
			if (!$search.val()) {
				alert('请输入片名！');
				$('#search').focus();
				return false;
			}
			window.location.href="<?php  echo _Mobileurl('search')?>&p="+encodeURIComponent($search.val());

		})

    $(function(){
		$("img.lazy").picLazyLoad({ 
			threshold : 200
		});
	})	 
</script>

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

<div style="display:none"><?php  if($setting['cnzz']) { ?>
<script src="<?php  echo $setting['cnzz'];?>"></script>
<?php  } ?>
</div>
</body>
</html>