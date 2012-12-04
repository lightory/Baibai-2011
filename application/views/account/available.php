  <div id="body">
    <div class="inner">
      <div class="content">
		<?php if ($isSelf): ?>
		<div class="globalNotices">
          <?php foreach ($notifications as $notification): ?>
          <div class="message">
            <?php if( isset($notification->avatar) ): ?>
            <img src="<?php echo $notification->avatar; ?>" class="avatar" />
            <?php endif; ?>
            <a href="<?php echo site_url("account/notification_read_do/$notification->id/"); ?>" class="message_act button">隐藏</a>
            <?php if( isset($notification->actionName) && ($notification->actionName) ): ?>
            <a href="<?php echo $notification->actionUrl; ?>" onclick="return false;"  class="message_act fancybox button" style="margin-right:6px;"><?php echo $notification->actionName; ?></a>
            <?php endif; ?>
            <p class="message_content"><?php echo $notification->content; ?></p>
          </div>
          <?php endforeach; ?>
          
        
          <?php foreach ($messages as $message):
            if (!isset($message['actionName'])){
              continue;
            }
          ?>
          <div class="message">
            <img src="<?php echo $message['avatar'] ?>" class="avatar" />
            <a href="<?php echo $message['actionUrl'] ?>" onclick="return false;"  class="message_act fancybox button"><?php echo $message['actionName'] ?></a>
            <p class="message_content"><?php echo $message['content'] ?></p>
          </div>
          <?php endforeach; ?>
        </div>
		<?php endif; ?>
	
        <div class="content_box">
          <div class="contentBox_tabNav">
						<?php if($isSelf): ?>
            <a class="contentBox_tabNav_tab first" href="<?php echo site_url("user/$user->username/"); ?>">我的书架</a><!--
            <?php else: ?>
            <a class="contentBox_tabNav_tab first" href="<?php echo site_url("user/$user->username/"); ?>">TA的书架</a><!--
            <?php endif; ?>
						<?php if ($isSelf):  ?>
            --><a class="contentBox_tabNav_tab" href="<?php echo site_url("user/$user->username/wishes/"); ?>">想借</a><!--
            --><a class="contentBox_tabNav_tab" href="<?php echo site_url("user/$user->username/onhand/"); ?>">
              已借
              <?php if ($onhandNoticeCount>0): ?>
              <span class="noticeCount"><?php echo $onhandNoticeCount; ?></span>
              <?php endif; ?>
            </a><!--
            --><a class="contentBox_tabNav_tab current" href="<?php echo site_url("user/$user->username/available/"); ?>">
              可借
              <?php if ($availableNoticeCount>0): ?>
              <span class="noticeCount"><?php echo $availableNoticeCount; ?></span>
              <?php endif; ?>
            </a><!--
            --><a class="contentBox_tabNav_tab last" href="<?php echo site_url('account/notice/'); ?>">
              动态
              <?php if ($messageCount>0): ?>
              <span class="noticeCount"><?php echo $messageCount; ?></span>
              <?php endif; ?>
            </a>
            <?php else: ?>
            --><a class="contentBox_tabNav_tab" href="<?php echo site_url("user/$user->username/wishes/"); ?>">想借</a><!--
            --><a class="contentBox_tabNav_tab" href="<?php echo site_url("user/$user->username/onhand/"); ?>">已借</a><!--
            --><a class="contentBox_tabNav_tab current" href="<?php echo site_url("user/$user->username/available/"); ?>">可借</a>
            <?php endif; ?>
          </div>
					<div id="user_books_list" class="available contents">
					     <ul class="books_list">
					          <?php foreach($books as $book):
					               $inventory = $this->MBook->getInventory($book->id);
					               $realInventory = $this->MBook->getRealInventory($book->id);
					               $needs = $this->MBook->getNeeds($book->id);
					          ?>
					          <li id="book-<?php echo $book->id; ?>" <?php if($isSelf && ($needs>0)): ?>style="background:#FFFBCC;"<?php endif; ?>>
					               <img class="book_cover" src="<?php echo $book->pic ? $book->pic : site_url('upload/book/cover/').'default.jpg'; ?>" />
					               <a class="book_name" href="<?php echo site_url("book/subject/$book->id/"); ?>"><?php echo $book->name; ?></a>
					               <span class="book_time"><?php echo mdate('%Y-%m-%d', $book->finishTime);
					 ?></span>
					               <span class="book_inventory"><?php echo "$realInventory / $inventory";  ?> 库存</span>
					               <span class="book_want"><?php echo $needs ?> 人想借</span>
					               <?php if($isSelf): ?>
					               <?php if($needs>0): ?>
					               <a href="<?php echo site_url("book/messages/all/$book->id/"); ?>" class="book_more" statue="0">查看详细<img src="<?php echo site_url('include/style/img/').'books_list_more.png'; ?>" /></a>
					               <?php endif; ?>
					               <?php else: ?>
					               <a href="<?php echo site_url("borrow/request/$book->id/"); ?>" class="button book_finish">我想借</a>
					               <?php endif; ?>
					          </li>
					          <?php endforeach; ?>
					     </ul>
					</div>
				</div>
      </div>
      <?php echo $this->Common->sidebar($user->uid, $isSelf); ?>
      <div class="clearfix"></div>
    </div>
  </div>
<script type="text/javascript">
$(document).ready(function(){
	var idName;
	if (idName = location.hash){
		$('a.book_more', idName).click();
	}
});
</script>