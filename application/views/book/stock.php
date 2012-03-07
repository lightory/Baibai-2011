	<div id="body">
		<div class="inner">
			<div class="content_box content">
			  <div class="contentBox_nav brd-bottom">
			     <p class="contentBox_nav_bread">
			       <a href="<?php echo site_url("book/"); ?>">书架</a> > 
			       <a href="<?php echo site_url("book/subject/$book->id/"); ?>"><?php echo utf_substr($book->name, 20); ?></a> > 
						 <?php $stockOwner = $this->MUser->getByUid($stock->ownerId); ?>
					   <span><?php echo $stockOwner->nickname.'捐赠'; ?></span>
			     </p>
			     <p class="contentBox_nav_share">
			        <span>分享到</span>
			        <a title="新浪微博" target="_blank" href="javascript:void((function(s,d,e){try{}catch(e){}var f='http://v.t.sina.com.cn/share/share.php?',u=d.location.href,p=['url=',e(u),'&title=', e(d.title), '&pic=<?php echo $book->pic ? $book->pic : site_url('upload/book/cover/').'default.jpg'; ?>', '&appkey=1470536138'].join('');function a(){if(!window.open([f,p].join(''),'mb',['toolbar=0,status=0,resizable=1,width=620,height=450,left=',(s.width-620)/2,',top=',(s.height-450)/2].join('')))u.href=[f,p].join('');};if(/Firefox/.test(navigator.userAgent)){setTimeout(a,0)}else{a()}})(screen,document,encodeURIComponent));"><img src="<?php echo site_url('include/style/img/').'share_tsina.png'; ?>" /></a>
			        <a title="腾讯微博" target="_blank" href="javascript:void((function(s,d,e){try{}catch(e){}var f='http://v.t.qq.com/share/share.php?',u=d.location.href,p=['url=',e(u),'&title=',e(d.title), '&pic=<?php echo $book->pic ? $book->pic : site_url('upload/book/cover/').'default.jpg'; ?>', '&appkey=1470536138'].join('');function a(){if(!window.open([f,p].join(''),'mb',['toolbar=0,status=0,resizable=1,width=620,height=450,left=',(s.width-620)/2,',top=',(s.height-450)/2].join('')))u.href=[f,p].join('');};if(/Firefox/.test(navigator.userAgent)){setTimeout(a,0)}else{a()}})(screen,document,encodeURIComponent));"><img src="<?php echo site_url('include/style/img/').'share_tqq.png'; ?>" /></a>
			        <a title="豆瓣" target="_blank" href="javascript:void(function(){var d=document,e=encodeURIComponent,s1=window.getSelection,s2=d.getSelection,s3=d.selection,s=s1?s1():s2?s2():s3?s3.createRange().text:'',r='http://www.douban.com/recommend/?url='+e(d.location.href)+'&title='+e(d.title)+'&sel='+e(s)+'&v=1',x=function(){if(!window.open(r,'douban','toolbar=0,resizable=1,scrollbars=yes,status=1,width=450,height=330'))location.href=r+'&r=1'};if(/Firefox/.test(navigator.userAgent)){setTimeout(x,0)}else{x()}})();"><img src="<?php echo site_url('include/style/img/').'share_douban.png'; ?>" /></a>
			        <a title="人人网" target="_blank" href="javascript:void((function(s,d,e){if(/renren\.com/.test(d.location))return;var f='http://share.renren.com/share/buttonshare?link=',u=d.location,l=d.title,p=[e(u),'&title=',e(l)].join('');function%20a(){if(!window.open([f,p].join(''),'xnshare',['toolbar=0,status=0,resizable=1,width=626,height=436,left=',(s.width-626)/2,',top=',(s.height-436)/2].join('')))u.href=[f,p].join('');};if(/Firefox/.test(navigator.userAgent))setTimeout(a,0);else%20a();})(screen,document,encodeURIComponent));"><img src="<?php echo site_url('include/style/img/').'share_renren.png'; ?>" /></a>
			     </p>
			  </div>
				<div class="stockMessages">
					<?php $i=0;foreach($borrowRecords as $record):
						$i++;
						$senter = $this->MUser->getByUid($record->senterUid);
						$senter->avatar = getGravatar($senter->email, 45);
						$receiver = $this->MUser->getByUid($record->receiverUid);
					?>
					<div class="stockMessages_message" <?php if($i==1): ?>style="border:none;"<?php endif; ?>>
						<img src="<?php echo $senter->avatar; ?>" class="avatar avatar-45" />
						<p>
							<span class="stockMessages_message_meta">
								<a href="<?php echo $this->MUser->getUrl($senter->uid); ?>"><?php echo "$senter->nickname($record->senterProvince)"; ?></a>
								 借给 
								<a href="<?php echo $this->MUser->getUrl($receiver->uid); ?>"><?php echo "$receiver->nickname($record->receiverProvince)"; ?></a>
							</span>
							<span class="stockMessages_message_content"><?php echo $record->finishMessage; ?></span>
							<span class="stockMessages_message_time"><?php echo $record->receiveTime; ?></span>
						</p>
					</div>
					<?php endforeach; ?>
				</div>
			</div>
			<div class="sidebar">
        		<?php
					$this->load->view("sidebar/components/book_info.php", array("book" => $book));
					$this->load->view("sidebar/components/sns_links.php"); 
					$this->load->view("sidebar/components/ext_links.php"); 
				?>
			</div>
			<div class="clearfix"></div>
		</div>
	</div>