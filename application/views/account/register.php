	<div id="body">
		<div class="inner">
			<div id="login" class="content_box">
				<div class="login_main">
					<h2>快速注册</h2>
					<?php if($ok): ?>
					<form class="profile" method="post" action="<?php echo site_url("account/register_do/"); ?>">
					  <?php if ($this->session->flashdata('error')){ ?>
            <p id="error" class="nolabel"><?php echo $this->session->flashdata('error'); ?></p>
            <?php } ?>
						<input type="text" name="invitation" id="invitation" class="text" value="<?php echo $user->code; ?>" style="display:none;" />
						<p>
							<label for="email">Email：</label>
							<input type="text" class="text" value="<?php echo $user->email; ?>" disabled />
						</p>
						<p>
							<label for="nickname">昵称：</label>
							<input type="text" name="nickname" id="nickname" class="text" />
						</p>
						<p>
							<label for="password">密码：</label>
							<input type="password" name="password" id="password" class="text" />
						</p>
						<p>
							<label for="password2">重复密码：</label>
							<input type="password" name="password2" id="password2" class="text" />
						</p>
						<br/>
						<p>
							<label for="name">姓名：</label>
							<input type="text" name="name" id="name" class="text" />
						</p>
						<p>
							<label for="province">地区：</label>
							<select name="province" id="province"></select>
							<select name="city" id="city"></select>
							<select name="district" id="district"></select>
							<input name="province2" id="province2" value="" style="display:none" />
							<input name="city2" id="city2" value="" style="display:none" />
							<input name="district2" id="district2" value="" style="display:none" />
						<p>
							<label for="address">街道地址：</label>
							<input type="text" name="address" id="address" class="text required" />
						</p>
						<p>
							<label for="postcode">邮政编码：</label>
							<input type="text" name="postcode" id="postcode" class="text"/>
						<p>
						<p>
							<label for="phone">联系电话：</label>
							<input type="text" name="phone" id="phone" class="text" />
						</p>
						<p>
							<input type="submit" value="注册" class="nolabel button" />
						</p>
					</form>
					<?php else: ?>
					<p>对不起，摆摆书架还处于内测阶段，没有开放注册。</p>
					<p>您可以<a href="<?php echo site_url('about/'); ?>" />读一下摆摆书架的基本规则</a>，如果认可的话，<a href="<?php echo site_url('account/apply/'); ?>">请填写这份内测资格申请问卷</a>。</p>
					<?php endif; ?>
				</div>
				<div class="login_side">
					<p>已有摆摆书架账号？<a href="<?php echo site_url("account/login/"); ?>">点此登录</a></p>
				</div>
				<div class="clearfix"></div>
			</div>
		</div>
	</div>
	
<script type="text/javascript" src="<?php echo site_url('include/script/jquery-validate/').'jquery.validate.pack.js'; ?>"></script>
<script type="text/javascript" src="<?php echo site_url('include/script/jquery-validate/').'jquery.validate.extendByYt.js'; ?>"></script>
<script type="text/javascript"> 
$('form.profile').validate({   
/* 设置验证规则 */  
rules: {   
	nickname: {   
		required:true,   
		stringCheck:true,   
		byteRangeLength:[3,15]
	},
	password: {
		required: true,
		minlength: 6
	},
	password2: {
		required: true,
		minlength: 6,
		equalTo: "#password"
	},
	name: {
		required:true
	},
	phone:{   
		required:true,   
		isPhone:true  
	},   
	address:{   
		required:true,   
		byteRangeLength:[10,100]   
	}   
},   
     
/* 设置错误信息 */  
messages: {   
	nickname: {       
		required: "请填写用户名",   
		stringCheck: "用户名只能包括中文字、英文字母、数字和下划线",   
		byteRangeLength: "用户名必须在3-15个字符之间(一个中文字算2个字符)"
	},   
	password: {
		required: '请输入密码',
		minLength: '密码必须大于6个字符'
	},
	password2: {
		required: '请确认你的密码',
		equalTo: '两次密码输入不一致',
		minLength: '密码必须大于6个字符'
	},
	name: {
		required: "请填写姓名"
	},
	phone:{   
		required: "请输入您的联系电话",   
		isPhone: "请输入一个有效的联系电话"  
	},   
	address:{   
		required: "请输入您的联系地址",   
		byteRangeLength: "请详实您的联系地址以便于能够收到书籍"  
	}   
},    
    
/* 设置错误信息提示DOM */  
errorPlacement: function(error, element) {       
	error.appendTo( element.parent());       
},     
  
});  
</script>
<script type="text/javascript" src="<?php echo site_url('include/script/').'districtData.js'; ?>"></script>
<script type="text/javascript" src="<?php echo site_url('include/script/').'district.js'; ?>"></script>