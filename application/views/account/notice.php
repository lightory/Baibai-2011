  <div id="body">
    <div class="inner">
      <div class="content">
        <div class="content_box">
          <div class="contentBox_tabNav">
            <a class="contentBox_tabNav_tab first" href="<?php echo site_url("user/$userId/"); ?>">我的书架</a>
            <a class="contentBox_tabNav_tab" href="<?php echo site_url("user/$userId/wishes/"); ?>">想借</a>
            <a class="contentBox_tabNav_tab" href="<?php echo site_url("user/$userId/onhand/"); ?>">
              已借
              <?php if ($onhandNoticeCount>0): ?>
              <span class="noticeCount"><?php echo $onhandNoticeCount; ?></span>
              <?php endif; ?>
            </a>
            <a class="contentBox_tabNav_tab" href="<?php echo site_url("user/$userId/available/"); ?>">
              可借
              <?php if ($availableNoticeCount>0): ?>
              <span class="noticeCount"><?php echo $availableNoticeCount; ?></span>
              <?php endif; ?>
            </a>
            <a class="contentBox_tabNav_tab current last" href="<?php echo site_url('account/notice/'); ?>">动态</a>
          </div>
					<div class="notice contents">
					   <?php foreach ($messages as $message): ?>
				     <div class="message">
				          <img src="<?php echo $message['avatar'] ?>" class="avatar" />
				          <?php if(isset($message['actionName'])): ?>
				          <a href="<?php echo $message['actionUrl'] ?>" onclick="return false;"  class="message_act fancybox button2"><?php echo $message['actionName'] ?></a>
				          <?php endif; ?>
				          <p class="message_content"><?php echo $message['content'] ?></p>
				          <p class="message_time"><?php echo $message['time'] ?></p>
				     </div>
				     <?php endforeach; ?>
					</div>		
				</div>
      </div>
      <?php echo $this->Common->sidebar($userId, TRUE); ?>
      <div class="clearfix"></div>
    </div>
  </div>