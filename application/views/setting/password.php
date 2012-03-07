	<div id="body">
		<div class="inner">
			<div id="config" class="content_box content">
				<div class="contentBox_tabNav">
          <a href="<?php echo site_url("setting/profile"); ?>" class="contentBox_tabNav_tab first current">个人资料</a><!--
          --><a href="<?php echo site_url("setting/address"); ?>" class="contentBox_tabNav_tab">收货地址</a><!--
				  --><a href="<?php echo site_url("setting/sync"); ?>" class="contentBox_tabNav_tab">同步动态</a><!--
		      --><a href="<?php echo site_url("setting/notify"); ?>" class="contentBox_tabNav_tab">邮件提醒</a>
        </div>
				<form class="profile" method="post" action="<?php echo site_url('setting/password_do/'); ?>">
					<?php if ($this->session->flashdata('error')){ ?>
					<p id="error" class="nolabel"><?php echo $this->session->flashdata('error'); ?></p>
					<?php } ?>
					<p>
						<label for="oldPW">原始密码：</label>
						<input type="password" name="oldPW" class="text required" />
					</p>
					<p>
						<label for="newPW">新密码：</label>
						<input type="password" name="newPW" id="newPW" class="text" />
					</p>
					<p>
						<label for="newPW2">新密码：</label>
						<input type="password" name="newPW2" class="text" />
					</p>
					<p>
						<input type="submit" value="好了，修改吧" class="nolabel button" />
					</p>
				</form>
			</div>
			<?php echo $this->Common->sidebar(); ?>
			<div class="clearfix"></div>
		</div>
	</div>
<script type="text/javascript" src="<?php echo site_url('include/script/jquery-validate/').'jquery.validate.pack.js'; ?>"></script>
<script type="text/javascript" src="<?php echo site_url('include/script/jquery-validate/').'jquery.validate.extendByYt.js'; ?>"></script>
<script type="text/javascript"> 
$('form.profile').validate({   
/* 设置验证规则 */  
rules: {   
	newPW: {
		required: true,
		minlength: 6
	},
	newPW2: {
		required: true,
		minlength: 6,
		equalTo: "#newPW"
	} 
},   
     
/* 设置错误信息 */  
messages: {    
	newPW: {
		required: '请输入密码',
		minlength: '密码必须大于6个字符'
	},
	newPW2: {
		required: '请确认你的密码',
		equalTo: '两次密码输入不一致',
		minlength: '密码必须大于6个字符'
	}
},   

/* 设置验证触发事件 */  
focusInvalid: false,   
onkeyup: true,   
    
/* 设置错误信息提示DOM */  
errorPlacement: function(error, element) {       
	error.appendTo( element.parent());       
},     
  
});  
</script>