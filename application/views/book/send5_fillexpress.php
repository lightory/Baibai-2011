<div class="newFancy">
  <div class="newFancy_header borrowBox_header">
    <div class="newFancy_header_left newFancy_header_user">
      <?php $receiver->address = $this->MAddress->getDefaultByUid($receiver->uid); ?>
      <img src="<?php echo getGravatar($receiver->email, 30); ?>" class="newFancy_avatar" />
      <p>
		<a class="username" href="<?php echo $this->MUser->getUrl($receiver->uid); ?>"><?php echo $receiver->nickname; ?></a>
		<?php if($receiver->isMobileValidate): ?>
		<img src="<?php echo site_url("include/style/img/"); ?>mobile.png" style="position:relative; top:2px;" title="手机已验证" />
		<?php else: ?>
		<img src="<?php echo site_url("include/style/img/"); ?>mobilenone.png" style="position:relative; top:2px;" title="手机未验证" />
		<?php endif; ?>
		想借
		<a href="<?php echo site_url("book/subject/$book->id/"); ?>">《<?php echo $book->name; ?>》</a> 
	</p>
      <p><span class="address"><?php echo $receiver->address->province."，".$receiver->address->city; ?></span></p>
    </div>
    <div class="newFancy_header_right newFancy_header_time">
      <?php echo mdate('%Y-%m-%d %H:%i', $record->time2); ?>
    </div>
  </div>
	<div class="newFancy_middle borrowBox_middle">
	  <div class="newFancy_addressInfo">
	    <h3>填写快递信息</h3>
  		<form method="post" action="<?php echo site_url("book/fillexpress_do/$record->id/"); ?>" class="newFancy_middle_form">
    		<ul>
    			<li>
    				<label class="expressType">快递</label>
    				<select name="expressType" id="expressType">
    				  <option value="申通">申通</option>
    				  <option value="圆通">圆通</option>
    				  <option value="中通">中通</option>
    				  <option value="韵达">韵达</option>
    				  <option value="顺丰">顺丰</option>
							<option value="邮政">邮政</option>
    				  <option value="其它">其它</option>
    				</select>
    				<input type="text" name="expressType2" id="expressType2" placeholder="快递名" style="width:70px; display:none;" />
    			</li>
    			<li>
    				<label class="expressId"></label>
    				<input type="text" name="expressId" id="expressId" placeholder="点击输入快递单号" />
					<span style="color:red; visibility:hidden;" id="expressIdAlert">请输入快递单号</span>
    			</li>
    			<li>
    				<label class="sentTime">发书时间</label>
    				<input type="text" name="sentTime" id="sentTime" value="<?php echo mdate('%Y-%m-%d', time()) ?>" />
    			</li>
    		</ul>
    		<h3 style="margin-top:10px; position:relative; left:-20px;">漂流寄语（关于这本书，随便说点什么吧）</h3>
    		<textarea name="finishMessage" class="newFancy_input" style="margin-top:6px; width:280px;"></textarea>
  		</form>
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
			<a href="javascript:void(0);" id="next" class="newFancy_button">确定</a>
		</p>
	</div>
</div>

<script type ="text/javascript" src="<?php echo site_url('include/script/datePicker/').'date.js'; ?>"></script>
<script type ="text/javascript" src="<?php echo site_url('include/script/datePicker/').'jquery.datePicker.js'; ?>"></script>
<link rel="stylesheet" href="<?php echo site_url('include/script/datePicker/').'datePicker.css'; ?>" type="text/css" media="screen" />

<script type="text/javascript">
$("#next").bind("click", function() {
	if (!$('#expressId').val()) {
		$('#expressIdAlert').css('visibility', 'visible');
		return false;
	}
	
	$.fancybox.showActivity();

	postData = $("div.newFancy_addressInfo form").serialize()+'&tsina='+$('#tsina').attr('checked')+'&douban='+$('#douban').attr('checked')+'&tqq='+$('#tqq').attr('checked');

	$.ajax({
		type		: "POST",
		url			: "<?php echo site_url("book/fillexpress_do/$record->id/"); ?>",
		data		: postData,
		success: function(data) {
			$.fancybox(data);
		}
	});

	return false;
});

$('#expressType').change(function(){
  if ($(this).val()=='其它'){
    $('#expressType2').show();
  } else{
    $('#expressType2').hide();
  }
});

Date.format = 'yyyy-mm-dd';
$('#sentTime').datePicker({
	startDate:'2010-01-01',
	endDate:(new Date()).asString(),
	clickInput:'true'
});
</script>