<div id="body">
  <div class="inner">
		<div class="breadcrumbs">
			<a href="<?php echo site_url("group/"); ?>">小组</a>
			<span style="font-size:12px;">&gt;</span>
			<a href="<?php echo site_url("group/newgroup"); ?>">申请创建小组</a>
		</div>
		<div class="content">
    	<div class="content_box group">
        <form class="profile" method="post" action="<?php echo site_url("group/newgroup_do/"); ?>" style="padding:30px 20px;">
					<?php if ($this->session->flashdata('error')){ ?>
          <p id="error" class="nolabel"><?php echo $this->session->flashdata('error'); ?></p>
          <?php } ?>
					<p>
						<label for="groupName">小组名称：</label>
						<input type="text" name="groupName" id="groupName" class="text" />
					</p>
					<p>
						<label for="groupUrl">访问地址：</label>
						http://bookfor.us/group/ <input type="text" name="groupUrl" id="groupUrl" class="text" style="width:94px" />
					</p>
					<p>
						<label for="bio">申请说明：</label>
						<textarea name="bio" id="bio"></textarea>
					</p>
					<p>
						<input type="submit" value="申请创建小组" class="nolabel button">
					</p>
				</form>
      </div>
		</div>
		<script type="text/javascript" src="<?php echo site_url('include/script/jquery-validate/').'jquery.validate.pack.js'; ?>"></script>
		<script type="text/javascript" src="<?php echo site_url('include/script/jquery-validate/').'jquery.validate.extendByYt.js'; ?>"></script>
		<script type="text/javascript"> 
		$('form.profile').validate({   
		/* 设置验证规则 */  
		rules: {   
			groupName: {   
				required:true
			},
			groupUrl: {
				required: true
			},
			bio:{   
				required:true
			} 
		},   

		/* 设置错误信息 */  
		messages: {   
			groupName: {       
				required: "请填写小组名",    
			},   
			groupUrl: {
				required: '请填写期望的访问地址',
			},
			bio: {
				required: "请填写申请原因"
			},  
		},    

		/* 设置错误信息提示DOM */  
		errorPlacement: function(error, element) {       
			error.appendTo( element.parent());       
		},     

		});  
		</script>