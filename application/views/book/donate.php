<div class="newFancy donateBox donateStep1">
	<div class="newFancy_header donateBox_header">
		<p class="donateBox_header_step donateBox_header_step1 donateBox_header_currentStep">
		  Step.1<br/>
		  <span>输入ISBN号码</span>
		</p>
		<p class="donateBox_header_step donateBox_header_step2">
		  Step.2<br/>
		  <span>确认详细信息</span>
		</p>
		<p class="donateBox_header_step donateBox_header_step3">
		  Step.3<br/>
		  <span>捐赠成功</span>
		</p>
	</div>
	<div class="newFancy_middle donateBox_middle">
		<form method="post" action="<?php echo site_url("book/getisbn_do/"); ?>" class="donateStep1_form">
			<input type="text" name="isbn" class="donateStep1_form_isbn newFancy_input" id="isbn"/>
			<input type="submit" value="下一步 >" class="donateStep1_form_nextButton" />
			<p class="error" style="position:absolute; bottom:3px; left:240px; display:none;">请输入正确的ISBN</p>
		</form>
		<img src="<?php echo site_url("include/style/img/").'isbn_tips.png'; ?>" class="donateStep1_isbnTips" />
	  <div class="newFancy_postmark"></div>
	</div>
</div>

<script type="text/javascript">
var prompt = '请输入书籍的 ISBN 号码或者书名';
$('form input#isbn').val(prompt);

$('form input#isbn').focus(function(){
  if ( $(this).val() == prompt ){
    $(this).val('');
  }
});
$('form input#isbn').blur(function(){
  if ( $(this).val() == '' ){
    $(this).val(prompt);
  }
});

$("form").bind("submit", function() {
	var isbn = $('#isbn').val().replace(/[ ]/g,"");

	$.fancybox.showActivity();
	
	$.ajax({
		type		: "GET",
		url		    : "<?php echo site_url("book/getisbn_do/"); ?>",
		data		: $(this).serializeArray(),
		success: function(data) {
			$.fancybox(data);
		}
	});

	return false;
}); 
</script>