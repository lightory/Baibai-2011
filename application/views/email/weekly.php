<html xmlns="http://www.w3.org/1999/xhtml"> 
<head> 
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title><?php echo $title; ?></title> 
</head>
<body style="background: #F2EFE9;">
	<div class="wrapper" style="width: 600px;margin: 0 auto;">
		<div class="outer" style="padding-top: 10px;margin: 0 15px;font-size: 12px;line-height: 18px;color: #96938E;">
			<a href="<?php echo site_url("weekly/{$weekly->id}/"); ?>" style="text-decoration: none;font-size: 12px;line-height: 18px;color: #96938E;">在浏览器中浏览本页</a>
			<a href="http://bookfor.us/book/" style="float: right;text-decoration: none;font-size: 12px;line-height: 18px;color: #96938E;"><?php echo "摆摆书架目前共有 {$stockNumber} 本书 · 来自 {$cityNumber} 个城市"; ?></a>
		</div>
		<div class="contentBox" style="background: #FFF;border: 1px solid #C9CACD;-webkit-border-radius: 6px;-moz-border-radius: 6px;margin: 6px 0;-webkit-box-shadow: 0 1px 3px rgba(0, 0, 0, 0.15);-moz-box-shadow: 0 1px 3px rgba(0, 0, 0, 0.15);">
			<div class="contentBox_header bd-bottom" style="border-bottom: 1px solid #E5E5E5;padding: 10px;">
				<img src="<?php echo site_url("/upload/weekly/")."{$weekly->id}.jpg"; ?>" class="contentBox_banner" alt="摆摆书架，您自己的社会化图书馆" style="width: 580px;height: 115px;">
			</div>
			
			<?php if(sizeof($borrowRequests) > 0): ?>
			<div class="contentBox_div bd-bottom" style="border-bottom: 1px solid #E5E5E5;padding: 10px 15px;">
				<h1 style="font-size: 14px;font-weight: bold;">Hi, <?php echo $user->nickname; ?>, 您有如下请求需要处理哦</h1>
				<table>
					<tr>
					<?php foreach($borrowRequests as $borrowRequest){
						if('reading'==$borrowRequest->type){
							$borrowRequest->type = 'onhand';
						}
						$avatar = getGravatar($borrowRequest->email, 45);
					?>
						<td style="width:50px;">
							<img src="<?php echo $avatar; ?>" style="width:45px; height:45px;">
						</td>
						<td style="width:235px;">
							<a href="<?php echo site_url("mine/$borrowRequest->type/")."#book-$borrowRequest->id"; ?>" style="font-size: 12px;line-height: 20px;color: #0063DC;text-decoration: none;">
								<?php echo "{$borrowRequest->nickname} 想借《{$borrowRequest->name}》"; ?>
								<br/>查看详情
							</a>
						</td>
					<?php } //endforeach ?>
					</tr>
				</table>
			</div>
			<?php endif; ?>
			
			<div class="contentBox_div bd-bottom" style="border-bottom: 1px solid #E5E5E5;padding: 10px 15px;">
				<h1 style="font-size: 14px;font-weight: bold;"><?php echo $title; ?></h1>
				<p style="font-size: 12px;line-height: 20px;color: #555;"><?php echo $weekly->desc; ?></p>
				<div>
					<?php foreach($books as $book): ?>
					<a href="<?php echo site_url("book/subject/{$book->id}"); ?>" target="_blank" style="text-decoration: none;">
						<img src="<?php echo $book->pic ? $book->pic : site_url('upload/book/cover/').'default.jpg'; ?>" style="width: 100px;height: 140px;margin-right: 10px;margin-bottom: 15px;" height="140" alt="<?php echo $book->name; ?>" class="book">
					</a>
					<?php endforeach; ?>
				</div>
			</div>
			<div class="contentBox_div" style="padding: 10px 15px;">
				<?php if($weekly->newsTitle && $weekly->newsLink): ?>
				<h1 style="float:left;"><a href="<?php echo $weekly->newsLink; ?>" style="color:#41AFEA;" target="_blank"><?php echo $weekly->newsTitle; ?></a></h1>
				<?php endif; ?>
				<div style="float:right; margin-top:4px;">
					<a href="http://t.sina.com.cn/baibaibook/" target="_blank" style="text-decoration: none;">
						<img src="http://bookfor.us/include/style/img/tsina.png" class="snsLinks_link" alt="新浪微博" style="width: 28px;">
					</a>
					<a href="http://t.qq.com/baibaibook/" target="_blank" style="text-decoration: none;">
						<img src="http://bookfor.us/include/style/img/tqq.png" class="snsLinks_link" alt="腾讯微博" style="width: 28px;">
					</a>
					<a href="http://www.douban.com/people/baibaibook/" target="_blank" style="text-decoration: none;">
						<img src="http://bookfor.us/include/style/img/dou.png" class="snsLinks_link" alt="豆瓣" style="width: 28px;">
					</a>
					<a href="http://twitter.com/baibaibook/" target="_blank" style="text-decoration: none;">
						<img src="http://bookfor.us/include/style/img/twitter.png" class="snsLinks_link" alt="Twitter" style="width: 28px;">
					</a>
				</div>
				<div style="clear:both"></div>
			</div>
		</div>
		<div class="outer" style="padding-bottom: 10px;margin: 0 15px;font-size: 12px;line-height: 18px;color: #96938E;">
			<a href="http://bookfor.us/setting/notify/" style="text-decoration: none;font-size: 12px;line-height: 18px;color: #96938E;">如果此邮件打扰到了您，请点击这里退订。</a>
			<a href="http://bookfor.us/" style="float: right;text-decoration: none;font-size: 12px;line-height: 18px;color: #96938E;">© 2010－2011 摆摆书架 bookfor.us</a>
		</div>
	</div>
</body>
</html>