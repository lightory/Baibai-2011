<div id="body">
	<div class="inner">
		<div class="content">
			<div class="content_box" id="config" style="padding:20px;">
				<h3>申请借阅<?php if($book){ echo "《{$book->name}》";} ?></h3>
				
				<?php if(empty($book)): ?>
					<form class="profile" method="POST" action="<?php echo site_url("borrow/getisbn_do/borrow/") ?>">
						<?php if ($this->session->flashdata('error')){ ?>
						<p id="error" class="nolabel"><?php echo $this->session->flashdata('error'); ?></p>
						<?php } ?>
						<p>
							<label for="isbn">ISBN 号：</label>
							<input type="text" class="text" name="isbn" id="isbn" />
							<input type="submit" value="下一步" class="button" />
						</p>
						<p id="error" class="nolabel" style="display:none;">请输入正确的ISBN 号码</p>
						<p>
							<span class="nolabel">不知道 ISBN？<a href="http://book.douban.com/" target="_blonk">去豆瓣搜索书名查找 ISBN 吧</a></span>
						</p>
					</form>
					<script type="text/javascript">
					var prompt = '请输入想借书籍的ISBN号码';
					$('form input#isbn').val(prompt);
					$('form input#isbn').focus(function(){
					  if ( $(this).val() == prompt ){
					    $(this).val('');
					  }
					});
					$('form input#isbn').blur(function(){
					  if ( $(this).val() == '' ){
					    $(this).val(prompt);
					  }
					});
					
					$("form").bind("submit", function() {
						var isbn = $('#isbn').val().replace(/[ ]/g,"");
						if ( (isbn.match(/\D/)!=null) || ((isbn.length!=13) && (isbn.length!=10)) ){
							$('p#error', this).show();
							return false;
						}
					});
					</script>
				<?php else: ?>
					<form class="profile" method="POST" action="<?php echo site_url("borrow/request_do/{$book->id}/"); ?>">
						<?php if(sizeof($readers)): ?>
						<p>
							<label for="scope">拥有者：</label>
							<?php if(empty($myRequest)): ?>
							<input type="radio" name="scope" value="0" checked /> 全部
							<input type="radio" name="scope" value="1" style="margin-left:10px;"> 定向借阅
							<?php else: ?>
							<input type="radio" name="scope" value="0" <?php if($myRequest->allowUserIds=='ALL'): ?>checked<?php endif; ?> /> 全部
							<input type="radio" name="scope" value="1" <?php if($myRequest->allowUserIds!='ALL'): ?>checked<?php endif; ?> style="margin-left:10px;"> 定向借阅
							<?php endif; ?>
						</p>
						<style>
						<?php if(empty($myRequest) || $myRequest->allowUserIds=='ALL'): ?>
						.readers { display:none; }
						<?php endif; ?>
						.userinfo { float:left; margin-right:10px; margin-bottom:20px; width:160px; }
						.userinfo input { vertical-align:top; margin-right:6px; float:left; }
						.userinfo img.avatar { float:left; margin-right:10px; }
						.profile .userinfo p { margin-bottom:6px; line-height:1; margin-left:73px; }
						</style>
						<div class="nolabel readers">
							<?php
							if ($myRequest && $myRequest->allowUserIds!='ALL') {
								$allowUserIds = explode(',', $myRequest->allowUserIds);
							}
							$i = 0;
							foreach($readers as $reader): 
								$i++;
								$reader = $this->MUser->getByUid($reader->readerId);
								$reader->avatar = getGravatar($reader->email, 30);
								$reader->address = $this->MAddress->getDefaultByUid($reader->uid);
							?>
							<div class="userinfo <?php if($i%3==1){ echo 'clearfix'; } ?>">
								<input type="checkbox" name="scope2[]" value="<?php echo $reader->uid; ?>" <?php if($myRequest && $myRequest->allowUserIds!='ALL' && in_array($reader->uid, $allowUserIds)): ?>checked<?php endif; ?> />
								<img src="<?php echo $reader->avatar; ?>" class="avatar avatar-45">
								<p><a href="<?php echo $this->MUser->getUrl($reader->uid); ?>" target="_blank"><?php echo $reader->nickname; ?></a></p>
								<p><?php echo "{$reader->address->province} · {$reader->address->city}"; ?></p>
							</div>
							<?php endforeach; ?>
							<div class="clearfix"></div>
						</div>
						<?php endif; ?>
						<p>
							<label for="message">留言：</label>
							<input type="text" class="text" name="message" placeholder="说说你为什么想借这本书吧" <?php if($myRequest): ?>value="<?php echo $myRequest->message; ?>"<?php endif; ?> />
						</p>
						<p id="error" class="nolabel" style="display:none;">留言内容需要至少 10 个汉字</p>
						<p>
							<label>我的地址：</label>
							<input type="text"class="text" value="<?php echo "{$me->address->province} · {$me->address->city} · {$me->address->district} · {$me->address->address}"; ?>" style="width:300px;" disabled />
							<a href="<?php echo site_url("setting/address/"); ?>" target="_blank">修改</a>
						</p>
						<p>
							<label>我的手机：</label>
							<input type="text" class="text" value="<?php echo $me->mobile; ?>" disabled />
							<a href="<?php echo site_url("setting/profile/"); ?>" target="_blank">修改</a>
						</p>
						<?php if(empty($myRequest) && count($myLinkedProviders)>0): ?>
						<p>
							<label>同步到：</label>
							<?php if(in_array('douban', $myLinkedProviders)): ?>
							<input type="checkbox" name="douban" value="true" checked>豆瓣
							<?php endif; ?>
							<?php if(in_array('tsina', $myLinkedProviders)): ?>
							<input type="checkbox" name="tsina" value="true" checked style="margin-left:6px;">新浪微博
							<?php endif; ?>
							<?php if(in_array('tqq', $myLinkedProviders)): ?>
							<input type="checkbox" name="tqq" value="true" checked style="margin-left:6px;">腾讯微博
							<?php endif; ?>
						</p>
						<?php endif; ?>
						<p>
							<input type="submit" class="button nolabel" value="确定" />
						</p>
					</form>
				<?php endif; ?>
			</div>
		</div>
		<div class="sidebar">
			<?php 
				if (!empty($book)) $this->load->view("sidebar/components/book_info.php", array("book" => $book));
				$this->load->view("sidebar/components/sns_links.php"); 
				$this->load->view("sidebar/components/ext_links.php"); 
			?>	
		</div>
		<div class="clearfix"></div>
	</div>
</div>

<script type="text/javascript">
$('input[name=scope]').change(function(){
	if ($("input['name=scope'][checked]").val() == 1) {
		$("div.readers").slideDown('normal');
	} else {
		$("div.readers").slideUp('normal');
	}
});

$("form.profile").submit(function() {
	if ($("input[name='message']").val().length < 10) {
	    $("#error", this).show();
	    return false;
	}
});
</script>