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
          <a href="<?php echo site_url("group/$group->url/edit/"); ?>" class="contentBox_tabNav_tab first">基本信息</a><!--
          --><a href="<?php echo site_url("group/$group->url/editicon"); ?>" class="contentBox_tabNav_tab current">小组图标</a>
        </div>
        <form class="profile" method="post" action="<?php echo site_url("group/$group->url/editicon_do/"); ?>" enctype="multipart/form-data" style="padding:30px 20px;">
					<?php if ($this->session->flashdata('error')){ ?>
          <p id="error" class="nolabel"><?php echo $this->session->flashdata('error'); ?></p>
          <?php } ?>
					<p>
						<label style="visibility:hidden;">当前图标</label>
						<img src="<?php echo getGroupIcon($group); ?>" />
					</p>
					<p>
						<label for="groupIcon">新图标：</label>
						<input type="file" name="groupIcon" id="groupIcon" style="border:none;" />
					</p>
					<p>
						<input type="submit" value="更新小组图标" class="nolabel button">
					</p>
				</form>
      </div>
		</div>