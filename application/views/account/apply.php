  <div id="body">
    <div class="inner">
      <div class="content content_box">
        <div class="contentBox_tabNav">
          <a href="<?php echo site_url("account/apply/"); ?>" class="contentBox_tabNav_tab first current">申请内测帐号</a>
        </div>
				<form class="profile" method="post" action="<?php echo site_url('account/apply_do/'); ?>" style="margin:20px 0 0 20px;">
					<?php if ($this->session->flashdata('error')){ ?>
					<p class="nolabel">
						您已经成功提交内测申请，我们会在审核后给您发送邀请邮件。
					</p>
					<p class="nolabel">
						您可以先在下列 SNS 关注我们，了解摆摆书架的最新动态。<br/>
						<a href="http://t.sina.com.cn/baibaibook/" class="sidebar_snsLinks_link" target="_blank"><img src="http://localhost/baibai/include/style/img/tsina.png"></a>
						<a href="http://t.qq.com/baibaibook/" class="sidebar_snsLinks_link" target="_blank"><img src="http://localhost/baibai/include/style/img/tqq.png"></a>
						<a href="http://www.douban.com/people/baibaibook/" class="sidebar_snsLinks_link" target="_blank"><img src="http://localhost/baibai/include/style/img/dou.png"></a>
						<a href="http://twitter.com/baibaibook/" class="sidebar_snsLinks_link" target="_blank"><img src="http://localhost/baibai/include/style/img/twitter.png"></a>
					</p>
					<?php }else{ ?>
					<p>对不起，摆摆书架还处于内测阶段，没有开放注册。<br/>您可以请写这份内测资格申请问卷。并等待内测邀请。</p>
					<p>
						<label for="nickname">昵称 *：</label>
						<input type="text" name="nickname" id="nickname" class="text" />
					</p>
					<p>
						<label for="email">Email *：</label>
						<input type="text" class="text" name="email" id="email" />
					</p>
					<p>
						<label for="address">所在地 *：</label>
						<input type="text" class="text" name="address" id="address" />
					</p>
					<p>
						<label for="blog">博客：</label>
						<input type="text" name="blog" id="blog" class="text"  />
					</p>
					<p>
						<label for="donate">捐赠意愿：</label>
						<input type="radio" name="donate" value="0" /> 不愿意
						&nbsp;&nbsp;&nbsp;<input type="radio" name="donate" value="1" /> 愿意
					</p>
					<p>
						<label for="bio">自我介绍：</label>
						<textarea name="bio"></textarea>
					</p>
					<p>
						<input type="submit" value="确认申请" class="nolabel button" />
					</p>
					<?php } ?>
				</form>
			</div>
      <div class="sidebar">
        <div class="content_box blog_post_list">
          <h3>摆摆日记</h3>
          <ul>
            <?php $limit=sizeof($blogPosts)>5?5:sizeof($blogPosts);for($i=0;$i<$limit;$i++){$blogPost=$blogPosts[$i]; ?>
            <li><a href="<?php echo $blogPost['link']; ?>"><?php echo $blogPost['title']; ?></a></li>
            <?php }; ?>
          </ul>
        </div>
				<div class="content_box sidebar_snsLinks">
				  <h3 class="sidebar_snsLinks_title">关注我们</h3>
				  <a href="http://t.sina.com.cn/baibaibook/" class="sidebar_snsLinks_link" target="_blank">
				    <img src="<?php echo site_url('include/style/img').'tsina.png'; ?>" />
				  </a>
          <a href="http://www.douban.com/people/baibaibook/" class="sidebar_snsLinks_link" target="_blank">
            <img src="<?php echo site_url('include/style/img').'dou.png'; ?>" />
          </a>
				</div>
      </div>
      <div class="clearfix"></div>
    </div>
  </div>
	<script type="text/javascript" src="<?php echo site_url('include/script/jquery-validate/').'jquery.validate.pack.js'; ?>"></script>
	<script type="text/javascript" src="<?php echo site_url('include/script/jquery-validate/').'jquery.validate.extendByYt.js'; ?>"></script>
	<script type="text/javascript"> 
	$('form.profile').validate({   
	/* 设置验证规则 */  
	rules: {   
		nickname: {   
			required:true
		},
		email: {
			required:true,
			email:true
		},
		address:{   
			required:true
		},
		donate:{
			required:true
		}  
	},  

	/* 设置错误信息提示DOM */  
	errorPlacement: function(error, element) {       
		error.appendTo( element.parent());       
	},     

	});  
	</script>