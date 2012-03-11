  <div id="body">
    <div class="inner">
      <div class="content_box content group">
        <div class="group_bread brd-bottom">
          <a href="<?php echo site_url('group'); ?>">小组</a>
          <ul class="group_myListNav">
            <li <?php if($type=='latest'): ?>class="current"<?php endif; ?>><a href="<?php echo site_url("group/"); ?>">最新话题</a></li>
            <li <?php if($type=='mytopics'): ?>class="current"<?php endif; ?>><a href="<?php echo site_url("group/mytopics/"); ?>">我发起的话题</a></li>
            <li <?php if($type=='myreplies'): ?>class="current"<?php endif; ?>><a href="<?php echo site_url("group/myreplies/"); ?>">我回应的话题</a></li>
          </ul>
        </div>
        <div class="group_main">
		<a href="<?php echo site_url("group/1/newtopic/"); ?>" class="button" style="display:inline-block;">发表新主题</a>
          <?php if(sizeof($topics)>0): ?>
          <table class="group_main_topics clearfix" style="border:none;">
            <tr class="group_main_topics_th">
              <th style="width:300px;">主题</th>
              <th style="widhth:90px; text-align:center;">作者</th>
              <th style="width:60px; text-align:center;">回复</th>
              <th style="text-align:center;">更新</th>
            </tr>
            <?php foreach($topics as $topic): ?>
            <?php $topic->author = $this->MUser->getByUid($topic->userId); ?>
            <?php $topic->replysCount = $this->MGroupTopic->getTopicReplysCount($topic->id); ?>
            <tr>
              <td><a href="<?php echo site_url("group/topic/$topic->id/"); ?>" title="<?php echo $topic->title; ?>"><?php echo utf_substr($topic->title,46); ?></a></td>
              <td style="text-align:center;"><a href="<?php echo $this->MUser->getUrl($topic->author->uid); ?>"><?php echo $topic->author->nickname; ?></a></td>
              <td style="text-align:center;"><?php echo $topic->replysCount; ?></td>
              <td style="text-align:center;"><?php echo toRelativeTime($topic->activeTime); ?></td>
            </tr>
            <?php endforeach; ?>
          </table>
          <?php echo $this->pagination->create_links(); ?>
          <?php endif; ?>
        </div>
      </div>