	<div id="body">
		<div class="inner">
			<div id="login" class="content_box">
				<div class="login_main">
					<h2>重设密码</h2>
					
					<?php if('0'==$confirm): ?>
					<form class="profile" method="post" action="<?php echo site_url("account/resetpassword_do/"); ?>">
						<?php if ($this->session->flashdata('error')){ ?>
						<p id="error" class="nolabel"><?php echo $this->session->flashdata('error'); ?></p>
						<?php } ?>
						<p>
							<label for="email">Email：</label>
							<input type="text" name="email" id="email" class="text" />
						</p>
						<p>
							<input type="submit" value="重设密码" class="nolabel button" />
						</p>
					</form>
					<?php elseif('1'==$confirm): ?>
					<p>您的密码重设要求已受理，请检查邮箱查看验证邮件。</p>
					<?php else: ?>
					<form class="profile" method="post" action="<?php echo site_url("account/resetpassword_do/$confirm/"); ?>">
						<p>
							<label for="password">密码：</label>
							<input type="password" name="password" id="password" class="text" />
						</p>
						<p>
							<label for="password2">重复密码：</label>
							<input type="password" name="password2" id="password2" class="text" />
						</p>
						<p>
							<input type="submit" value="重设密码" class="nolabel button" />
						</p>
					</form>
					<?php endif; ?>
				</div>
				<div class="clearfix"></div>
			</div>
		</div>
	</div>
	
<script type="text/javascript" src="<?php echo site_url('include/script/jquery-validate/').'jquery.validate.pack.js'; ?>"></script>
<script type="text/javascript" src="<?php echo site_url('include/script/jquery-validate/').'jquery.validate.extendByYt.js'; ?>"></script>
<?php if('0'==$confirm): ?>
<script type="text/javascript"> 
$('form.profile').validate({   
/* 设置验证规则 */  
rules: {   
	email:{   
		required:true,   
		email:true
	}   
},   
     
/* 设置错误信息 */  
messages: {   
	address:{   
		required: "请输入您的Email",   
		email: "请输入正确的Email"  
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
<?php elseif ('1'!=$confirm): ?>
<script type="text/javascript"> 
$('form.profile').validate({   
/* 设置验证规则 */  
rules: {   
	password: {
		required: true,
		minlength: 6
	},
	password2: {
		required: true,
		minlength: 6,
		equalTo: "#password"
	} 
},   
     
/* 设置错误信息 */  
messages: {    
	password: {
		required: '请输入密码',
		minLength: '密码必须大于6个字符'
	},
	password2: {
		required: '请确认你的密码',
		equalTo: '两次密码输入不一致',
		minLength: '密码必须大于6个字符'
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
<?php endif; ?>