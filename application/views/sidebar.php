<div class="sidebar">
	<div  class="content_box info_box">
		<div class="top">
			<div style="padding:15px 15px 0;">
				<img src="<?php echo $user->avatar; ?>" class="avatar avatar-45" />
				<p class="username">
					<?php echo $user->nickname; ?>
					<?php if($user->isMobileValidate): ?>
					<img src="<?php echo site_url("include/style/img/"); ?>mobile.png" style="position:relative; top:2px;" title="手机已验证" />
					<?php else: ?>
					<?php if($isSelf): ?><a href="<?php echo site_url("setting/mobile/"); ?>" style="background:none;"><?php endif;?>
					<img src="<?php echo site_url("include/style/img/"); ?>mobilenone.png" style="position:relative; top:2px;" title="手机未验证" />
					<?php if($isSelf): ?></a><?php endif;?>
					<?php endif; ?>
				</p>
				<p class="joinTime" style="line-height:18px;"><?php echo  mdate('%Y年%m月%d日加入', $user->joinTime); ?></p>
				<?php if ( $isSelf ): ?>
				<p>
					<a href="<?php echo site_url("setting/profile/"); ?>">设置</a> - 
					<a href="<?php echo site_url("history/"); ?>">历史记录</a>
				</p>
				<?php endif; ?>
			</div>
			<p class="clearfix"></p>
			<div class="infoBox_numbers">
			   <p class="infoBox_numbers_donate">
			     <span class="infoBox_numbers_left">
			       捐赠<br/>
			       <label class="infoBox_numbers_number"><?php echo $donateCount; ?></label>
			     </span>
			     <span class="infoBox_numbers_right">
             传阅<br/>
             <label class="infoBox_numbers_number"><?php echo $donateTransCount; ?></label>
           </span>
			   </p>
			   <p class="infoBox_numbers_borrow">
           <span class="infoBox_numbers_left">
             借入<br/>
             <label class="infoBox_numbers_number"><?php echo $borrowCount; ?></label>
           </span>
           <span class="infoBox_numbers_right">
             归还<br/>
             <label class="infoBox_numbers_number"><?php echo $borrowedCount; ?></label>
           </span>
         </p>
         <p class="clearfix"></p>
			</div>
			<div style="padding:0 15px 15px;">
				<p class="city"><?php if(isset($address->province)) { echo $address->province . ' ' . $address->city;} ?></p>
				<p class="blog"><?php if(isset($user->blog)): ?><a href="<?php echo $user->blog; ?>" target="_blank"><?php echo $user->blog; ?></a><?php endif ?></p>
				<p class="borrowQuote">
					剩余摆摆券：<a href="<?php echo site_url('about/faq/').'#borrowQuote'; ?>"><?php echo $user->borrowQuote; ?></a>
				</p>
				<?php if($this->session->userdata('uid') && !$isSelf): ?>
				<p class="action">
					<?php if(!$isFollow):?>
					<a class="sendMail_button" href="<?php echo site_url("account/follow_do/$user->uid/"); ?>">关注</a>
					<?php endif;?>
					<a class="sendMail_button fancybox" onclick="return false;" href="<?php echo site_url("mail/send/$user->uid/"); ?>">发站内信</a>
					<?php if($isFollow):?>
					<a class="sendMail_button" href="<?php echo site_url("account/unfollow_do/$user->uid/"); ?>">取消关注</a>
					<?php endif;?>
				</p>
				<?php endif; ?>
				<?php if ($isSelf): ?>
				<p class="infoBox_snsIcon">
					<a href="<?php echo site_url('setting/sync/'); ?>">
						同步：
						<?php if(in_array('douban', $myLinkedProviders)): ?>
						<span class="infoBox_snsIcon_douban_linked">豆瓣</span>
						<?php else:?>
						<span class="infoBox_snsIcon_douban">豆瓣</span>
						<?php endif;?>
						<?php if(in_array('tsina', $myLinkedProviders)): ?>
						<span class="infoBox_snsIcon_tsina_linked">新浪微博</span>
						<?php else:?>
						<span class="infoBox_snsIcon_tsina">新浪微博</span>
						<?php endif;?>
						<?php if(in_array('tqq', $myLinkedProviders)): ?>
						<span class="infoBox_snsIcon_tqq_linked">腾讯微博</span>
						<?php else:?>
						<span class="infoBox_snsIcon_tqq">腾讯微博</span>
						<?php endif;?>
					</a>
				</p>
				<?php endif; ?>
				<?php if($this->session->userdata('uid') == '1'): ?>
				<p style="margin-top:10px;">最后登录时间：<?php echo mdate('%Y-%m-%d %H:%i' , $lastLogin->time); ?></p>
				<?php endif; ?>
			</div>
		</div>
		<p class="bottom bio"><?php echo nl2br($user->bio); ?></p>
	</div>
	<?php if ($isInviteModuleDisplay): ?>
	<div class="content_box invite_box">
		<h3>邀请好友</h3>
		<form method="post" class="inviteForm" action="<?php echo site_url('account/invite_do/'); ?>">
			<input type="text" name="email" />
			<a href="javascript:void(0);" class="button">邀请好友</a>
		</form>
		<p class="inviteQuote"><a href="<?php echo site_url('account/myinvites/'); ?>">剩余 <?php echo $user->inviteQuote; ?></a></p>
	</div>
	<script type="text/javascript">
	$('div.invite_box form a').click(function(){
		$('div.invite_box form').submit();
	});
	
	// SeachBox Prompt
	var invitebox_prompt = '请输入好友的邮箱';
	$('div.invite_box form input').val(invitebox_prompt);
	$('div.invite_box form input').focus(function(){
		if ( $(this).val() == invitebox_prompt ){
			$(this).val('');
		}
	});
	$('div.invite_box form input').blur(function(){
		if ( $(this).val() == '' ){
			$(this).val(invitebox_prompt);
		}
	});
	
		<?php if ($user->inviteQuote <= 0): ?>
		$('div.invite_box form').submit(function(){
			return false;
		});
		<?php endif; ?>
	</script>
	<?php endif; ?>
	<?php if(sizeof($followings)>0): ?>
	<div class="content_box" style="position:relative; padding:10px 0 0 15px;">
		<?php if($isSelf): ?>
		<h3>我关注的人</h3>
		<?php else: ?>
		<h3>TA关注的人</h3>
		<?php endif; ?>	
		<a href="<?php echo site_url("user/{$user->username}/follows/"); ?>" style="position:absolute; right:20px; top:10px;">全部</a>
		<div class="iconsGrid">
      <?php foreach($followings as $followUser): ?>
      <dl>
        <dt>
          <a href="<?php echo site_url("user/$followUser->username/"); ?>">
            <img src="<?php echo getGravatar($followUser->email, 50); ?>" class="avatar avatar-50" />
          </a>
        </dt>
        <dd>
          <a href="<?php echo site_url("user/$followUser->username/"); ?>"><?php echo $followUser->nickname; ?></a>
        </dd>
      </dl>
      <?php endforeach; ?>
      <p class="clearfix" style="margin-bottom:20px;">
				<a href="<?php echo site_url("user/{$user->username}/follows/"); ?>">关注<?php echo $followingsCount; ?>人</a> ｜ 
      	<a href="<?php echo site_url("user/{$user->username}/fans/"); ?>">被<?php echo $followersCount; ?>人关注</a>
      </p>
    </div>
	</div>
	<?php endif; ?>
</div>

<script type="text/javascript" src="<?php echo site_url('include/script/jquery-validate/').'jquery.validate.pack.js'; ?>"></script>
<script type="text/javascript" src="<?php echo site_url('include/script/jquery-validate/').'jquery.validate.extendByYt.js'; ?>"></script>
<script type="text/javascript"> 
$('form.inviteForm').validate({   
/* 设置验证规则 */  
rules: {   
	email:{   
		required:true,   
		email:true
	}   
},   
     
/* 设置错误信息 */  
messages: {   
	address:{   
		required: "请输入好友的Email",   
		email: "请输入正确的Email"  
	}   
},   

    
/* 设置错误信息提示DOM */  
errorPlacement: function(error, element) {       
	error.appendTo( element.parent());       
},     
  
});  
</script>