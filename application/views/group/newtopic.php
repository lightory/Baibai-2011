  <div id="body">
    <div class="inner">
			<div class="breadcrumbs">
				<a href="<?php echo site_url("group/"); ?>">小组</a>
				<span style="font-size:12px;">&gt;</span>
        新主题
			</div>
      <div class="content_box content newGroupTopic">
        <form class="postForm newGroupTopic_postForm" method="POST" action="<?php echo site_url('group/newtopic_do/'); ?>">
          <input type="hidden" name="groupId" value="<?php echo $group->id; ?>" />
          <input type="text" name="postTitle" class="postForm_postTitle newGroupTopic_postForm_postTitle" />
          <textarea name="postContent" class="newGroupTopic_postForm_postContent"></textarea>
          <input type="submit" class="button" value="发布" />
        </form>
      </div>