<div class="getreward fancy">
	<div class="fancybox_content">
		<h4>
			恭喜您成功借出一本书
		</h4>
	</div>
	<div class="fancybox_content hr" style="padding-top:0;">
		<div class="message"> 
			<img src="<?php echo $receiver->avatar; ?>" class="avatar" /> 
			<p class="message_content">
				<a class="username" href="<?php echo $this->MUser->getUrl($receiver->uid); ?>"><?php echo $receiver->nickname; ?></a>
				<?php if($receiver->isMobileValidate): ?>
				<img src="<?php echo site_url("include/style/img/"); ?>mobile.png" style="position:relative; top:2px;" title="手机已验证" />
				<?php else: ?>
				<img src="<?php echo site_url("include/style/img/"); ?>mobilenone.png" style="position:relative; top:2px;" title="手机未验证" />
				<?php endif; ?>
				收到您寄出的  
				<a href="<?php echo site_url("book/subject/$book->id/"); ?>">《<?php echo $book->name; ?>》</a> 
			</p>
			<p class="message_time">收到日期：<?php echo $record->receiveTime; ?></p> 
		</div> 
	</div>
	<div class="fancybox_content hr">
		<p class="reward">获得1张摆摆券奖励<p>
	</div>
	<div class="fancybox_content">
		<p class="nav">
			<a href="<?php echo site_url("book/getreward_do/$record->id/"); ?>" id="next" class="button">确定</a>
		</p>
	</div>
</div>
<script type="text/javascript">
$("#next").bind("click", function() {
	$.fancybox.showActivity();

	$.ajax({
		type		: "POST",
		url		: "<?php echo site_url("book/getreward_do/$record->id/"); ?>",
		data		: $("this").serializeArray(),
		success: function(data) {
			$.fancybox.close();
			window.location.reload();
		}
	});

	return false;
});
</script>