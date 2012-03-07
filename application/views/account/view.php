  <div id="body">
    <div class="inner">
      <div class="content">
        <div class="globalNotices">
		<?php if ($isSelf): ?>
			<?php if(isset($statueUpdated)): ?>
				<div class="message">
					<p class="message_content">恭喜您，已经激活帐号，防止书籍下架。</p>
				</div>
			<?php endif; ?>
			
			<?php if($user->borrowQuote<1): ?>
				<div class="message">
					<?php if($availableNoticeCount>0): ?>
					<a href="<?php echo site_url("user/$user->username/available/"); ?>" class="message_act button">查看</a>
					<p class="message_content">没有摆摆券了？<?php echo $availableNoticeCount; ?> 个人正好想借您的书，去看一眼吧～</p>
					<?php else: ?>
					<a href="<?php echo site_url("book/wanted/"); ?>" class="message_act button">查看</a>
					<p class="message_content">没有摆摆券了？去看看大家想借的书吧，马上就能获得摆摆券~</p>
					<?php endif; ?>
				</div>
			<?php endif; ?>
	
          <?php foreach ($notifications as $notification): ?>
          <div class="message">
            <?php if( isset($notification->avatar) && $notification->avatar ): ?>
            <img src="<?php echo $notification->avatar; ?>" class="avatar" />
            <?php endif; ?>
            <a href="<?php echo site_url("account/notification_read_do/$notification->id/"); ?>" class="message_act button">隐藏</a>
            <?php if( isset($notification->actionName) && ($notification->actionName) ): ?>
            <a href="<?php echo $notification->actionUrl; ?>" onclick="return false;"  class="message_act fancybox button" style="margin-right:6px;"><?php echo $notification->actionName; ?></a>
            <?php endif; ?>
            <p class="message_content"><?php echo $notification->content; ?></p>
          </div>
          <?php endforeach; ?>
          <?php endif; ?>
        
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
        
        <div class="content_box">
					<div class="contentBox_tabNav">
						<?php if($isSelf): ?>
            <a class="contentBox_tabNav_tab first current" href="<?php echo site_url("user/$user->username/"); ?>">我的书架</a><!--
            <?php else: ?>
            <a class="contentBox_tabNav_tab first current" href="<?php echo site_url("user/$user->username/"); ?>">TA的书架</a><!--
            <?php endif; ?>
						<?php if ($isSelf):  ?>
            --><a class="contentBox_tabNav_tab" href="<?php echo site_url("user/$user->username/wishes/"); ?>">想借</a><!--
            --><a class="contentBox_tabNav_tab" href="<?php echo site_url("user/$user->username/onhand/"); ?>">
              已借
              <?php if ($onhandNoticeCount>0): ?>
              <span class="noticeCount"><?php echo $onhandNoticeCount; ?></span>
              <?php endif; ?>
            </a><!--
            --><a class="contentBox_tabNav_tab" href="<?php echo site_url("user/$user->username/available/"); ?>">
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
            --><a class="contentBox_tabNav_tab" href="<?php echo site_url("user/$user->username/available/"); ?>">可借</a>
            <?php endif; ?>
          </div>
          <div class="home contents">
						<?php
              $count1 = sizeof($wishBooks);
              $count2 = sizeof($onhandBooks);
              $count3 = sizeof($availableBooks);
              $wishesShow = ($count3>0)||($count2>0)||($count1>0);
              $onhandShow = ($count3>0)||($count2>0);
              $availableShow = ($count3>0);
            ?>
            <?php if(!$isSelf || $wishesShow): ?>
            <div class="book_list1">
              <h3><?php if($isSelf){ echo '我'; } else{ echo $user->nickname; } ?>想借的</h3>
              <a href="<?php echo site_url("user/$user->username/wishes/"); ?>" class="more">查看全部<?php echo $wishesCount; ?>本</a>
              
              <?php if( $user->borrowQuote>0 || !$isSelf ): ?>
              <ul>
                <?php $i=0; ?>
                <?php foreach($wishBooks as $book): ?>
                <?php $i++; ?>
                <li <?php if(6==$i){ echo 'style="margin:0;"';} ?>>
                  <a href="<?php echo site_url("book/subject/$book->id/"); ?>">
                    <img src="<?php echo $book->pic ? $book->pic : site_url('upload/book/cover/').'default.jpg'; ?>" class="book_cover" title="<?php echo $book->name; ?>" />
                  </a>
                </li>
                <?php endforeach; ?>
              </ul>
              <?php else: ?>
              <div class="guide">
                <p>您当前摆摆券为0，无法继续借书。<a href="<?php echo site_url('about/faq/').'#borrowQuote'; ?>">如何增加?</a></p>
              </div>
              <?php endif; ?>
              
              <?php // 如果想借的书为零
                if ($count1==0): 
              ?>
              <div class="guide">
                <p>还没有想借的书？去 <a href="<?php echo site_url('book/'); ?>">书架</a> 看看吧~</p>
              </div>
              <?php // END 如果想借的书为零
                endif; 
              ?>
              <div class="clearfix"></div>
            </div>
            <?php else: ?>
            <div class="book_list1">
              <h3><?php if($isSelf){ echo '我'; } else{ echo $user->nickname; } ?>想借的</h3>
              <div class="guide">
                <p>还没有想借的书？去 <a href="<?php echo site_url('book/'); ?>">书架</a> 看看吧~</p>
              </div>
            </div>
            <?php endif; ?>
            
            <?php if(!$isSelf || $onhandShow): ?>
            <div class="book_list1">
              <h3><?php if($isSelf){ echo '我'; } else{ echo $user->nickname; } ?>已借的</h3>
              <a href="<?php echo site_url("user/$user->username/onhand/"); ?>" class="more">查看全部<?php echo $onhandCount; ?>本</a>
              <ul>
                <?php $i=0; ?>
                <?php foreach($onhandBooks as $book): ?>
                <?php $i++; ?>
                <li <?php if(6==$i){ echo 'style="margin:0;"';} ?>>
                  <a href="<?php echo site_url("book/subject/$book->id/"); ?>">
                    <img src="<?php echo $book->pic ? $book->pic : site_url('upload/book/cover/').'default.jpg'; ?>" class="book_cover" title="<?php echo $book->name; ?>" />
                  </a>
                </li>
                <?php endforeach; ?>
              </ul>
              <?php if ($count2==0): ?>
              <div class="guide">
                <p>当您成功借到一本书时，就会出现在这里。</p>
              </div>
              <?php endif; ?>
              <div class="clearfix"></div>
            </div>
            <?php else: ?>
            <div class="book_list1">
              <h3><?php if($isSelf){ echo '我'; } else{ echo $user->nickname; } ?>已借的</h3>
              <div class="guide">
                <p>当您成功借到一本书时，就会出现在这里。</p>
              </div>
            </div>
            <?php endif; ?>
            
            <?php if(!$isSelf || $availableShow): ?>
            <div class="book_list1">
              <h3><?php if($isSelf){ echo '我'; } else{ echo $user->nickname; } ?>可借的</h3>
              <a href="<?php echo site_url("user/$user->username/available/"); ?>" class="more">查看全部<?php echo $availableCount; ?>本</a>
              <ul>
                <?php $i=0; ?>
                <?php foreach($availableBooks as $book): ?>
                <?php $i++; ?>
                <li <?php if(6==$i){ echo 'style="margin:0;"';} ?>>
                  <a href="<?php echo site_url("book/subject/$book->id/"); ?>">
                    <img src="<?php echo $book->pic ? $book->pic : site_url('upload/book/cover/').'default.jpg'; ?>" class="book_cover" title="<?php echo $book->name; ?>" />
                  </a>
                </li>
                <?php endforeach; ?>
              </ul>
              <?php if ($count3==0): ?>
              <div class="guide">
                <p>读完了借来的书？继续借给下一位需要的同学吧~</p>
              </div>
              <?php endif; ?>
              <div class="clearfix"></div>
            </div>
            <?php else: ?>
            <div class="book_list1">
              <h3><?php if($isSelf){ echo '我'; } else{ echo $user->nickname; } ?>可借的</h3>
              <div class="guide">
                <p>读完了借来的书？继续借给下一位需要的同学吧~</p>
              </div>
            </div>
            <?php endif; ?>
            
          </div>
        </div>
      </div>
      <?php echo $this->Common->sidebar($user->uid, $isSelf); ?>
      <div class="clearfix"></div>
    </div>
  </div>