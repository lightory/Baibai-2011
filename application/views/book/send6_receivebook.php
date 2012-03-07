<div class="newFancy borrowBox">
  <div class="newFancy_header borrowBox_header">
    <div class="newFancy_header_left newFancy_header_user">
      <?php $senter->address = $this->MAddress->getDefaultByUid($senter->uid); ?>
      <img src="<?php echo getGravatar($senter->email, 30); ?>" class="newFancy_avatar" />
      <p>
		<a class="username" href="<?php echo $this->MUser->getUrl($senter->uid); ?>"><?php echo $senter->nickname; ?></a>
		<?php if($senter->isMobileValidate): ?>
		<img src="<?php echo site_url("include/style/img/"); ?>mobile.png" style="position:relative; top:2px;" title="手机已验证" />
		<?php else: ?>
		<img src="<?php echo site_url("include/style/img/"); ?>mobilenone.png" style="position:relative; top:2px;" title="手机未验证" />
		<?php endif; ?>
		已经将<a href="<?php echo site_url("book/subject/$book->id/"); ?>">《<?php echo $book->name; ?>》</a>
		寄给您
	</p>
      <p><span class="address"><?php echo $senter->address->province."，".$senter->address->city; ?></span></p>
    </div>
    <div class="newFancy_header_right newFancy_header_time">
      <?php echo mdate('%Y-%m-%d %H:%i', $record->time3); ?>
    </div>
  </div>
	<div class="newFancy_middle borrowBox_middle">
    <div class="newFancy_addressInfo">
      <h3>快递信息</h3>
      <ul>
        <li>
          <label class="expressType">快递公司</label>
          <?php echo $record->expressType; ?>
        </li>
        <li>
          <label class="expressId">快递编号</label>
          <?php echo $record->expressId; ?>
          <?php if(in_array($record->expressType, array('申通','圆通','韵达','中通','顺丰','邮政'))): ?>
          (<a href="<?php echo site_url('book/trackexpress/'.$record->expressType.'/'.$record->expressId); ?>" target="_blank">查看物流</a>)
          <?php endif; ?>
        </li>
        <li>
          <label class="sentTime">发书时间</label>
          <?php echo $record->sentTime; ?>
        </li>
      </ul>
      <h3 style="margin-top:10px;"><?php echo $senter->nickname; ?> 的漂流寄语</h3>
      <?php echo $record->finishMessage; ?>
    </div>
    <img class="borrowBox_middle_bookCover" src="<?php echo $book->pic ? $book->pic : site_url('upload/book/cover/').'default.jpg'; ?>" />
    <p class="clearfix"></p>
    <div class="newFancy_postmark"></div>
  </div>
	<div class="newFancy_footer">
		<p class="nav" style="text-align:right; padding:10px 20px; position:relative;">
			<?php if(count($myLinkedProviders)>0): ?>
			<span style="position:absolute; left:20px; top:16px; color:#929292;">
				同步到
				<?php if(in_array('douban',$myLinkedProviders)): ?>
				<input type="checkbox" name="douban" id="tsina" value="1" checked style="margin:0 2px 0 6px;">豆瓣
				<?php endif; ?>
				<?php if(in_array('tsina',$myLinkedProviders)): ?>
				<input type="checkbox" name="tsina" id="tsina" value="1" checked style="margin:0 2px 0 6px;">新浪微博
				<?php endif; ?>
				<?php if(in_array('tqq',$myLinkedProviders)): ?>
				<input type="checkbox" name="tqq" id="tqq" value="1" checked style="margin:0 2px 0 6px;">腾讯微博
				<?php endif; ?>
			</span>
			<?php endif; ?>
		  <a href="<?php echo site_url("group/baibai/"); ?>" style="margin-right:10px;">我要投诉</a>
			<a href="javascript:void(0);" id="next" class="newFancy_button">确定收到书</a>
		</p>
	</div>
</div>

<script type ="text/javascript" src="<?php echo site_url('include/script/datePicker/').'date.js'; ?>"></script>
<script type ="text/javascript" src="<?php echo site_url('include/script/datePicker/').'jquery.datePicker.js'; ?>"></script>
<link rel="stylesheet" href="<?php echo site_url('include/script/datePicker/').'datePicker.css'; ?>" type="text/css" media="screen" />

<script type="text/javascript">
$("#next").bind("click", function() {
	$.fancybox.showActivity();

	postData = $("div.fancybox_content form").serialize()+'&tsina='+$('#tsina').attr('checked')+'&douban='+$('#douban').attr('checked')+'&tqq='+$('#tqq').attr('checked');

	$.ajax({
		type		: "POST",
		url		: "<?php echo site_url("book/receivebook_do/$record->id/"); ?>",
		data		: postData,
		success: function(data) {
			$.fancybox(data);
		}
	});

	return false;
});

Date.format = 'yyyy-mm-dd';
$('#receiveTime').datePicker({
	startDate:'2010-01-01',
	endDate:(new Date()).asString(),
	clickInput:'true'
});
</script>