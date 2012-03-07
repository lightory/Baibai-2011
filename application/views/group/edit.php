<div id="body">
  <div class="inner">
		<div class="breadcrumbs">
			<a href="<?php echo site_url("group/"); ?>">小组</a>
			<span style="font-size:12px;">&gt;</span>
			<a href="<?php echo site_url("group/$group->url/"); ?>"><?php echo $group->name; ?></a>
			<span style="font-size:12px;">&gt;</span>
			<a href="<?php echo site_url("group/$group->url/edit/"); ?>">更改设置</a>
		</div>
		<div class="content">
    	<div class="content_box group">
				<div class="contentBox_tabNav">
          <a href="<?php echo site_url("group/$group->url/edit/"); ?>" class="contentBox_tabNav_tab first current">基本信息</a><!--
          --><a href="<?php echo site_url("group/$group->url/editicon"); ?>" class="contentBox_tabNav_tab">小组图标</a>
        </div>
        <form class="profile" method="post" action="<?php echo site_url("group/$group->url/edit_do/"); ?>" style="padding:30px 20px;">
					<?php if ($this->session->flashdata('error')){ ?>
          <p id="error" class="nolabel"><?php echo $this->session->flashdata('error'); ?></p>
          <?php } ?>
					<p>
						<label for="groupName">小组名称：</label>
						<input type="text" name="groupName" id="groupName" class="text" value="<?php echo $group->name; ?>" />
					</p>
					<p>
						<label for="groupDesc">小组介绍：</label>
						<textarea name="groupDesc" id="groupDesc"><?php echo $group->desc; ?></textarea>
					</p>
					<p>
						<input type="submit" value="更新小组设置" class="nolabel button">
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
			}
		},   

		/* 设置错误信息 */  
		messages: {   
			groupName: {       
				required: "请填写小组名",    
			}
		},    

		/* 设置错误信息提示DOM */  
		errorPlacement: function(error, element) {       
			error.appendTo( element.parent());       
		},     
		});  
		</script>