<div class="newFancy sendMail">
  <div class="newFancy_header sendMail_header">
    <h3>登录后才可进行此项操作</h3>
    <div class="newFancy_postmark"></div>
  </div>
  <div class="newFancy_middle sendMail_middle">
    <form class="sendMail_middle_form" method="post" action="<?php echo site_url("account/login_do/fancy/"); ?>">
      <p>
				<label for="email">Email：</label>
				<input type="text" name="email" id="email" class="text">
			</p>
			<p>
				<label for="password">密码：</label>
				<input type="password" name="password" id="password" class="text">
			</p>
			<p>
				<label class="nolabel">
					<input type="checkbox" name="remember" value="1">记住我
				</label>
				<a href="http://localhost/baibai/account/resetpassword/" id="forget_pw">忘记密码</a>
			</p>
      <a href="javascript:void(0);" class="newFancy_button sendMail_middle_form_button">登录</a>
    </form>
    <div class="clearfix"></div>
  </div>
</div>

<script type="text/javascript">
$("a.sendMail_middle_form_button").click(function(){      
  $.fancybox.showActivity();

  $.ajax({
    type    : "POST",
    url   : $(this).parent().attr('action'),
    data    : $(this).parent().serializeArray(),
    success: function(data) {
      $.fancybox(data);
    }
  });
  
  return false;
});

$(document).keypress(function(e){
  if(e.ctrlKey && e.which == 13 || e.which == 10) {
    $("a.sendMail_middle_form_button").click();
  }
});
</script>