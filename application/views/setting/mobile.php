	<div id="body">
		<div class="inner">
			<div id="config" class="content_box content">
				<div class="contentBox_tabNav">
          <a href="<?php echo site_url("setting/profile"); ?>" class="contentBox_tabNav_tab first current">个人资料</a><!--
          --><a href="<?php echo site_url("setting/address"); ?>" class="contentBox_tabNav_tab">收货地址</a><!--
				  --><a href="<?php echo site_url("setting/sync"); ?>" class="contentBox_tabNav_tab">同步动态</a><!--
		      --><a href="<?php echo site_url("setting/notify"); ?>" class="contentBox_tabNav_tab">邮件提醒</a>
        </div>
				<?php if($mobile): ?>
				<form class="profile" method="post" action="<?php echo site_url("setting/mobile_link_do/{$mobile}/"); ?>">	
				<?php else: ?>
				<form class="profile" method="post" action="<?php echo site_url('setting/mobile/'); ?>">
				<?php endif; ?>
					<p style="font-size:16px; font-weight:bold;">绑定手机号码</p>
					<?php if ($this->session->flashdata('error')){ ?>
					<p id="error" class="nolabel"><?php echo $this->session->flashdata('error'); ?></p>
					<?php } ?>
					<?php if($mobile): ?>
					<p>验证短信已发送，请输入验证码，完成绑定。</p>
					<?php endif; ?>
					<p>
						<label for="mobile">手机号码：</label>
						<?php if($mobile): ?>
						<input type="text" name="mobile" class="text required" style="width:120px;" value="<?php echo $mobile; ?>" disabled />
						<?php else: ?>
						<input type="text" name="mobile" class="text required" style="width:120px;" value="<?php echo $user->mobile; ?>" />
						<?php endif; ?>
					</p>
					<?php if($mobile): ?>
					<p>
						<label for="code">验证码：</label>
						<input type="text" name="code" class="text required" style="width:120px;" />
					</p>
					<p>
						<input type="submit" value="绑定" class="nolabel button" />
					</p>
					<?php else: ?>
					<p>
						<input type="submit" value="获取验证码" class="nolabel button" />
					</p>
					<?php endif; ?>
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
	mobile:{   
		required:true,   
		isMobile:true  
	},
	code:{
		required:true
	}
},   

/* 设置错误信息 */  
messages: {   
	mobile:{   
		required: "请输入您的手机号码",   
		isMobile: "请正确填写您的手机号码"  
	}
},   

/* 设置错误信息提示DOM */  
errorPlacement: function(error, element) {       
	error.appendTo(element.parent());       
},     

}); 
</script>