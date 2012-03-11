  <div id="body">
    <div class="inner">
		<div class="breadcrumbs">
			<a href="<?php echo site_url("group/"); ?>">小组</a>
			<span style="font-size:12px;">&gt;</span>
			<a href="<?php echo site_url("group/$group->url/"); ?>"><?php echo $group->name; ?></a>
		</div>
		<div class="content">
      	<div class="content_box group">
	        <div class="contentBox_tabNav">
	          <a href="<?php echo site_url("group/$group->url/"); ?>" class="contentBox_tabNav_tab current first">讨论</a>
	        </div>
	        <div class="group_main">
	          <img src="<?php echo getGroupIcon($group); ?>" class="avatar avatar-42 group_main_icon" />
	          <h2 class="group_main_title"><?php echo $group->name; ?></h2>
	          <p class="group_main_nav"></p>
	          <a href="<?php echo site_url("group/$group->id/newtopic/"); ?>" class="button group_main_bigButton">发表新主题</a>
			<p class="group_main_desc"><?php echo nl2br($group->desc); ?></p>
	          <table class="group_main_topics clearfix">
	            <tr class="group_main_topics_th">
	              <th style="width:340px;">主题</th>
	              <th style="widhth:90px; text-align:center;">作者</th>
	              <th style="width:60px; text-align:center;">回复</th>
	              <th style="text-align:center;">更新</th>
	            </tr>
	            <?php foreach($topics as $topic): ?>
	            <?php $topic->author = $this->MUser->getByUid($topic->userId); ?>
	            <?php $topic->replysCount = $this->MGroupTopic->getTopicReplysCount($topic->id); ?>
	            <tr>
	              <td><a href="<?php echo site_url("group/topic/$topic->id/"); ?>" title="<?php echo $topic->title; ?>"><?php echo utf_substr($topic->title,56); ?></a></td>
	              <td style="text-align:center;"><a href="<?php echo $this->MUser->getUrl($topic->author->uid); ?>"><?php echo $topic->author->nickname; ?></a></td>
	              <td style="text-align:center;"><?php echo $topic->replysCount; ?></td>
	              <td style="text-align:center;"><?php echo toRelativeTime($topic->activeTime); ?></td>
	            </tr>
	            <?php endforeach; ?>
	          </table>
	          <?php echo $this->pagination->create_links(); ?>
	        </div>
	      </div>
	</div>