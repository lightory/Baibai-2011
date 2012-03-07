	<div id="body">
		<div class="inner">
			<div id="config" class="content_box content">
				<div class="contentBox_tabNav">
          <a href="<?php echo site_url("setting/profile"); ?>" class="contentBox_tabNav_tab first current">个人资料</a><!--
          --><a href="<?php echo site_url("setting/address"); ?>" class="contentBox_tabNav_tab">收货地址</a><!--
				  --><a href="<?php echo site_url("setting/sync"); ?>" class="contentBox_tabNav_tab">同步动态</a><!--
	        --><a href="<?php echo site_url("setting/notify"); ?>" class="contentBox_tabNav_tab">邮件提醒</a>
        </div>
				<form class="profile" method="post" action="<?php echo site_url('setting/profile_do/'); ?>">
					<?php if ($this->session->flashdata('error')){ ?>
					<p id="error" class="nolabel"><?php echo $this->session->flashdata('error'); ?></p>
					<?php } ?>
					<p>
						<label for="username">用户名：</label>
						<?php if($user->username == $user->uid): ?>
						<input type="text" name="username" id="username" class="text">
						（最多15个字符，设置后不能修改）
						<?php else: ?>
						<input type="text" name="username" id="username" class="text" value="<?php echo $user->username; ?>" disabled>
						<?php endif; ?>
					</p>
					<p>
						<label>密码：</label>
						<a href="<?php echo site_url('setting/password/'); ?>">修改</a>
					</p>
					<p>
						<label>头像：</label>
						<img src="<?php echo $user->avatar; ?>" class="avatar-45 avatar" />
						<a href="http://blog.bookfor.us/archives/40/" target="_blank" style="vertical-align:top;">如何修改？</a>
					</p>
					<p>
						<label for="mobile">手机：</label>
						<input type="text" name="mobile" class="text" value="<?php echo $user->mobile; ?>" <?php if($user->isMobileValidate): ?>disabled<?php endif; ?>/>
						<?php if($user->isMobileValidate): ?>
						<a href="<?php echo site_url('setting/mobile_unlink_do/'); ?>">取消绑定</a>
						<?php else: ?>
						<a href="<?php echo site_url('setting/mobile/'); ?>">绑定号码</a>
						<?php endif; ?>
					</p>
					<p>
						<label for="nickname">昵称：</label>
						<input type="text" name="nickname" class="text" value="<?php echo $user->nickname; ?>" />
					</p>
					<p>
						<label for="blog">博客：</label>
						<input type="text" name="blog" id="blog" class="text" value="<?php echo $user->blog; ?>" />
					</p>
					<p>
						<label for="bio">自我介绍：</label>
						<textarea name="bio"><?php echo $user->bio; ?></textarea>
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
	blog:{   
		url:true 
	},
	mobile:{   
		required:true,   
		isPhone:true  
	}
},   
     
/* 设置错误信息 */  
messages: {   
	blog:{
		url:'请输入正确的URL（加http://）'
	},
	mobile:{   
		required: "请输入您的联系电话",   
		isPhone: "请输入一个有效的联系电话"  
	},
},   
    
/* 设置错误信息提示DOM */  
errorPlacement: function(error, element) {       
	error.appendTo(element.parent());       
},     
  
});  
</script>