<html xmlns="http://www.w3.org/1999/xhtml"> 
<head> 
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="pubdate" content="<?php echo $weekly->pubdate; ?>">
	<title><?php echo "摆摆特刊第{$weekly->id}期：{$weekly->name}"; ?></title> 
	<style type="text/css">
		body{
			background:#F2EFE9;
		}
		div.wrapper{
			width:600px; 
			margin:0 auto;
		}
		div.contentBox{
			background:#FFF;
			border:1px solid #C9CACD;
			-webkit-border-radius:6px; -moz-border-radius:6px;
			margin:6px 0;
			-webkit-box-shadow:0 1px 3px rgba(0, 0, 0, 0.15); -moz-box-shadow:0 1px 3px rgba(0, 0, 0, 0.15);
		}
		div.contentBox_header{
			padding:10px;
		}
		div.contentBox_div{
			padding:10px 15px;
		}
		.bd-bottom{
			border-bottom:1px solid #E5E5E5;
		}
		img.contentBox_banner{
			width:580px; height:115px;
		}
		img.book{
			width:100px; height:140px;
			margin-right:10px; margin-bottom:15px;
		}
		h1{
			font-size:14px; font-weight:bold;
		}
		p{
			font-size:12px; line-height:20px;
			color:#555;
		}
		a{
			text-decoration:none;
		}
		img.snsLinks_link{
			width:28px;
		}
		.outer{
			margin:0 15px;
		}
		.outer,
		.outer a{
			font-size:12px; line-height:18px;
			color:#96938E;
		}
	</style>
</head>
<body>
	<div class="wrapper">
		<div class="outer" style="padding-top:10px;">
			<a href="<?php echo site_url("weekly/{$weekly->id}/"); ?>">在浏览器中浏览本页</a>
			<a href="<?php echo site_url("book/"); ?>" style="float:right;"><?php echo "摆摆书架目前共有 {$stockNumber} 本书 · 来自 {$cityNumber} 个城市"; ?></a>
		</div>
		<div class="contentBox">
			<div class="contentBox_header bd-bottom">
				<img src="<?php echo site_url("upload/weekly/").$weekly->id.'.jpg'; ?>" class="contentBox_banner" alt="摆摆书架，您自己的社会化图书馆" />
			</div><!--
			<div class="contentBox_div bd-bottom">
				<h1>Hi, LIGHT, 您有如下请求需要处理哦</h1>
				<table>
					<tr>
						<td style="width:50px;">
							<img src="http://www.gravatar.com/avatar/6a8990966c80a0adec55c25d829cc2ee?s=45" style="width:45px; height:45px;">
						</td>
						<td style="width:235px;">
							<a href="#" style="font-size:12px; line-height:20px; color:#0063DC;">
								LIGHT 想借《激荡三十年》<br/>
								查看详情
							</a>
						</td>
						<td style="width:50px;">
							<img src="http://www.gravatar.com/avatar/6a8990966c80a0adec55c25d829cc2ee?s=45" style="width:45px; height:45px;">
						</td>
						<td style="width:235px;">
							<a href="#" style="font-size:12px; line-height:20px; color:#0063DC;">
								LIGHT 想借《激荡三十年》<br/>
								查看详情
							</a>
						</td>
					</tr>
				</table>
			</div>-->
			<div class="contentBox_div bd-bottom">
				<h1><?php echo $weekly->name; ?></h1>
				<p><?php echo $weekly->desc; ?></p>
				<div>
					<?php foreach($books as $book): ?>
					<a href="<?php echo site_url("book/subject/{$book->id}/"); ?>" target="_blank">
						<img src="<?php echo $book->pic ? $book->pic : site_url('upload/book/cover/').'default.jpg'; ?>" style="width: 100px;height: 140px;margin-right: 10px;margin-bottom: 15px;" height="140" alt="<?php echo $book->name; ?>" class="book" />
					</a>
					<?php endforeach; ?>
				</div>
			</div>
			<div class="contentBox_div">
				<?php if($weekly->newsTitle && $weekly->newsLink) { ?>
				<h1 style="float:left;"><a href="<?php echo $weekly->newsLink; ?>" style="color:#41AFEA;" target="_blank"><?php echo $weekly->newsTitle; ?></a></h1>
				<?php } else { ?>
				<h1 style="float:left;">关注我们</h1>
				<?php } ?>
				<div style="float:right; margin-top:4px;">
					<a href="http://t.sina.com.cn/baibaibook/" target="_blank">
						<img src="http://bookfor.us/include/style/img/tsina.png" class="snsLinks_link" alt="新浪微博">
				  </a>
				  <a href="http://t.qq.com/baibaibook/" target="_blank">
            <img src="http://bookfor.us/include/style/img/tqq.png" class="snsLinks_link" alt="腾讯微博">
          </a>
          <a href="http://www.douban.com/people/baibaibook/" target="_blank">
            <img src="http://bookfor.us/include/style/img/dou.png" class="snsLinks_link" alt="豆瓣">
          </a>
          <a href="http://twitter.com/baibaibook/" target="_blank">
            <img src="http://bookfor.us/include/style/img/twitter.png" class="snsLinks_link" alt="Twitter">
          </a>
				</div>
				<div style="clear:both"></div>
			</div>
		</div>
		<div class="outer" style="padding-bottom:10px;">
			<a href="http://bookfor.us/setting/notify/">如果此邮件打扰到了您，请点击这里退订。</a>
			<a href="http://bookfor.us/" style="float:right;">© 2010－2011 摆摆书架 bookfor.us</a>
		</div>
	</div>
	<script type="text/javascript">
	  var _gaq = _gaq || [];
	  _gaq.push(['_setAccount', 'UA-19615786-1']);
	  _gaq.push(['_trackPageview']);

	  (function() {
	    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	  })();

	</script>
</body>
</html>