  <div id="body">
    <div class="inner">
			<div class="breadcrumbs">
				<a href="<?php echo site_url("group/"); ?>">小组</a>
				<span style="font-size:12px;">&gt;</span>
				<?php echo $topic->title; ?>
			</div>
      <div class="content_box content groupTopic">
        <div class="groupTopic_main">
          <img src="<?php echo $topic->author->avatar; ?>" class="avatar avatar-42" />
          <h2><?php echo $topic->title; ?></h2>
          <p class="groupTopic_main_meta">
            <a href="<?php echo $this->MUser->getUrl($topic->author->uid); ?>"><?php echo $topic->author->nickname.'('.$topic->author->address->province.')'; ?></a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
            <span class="groupTopic_main_meta_time"><?php echo mdate('%Y-%m-%d %H:%i', $topic->time); ?></span>
          </p>
          <p class="groupTopic_postContent"><?php echo preg_replace('/(http:\/\/[\w\/.?=-]*)/', '<a href="\1" target="_blank">\1</a>', $topic->content); ?></p>
        </div>
        <ul class="groupTopic_main_replys">
          <?php foreach($topic->replys as $reply): ?>
          <?php $reply->author = $this->MUser->getByUid($reply->userId); ?>
          <?php $reply->author->avatar = getGravatar( $reply->author->email, 45); ?>
          <?php $reply->author->address = $this->MAddress->getDefaultByUid($reply->author->uid); ?>
          <li class="groupTopic_main_reply">
            <img src="<?php echo $reply->author->avatar; ?>" class="avatar avatar-42" />
            <p class="groupTopic_main_meta">
              <a href="<?php echo $this->MUser->getUrl($reply->author->uid); ?>"><?php echo $reply->author->nickname.'('.$reply->author->address->province.')'; ?></a>&nbsp;&nbsp;说：
            </p>
            <p class="groupTopic_postContent"><!--
              --><?php echo preg_replace('/(http:\/\/[\w\/.?=-]*)/', '<a href="\1" target="_blank">\1</a>', $reply->content); ?>
              <span class="groupTopic_postContent_time"><?php echo mdate('%Y-%m-%d %H:%i', $reply->time); ?></span>
            </p>
          </li>
          <?php endforeach; ?>
          <?php if($group->id==1 || $group->type=='location' || $group->userType!=''):?>
          <li class="groupTopic_main_reply">
            <img src="<?php echo $user->avatar; ?>" class="avatar avatar-42" />
            <p class="groupTopic_main_meta">
              <a href="<?php echo $this->MUser->getUrl($user->uid); ?>"><?php echo $user->nickname.'('.$user->address->province.')'; ?></a>&nbsp;&nbsp;说：
            </p>
            <form class="groupTopic_postContent postForm" method="POST" action="<?php echo site_url('group/reply_do/'); ?>">
              <input type="hidden" name="topicId" value="<?php echo $topic->id; ?>" />
              <textarea name="postContent"></textarea>
              <input type="submit" class="button" value="回复" />
            </form>
          </li>
          <?php endif; ?>
        </ul>
      </div>