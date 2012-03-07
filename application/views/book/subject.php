	<div id="body">
		<div class="inner">
			<div class="content_box content">
			  <div class="contentBox_nav brd-bottom">
			     <p class="contentBox_nav_bread">
			       <a href="<?php echo site_url("book/"); ?>">书架</a> > 
			       <span><?php echo utf_substr($book->name, 20); ?></span>
			     </p>
			     <p class="contentBox_nav_share">
			        <span>分享到</span>
			        <a title="新浪微博" target="_blank" href="javascript:void((function(s,d,e){try{}catch(e){}var f='http://v.t.sina.com.cn/share/share.php?',u=d.location.href,p=['url=',e(u),'&title=', e(d.title), '&pic=<?php echo $book->pic ? $book->pic : site_url('upload/book/cover/').'default.jpg'; ?>', '&appkey=1470536138'].join('');function a(){if(!window.open([f,p].join(''),'mb',['toolbar=0,status=0,resizable=1,width=620,height=450,left=',(s.width-620)/2,',top=',(s.height-450)/2].join('')))u.href=[f,p].join('');};if(/Firefox/.test(navigator.userAgent)){setTimeout(a,0)}else{a()}})(screen,document,encodeURIComponent));"><img src="<?php echo site_url('include/style/img/').'share_tsina.png'; ?>" /></a>
			        <a title="腾讯微博" target="_blank" href="javascript:void((function(s,d,e){try{}catch(e){}var f='http://v.t.qq.com/share/share.php?',u=d.location.href,p=['url=',e(u),'&title=',e(d.title), '&pic=<?php echo $book->pic ? $book->pic : site_url('upload/book/cover/').'default.jpg'; ?>', '&appkey=1470536138'].join('');function a(){if(!window.open([f,p].join(''),'mb',['toolbar=0,status=0,resizable=1,width=620,height=450,left=',(s.width-620)/2,',top=',(s.height-450)/2].join('')))u.href=[f,p].join('');};if(/Firefox/.test(navigator.userAgent)){setTimeout(a,0)}else{a()}})(screen,document,encodeURIComponent));"><img src="<?php echo site_url('include/style/img/').'share_tqq.png'; ?>" /></a>
			        <a title="豆瓣" target="_blank" href="javascript:void(function(){var d=document,e=encodeURIComponent,s1=window.getSelection,s2=d.getSelection,s3=d.selection,s=s1?s1():s2?s2():s3?s3.createRange().text:'',r='http://www.douban.com/recommend/?url='+e(d.location.href)+'&title='+e(d.title)+'&sel='+e(s)+'&v=1',x=function(){if(!window.open(r,'douban','toolbar=0,resizable=1,scrollbars=yes,status=1,width=450,height=330'))location.href=r+'&r=1'};if(/Firefox/.test(navigator.userAgent)){setTimeout(x,0)}else{x()}})();"><img src="<?php echo site_url('include/style/img/').'share_douban.png'; ?>" /></a>
			        <a title="人人网" target="_blank" href="javascript:void((function(s,d,e){if(/renren\.com/.test(d.location))return;var f='http://share.renren.com/share/buttonshare?link=',u=d.location,l=d.title,p=[e(u),'&title=',e(l)].join('');function%20a(){if(!window.open([f,p].join(''),'xnshare',['toolbar=0,status=0,resizable=1,width=626,height=436,left=',(s.width-626)/2,',top=',(s.height-436)/2].join('')))u.href=[f,p].join('');};if(/Firefox/.test(navigator.userAgent))setTimeout(a,0);else%20a();})(screen,document,encodeURIComponent));"><img src="<?php echo site_url('include/style/img/').'share_renren.png'; ?>" /></a>
			     </p>
			  </div>
				<div class="bookBox">
					<img class="bookBox_cover" src="<?php echo $book->pic ? $book->pic : site_url('upload/book/cover/').'default.jpg'; ?>" title="<?php echo $book->name; ?>" />
					<div class="bookBox_info">
						<h3><?php echo $book->name; ?></h3>
						<ul>
							<li>作者：<?php echo $book->author; ?></li>
							<li>出版时间：<?php echo $book->pubdate; ?></li>
							<li>上架时间：<?php echo mdate('%Y-%m-%d', $book->time); ?></li>
						</ul>
					</div>
					<div class="bookBox_count">
					   <span>
               <label><?php echo $needs; ?></label>
               <br/>想借
             </span>
					   <span>
               <label><?php echo $availableStockNumber; ?></label>
               <br/>当前库存
             </span>
             <span class="end">
               <label><?php echo $stockNumber; ?></label>
               <br/>总库存
             </span>
					</div>
					<div class="bookBox_act">
						<?php if ($userStatue == 'none'): ?>
							<a href="<?php echo site_url("borrow/request/{$book->id}/"); ?>" class="iwantborrow button">想借</a>
							<a href="<?php echo site_url("book/donate/$book->isbn/"); ?>" class="donate fancybox button">捐赠</a>
						<?php elseif ($userStatue == 'wanter'): ?>
							我想借  
							<a href="<?php echo site_url("borrow/request/{$book->id}/"); ?>">编辑</a>
							<a href="<?php echo site_url("borrow/request_cancel_do/$book->id/"); ?>">取消</a>
						<?php elseif ($userStatue == 'borrower'): ?>
							我正在借
						<?php elseif ($userStatue == 'reader'): ?>
							<?php if ($stock->statue == 'available'): ?>
							我手上有一本
							<a href="<?php echo site_url("mine/available/")."#book-{$book->id}"; ?>">借出</a>
							   <?php if(isset($userStatue2)): ?>
                 <a href="<?php echo site_url("book/donate_delete_do/$book->id/"); ?>">取消</a>
                 <?php endif; ?>
							<?php elseif ($stock->statue == 'reading'): ?>
							我正在读
							<a href="<?php echo site_url("book/returnbook/$stock->id/"); ?>" class="fancybox">读完了～</a>
							<?php elseif ($stock->statue == 'transfor'): ?>
							我手上有一本，正在借给别人
							<?php endif; ?>
						<?php elseif ($userStatue == 'owner'): ?>
							我捐赠过
						<?php endif; ?>
					</div>
				</div>
				<div style="margin-top:20px; padding:0 15px;">
  				<h3 class="mb10">本书简介</h3>
          <p class="summary" style="line-height:20px; margin-bottom:10px; color:#8A8587;"><?php echo $book->summary; ?></p>
        </div>
        <div class="book_list3" style="margin-top:20px; padding:0 15px;">
            <h3 style="margin-bottom:10px;">相关书籍</h3>
            <ul>
							<?php $i=0; ?>
              <?php foreach($relatedBooks as $relatedBook): ?>
							<?php $i++; ?>
              <li <?php if(0 == $i%5){ echo 'style="margin-right:0;"';} ?>>
                <a href="<?php echo site_url("book/subject/$relatedBook->id/"); ?>" title="<?php echo $relatedBook->name; ?>">
                  <img src="<?php echo $relatedBook->pic; ?>" class="book_cover" />
                  <span class="book_name"><?php echo utf_substr($relatedBook->name,12);if(utf_strlen($relatedBook->name)>12){echo '...';} ?></span>
                </a>
              </li>
              <?php endforeach; ?>
            </ul>
        </div>
				<div style="margin-top:20px; padding:0 15px 15px;">
            <h3>库存信息</h3>
            <div class="stocksInfoTable">
                <p class="stocksInfoTable_header">
                    <span style="width:96px; text-align:left;">捐赠者</span>
                    <span style="width:96px; text-align:left;">持有者</span>
                    <span style="width:100px; text-align:left;">所在地</span>
                    <span style="width:90px;">到达城市</span>
                    <span style="width:90px;">传递人数</span>
                    <span style="width:60px;"></span>
                </p>
                <?php foreach($stocks as $stock): 
                    $owner = $this->MUser->getByUid($stock->ownerId);
                    $reader = $this->MUser->getByUid($stock->readerId);
                    $reader->address = $this->MAddress->getDefaultByUid($stock->readerId);
                    $readersCount = $this->MStock->getReadersCount($stock->id);
                    $readersCityCount = $this->MStock->getReadersCityCount($stock->id);
                ?>
                <p>
                    <span style="width:96px; text-align:left;"><a href="<?php echo $this->MUser->getUrl($owner->uid); ?>"><?php echo $owner->nickname; ?></a></span>
                    <span style="width:96px; text-align:left;"><a href="<?php echo $this->MUser->getUrl($reader->uid); ?>"><?php echo $reader->nickname; ?></a></span>
                    <span style="width:100px; text-align:left;"><?php echo $reader->address->province; ?></span>
                    <span style="width:90px;"><?php echo $readersCityCount; ?></span>
                    <span style="width:90px;"><?php echo $readersCount; ?></span>
										<span style="width:60px;"><?php if($readersCount>0): ?><a href="<?php echo site_url("book/stock/$stock->id/"); ?>">查看详情</a><?php endif; ?></span>
                </p>
                <?php endforeach; ?>
                <div class="body_postMark" style="top:-30px; right:-20px;"></div>
                <div class="body_stick" style="top:-6px; left:-6px;"></div>
                <div class="body_stick" style="bottom:-6px; right:-6px;"></div>
            </div>
        </div>
			</div>
			<div class="sidebar">
			  <div class="content_box book_info_box">
			     <h3 class="mb10">其它信息</h3>
           <ul>
              <li>ISBN：<?php echo $book->isbn; ?></li>
              <li>出版社：<?php echo $book->publisher; ?></li>
              <li>出版年：<?php echo $book->pubdate; ?></li>
              <li>豆瓣链接：<a href="http://book.douban.com/subject/<?php echo $book->doubanId; ?>/" target="_blank"><?php echo $book->doubanId; ?></a></li>
           </ul>
			  </div>
			  <div class="content_box" style="padding:15px;">
			     <h3 class="mb10">标签 <?php if($this->session->userdata('uid')):?><a id="showTagForm" style="font-size:12px; font-weight:normal;" href="javascript:void(0);">(+标签)</a><?php endif; ?></h3>
			     <div style="line-height:20px;">
    			     <?php 
    			     $i=0;
               $number = sizeof($tags);
    			     foreach($tags as $tag): 
    			       $i++;
    			     ?>
    			     <a class="clickableTag" href="<?php echo site_url("book/tag/$tag->tag/"); ?>"><?php echo $tag->tag; ?></a>
    			     <?php if($i<$number): ?>&nbsp;&nbsp;·&nbsp;&nbsp;<?php endif; ?>
    			     <?php endforeach; ?>
    			     <?php if($this->session->userdata('uid')):?>
               <form id="tagForm" method="POST" action="<?php echo site_url("book/tagging_do/$book->id/"); ?>" style="margin-top:10px; display:none;">
                 <h3 class="mb10">我常用的标签</h3>
                 <?php 
                 $i=0;
                 $number = sizeof($myTags);
                 foreach($myTags as $tag): 
                   $i++;
                 ?>
                 <a class="clickableTag" href="<?php echo site_url("book/all/tag/$tag->tag/"); ?>"><?php echo $tag->tag; ?></a>
                 <?php if($i<$number): ?>&nbsp;&nbsp;·&nbsp;&nbsp;<?php endif; ?>
                 <?php endforeach; ?>
                 <input type="hidden" name="oldTags" value="<?php echo $myTagsOfBook; ?>"  />
                 <input type="text" name="newTags" id="newTags" value="<?php echo $myTagsOfBook; ?>" placeholder="您可以直接点击添加上面的标签" style="color:#C7C7C7; width:95%; line-height:24px; background:#F5F5F5; border:1px solid #EBEBEB; padding:0 5px; margin:6px 0;" />
                 <input type="submit" value="确认" class="button" />
                 <span style="margin-left:6px; color:#8c8c8c;">多个标签用空格分隔</span>
               </form>
               <?php endif; ?>
			     </div>
			  </div>
        		<?php 
					$this->load->view("sidebar/components/sns_links.php"); 
					$this->load->view("sidebar/components/ext_links.php"); 
				?>
			</div>
			<div class="clearfix"></div>
		</div>
	</div>
<script type="text/javascript">
$summary = $('p.summary');
var summary = $summary.html();
if (summary.length>150){
	var shortSummary = summary.substring(0,150) + '...';
	shortSummary += '<a href="javascript:showAll();">(更多)</a>';
	summary += '<a href="javascript:showLess();">(收起)</a>';

	var showAll = function(){
		$summary.html(summary);
	}

	var showLess = function(){
		$summary.html(shortSummary);
	}

	$('p.summary').html(shortSummary);
}


$(document).ready(function(){
	var hash = location.hash;
	if(hash == '#borrow'){
		$('div.bookBox_act a.iwantborrow').click();
	} else if(hash == '#donate'){
		$('div.bookBox_act a.donate').click();
	}
});

var showTagForm = false;
$('#showTagForm').click(function(){
  if (showTagForm==false){
    $('#tagForm').show();
    showTagForm = true;
  } else{
    $('#tagForm').hide();
    showTagForm = false;
  }
});

$('.clickableTag').click(function(){
  if(showTagForm==false){
    return;
  }

  var tag = $(this).text();
  var newTags = $('#newTags').val();
  if (newTags.indexOf(tag)==-1){
    $('#newTags').val(newTags+' '+tag);
  }
  return false;
});
</script>