<div class="newFancy donateBox donateStep2">
  <div class="newFancy_header donateBox_header">
    <p class="donateBox_header_step donateBox_header_step1">
      Step.1<br/>
      <span>输入ISBN号码</span>
    </p>
    <p class="donateBox_header_step donateBox_header_step2 donateBox_header_currentStep">
      Step.2<br/>
      <span>确认详细信息</span>
    </p>
    <p class="donateBox_header_step donateBox_header_step3">
      Step.3<br/>
      <span>捐赠成功</span>
    </p>
  </div>
	<div class="newFancy_middle donateBox_middle">
	  <div class="newFancy_bookInfo">
  		<img src="<?php echo $book->pic ? $book->pic : site_url('upload/book/cover/').'default.jpg'; ?>" class="newFancy_bookInfo_cover" />
  		<ul class="newFancy_bookInfo_ul">
  		  <li class="newFancy_bookInfo_ul_name"><?php echo $book->name; ?></li>
  			<li>ISBN：<?php echo $book->isbn; ?></li>
  			<li>作者：<?php echo $book->author; ?></li>
  			<?php if($book->translator): ?><li>译者：<?php echo $book->translator; ?></li><?php endif; ?>
  			<li>出版社：<?php echo $book->publisher; ?></li>
  			<li>出版时间：<?php echo $book->pubdate; ?></li>
  			<li>定价：<?php echo $book->price; ?></li>
  		</ul>
		</div>
		<div class="newFancy_postmark"></div>
	</div>
	<div class="newFancy_footer donateBox_footer donateStep2_footer">
		<!--
		<h3>给书加上标签吧，方便大家查找（记得用空格区分标签哦）</h3>
		<form method="post" class="donateStep2_form">
			<input type="hidden" name="oldTags" value="<?php echo $myTagsOfBook; ?>" />
			<input type="text" name="tag" class="donateStep2_form_tag newFancy_input" id="tag" value="<?php echo $myTagsOfBook; ?>" />
		</form>
		-->
		<p class="donateStep2_nav" style="position:relative;">
			<?php if(count($myLinkedProviders)>0): ?>
			<span style="position:absolute; left:0; top: 6px; color:#929292;">
				同步到
				<?php if(in_array('douban',$myLinkedProviders)): ?>
				<input type="checkbox" name="douban" id="douban" checked style="margin:0 2px 0 6px;">豆瓣
				<?php endif; ?>
				<?php if(in_array('tsina',$myLinkedProviders)): ?>
				<input type="checkbox" name="tsina" id="tsina" checked style="margin:0 2px 0 6px;">新浪微博
				<?php endif; ?>
				<?php if(in_array('tqq',$myLinkedProviders)): ?>
				<input type="checkbox" name="tqq" id="tqq" checked style="margin:0 2px 0 6px;">腾讯微博
				<?php endif; ?>
			</span>
			<?php endif; ?>
      <a href="<?php echo site_url("book/donate/"); ?>" id="prev" class="newFancy_button newFancy_prevButton"><</a>
      <a href="<?php echo site_url("book/donate_do/$book->id/"); ?>" id="next" class="newFancy_button">确认</a>
    </p>
	</div>
</div>
<script type="text/javascript">

$("#prev").bind("click", function() {
	$.fancybox.showActivity();

	$.ajax({
		type		: "POST",
		url		: "<?php echo site_url("book/donate/"); ?>",
		data		: $(this).serializeArray(),
		success: function(data) {
			$.fancybox(data);
		}
	});

	return false;
});

$("#next").bind("click", function() {
  var href = $(this).attr('href');
  $(this).attr("href", 'javascript:void();');  
           
	$.fancybox.showActivity();
	
	postData = '&tsina='+$('#tsina').attr('checked')+'&douban='+$('#douban').attr('checked')+'&tqq='+$('#tqq').attr('checked');

	$.ajax({
		type		: "POST",
		url		: href,
		data		: postData,
		success: function(data) {
			$.fancybox(data);
		}
	});

	return false;
});
</script>