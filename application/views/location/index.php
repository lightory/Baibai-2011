	<div id="body">
		<div class="inner">
			<div class="content">
				<div class="content_box">
			  	<div class="contentBox_tabNav">
			  		<a href="<?php echo site_url('book/'); ?>" class="contentBox_tabNav_tab first">全站书架</a><!--
					--><a href="<?php echo site_url("location/{$location->url}/"); ?>" class="contentBox_tabNav_tab current"><?php echo $location->name; ?>书架</a><!--
					--><a href="<?php echo site_url("book/wanted/"); ?>" class="contentBox_tabNav_tab last">大家想借</a>
			  	</div>
				<div class="lib_contentModule book_list3 brd-bottom">
          	<ul>
            	<?php $i=0; ?>
	            <?php foreach ($lastestAvailableBooks as $book): ?>
	            <?php $i++; ?>
	            <li <?php if(0 == $i%5){ echo 'style="margin-right:0;"';} ?>>
	              <a href="<?php echo site_url("book/subject/$book->id/"); ?>" title="<?php echo $book->name; ?>">
	                <img src="<?php echo $book->pic ? $book->pic : site_url('upload/book/cover/').'default.jpg'; ?>" class="book_cover" />
	                <span class="book_name"><?php echo $book->name; ?></span>
	              </a>
	            </li>
	            <?php endforeach; ?>
	          </ul>
		  			<p class="clearfix"></p>
						<?php echo $this->pagination->create_links(); ?>
        	</div>
				</div>
				<div class="content_box">
					<table class="group_main_topics clearfix" style="border:none; margin-top:0; padding:0 15px 10px;">
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
						<tr>
							<td colspan="4">
								<a href="<?php echo site_url("group/{$location->url}/"); ?>">查看更多...</a>
							</td>
						</tr>
          </table>
				</div>
				<div class="lib_contentModule book_list3 content_box">
        		<?php $this->load->view("book/components/slide_books.php", $slideBooks); ?>
      	</div>
			</div>
			<div class="sidebar">
				<div class="content_box user_list">
					<h3>捐赠排行榜</h3>
					<ul>
						<?php foreach($users as $user): ?>
						<li>
							<img src="<?php echo getGravatar($user->email, 35); ?>" class="avatar avatar-35" />
							<a class="username" href="<?php echo $this->MUser->getUrl($user->uid); ?>"><?php echo $user->nickname; ?></a>
							<?php $address = $this->MAddress->getDefaultByUid($user->uid); ?>
							<span class="district"><?php echo "$address->province $address->city"; ?></span>
							<span class="donate_count"><?php echo $user->count; ?></span>
						</li>
						<?php endforeach; ?>
					</ul>
				</div>
				<?php 
					$this->load->view("sidebar/components/sns_links.php"); 
					$this->load->view("sidebar/components/ext_links.php"); 
				?>
			</div>
			<div class="clearfix"></div>
		</div>
	</div>