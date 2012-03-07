  <div id="body">
    <div class="inner">
      <div class="content">
        <div class="content_box">
          <div class="index_introModel">
            <img src="<?php echo site_url("include/style/img").'index_introModel_pic.png'; ?>" class="index_introModel_pic" />
            <img src="<?php echo site_url("include/style/img").'index_introModel_logo.png'; ?>" class="index_introModel_logo" />
            <p class="index_introModel_text">
              摆摆书架是一个社会化图书馆。<br/>
              摆摆书架的所有藏书均来自会员捐赠、寄存在会员处，并且所有会员都可以随时借阅。<br/>
              您可以分享自己看过的闲书，并且可以免费借阅他人分享书籍。
            </p>
            <div class="clearfix"></div>
          </div>
          <div class="lib_contentModule book_list3">
          	<?php $this->load->view("book/components/slide_books.php", $slideBooks); ?>
          </div>
        </div>
      </div>
      <div class="sidebar">
        <div class="content_box index_loginModel">
          <div class="index_loginModel_header">
            <h3>用户登录</h3>
            <a href="<?php echo site_url('account/resetpassword/'); ?>">忘记密码</a>
          </div>
          <form class="index_loginModel_middle" method="post" action="<?php echo site_url("account/login_do/"); ?>">
            <p class="index_loginModel_email">
              <input type="text" name="email" id="email" />
            </p>
            <p class="index_loginModel_password">
              <input type="password" name="password" id="password" />
            </p>
            <label class="index_loginModel_remember">
              <input type="checkbox" name="remember" value="1" />记住我
            </label>
            <input type="submit" value="登录" class="button index_loginModel_submit" onClick="pageTracker._trackEvent('首页', '登录', location.href);" />
          </form>
          <div class="index_loginModel_footer">
            <a href="<?php echo site_url('account/apply/'); ?>" class="index_loginModel_footer_link" onClick="pageTracker._trackEvent('首页', '申请内测', location.href);">还没有帐号？来申请内测吧～</a>
          </div>
        </div>
        <div class="content_box blog_post_list">
          <h3>摆摆日记</h3>
          <ul>
            <?php $limit=sizeof($blogPosts)>5?5:sizeof($blogPosts);for($i=0;$i<$limit;$i++){$blogPost=$blogPosts[$i]; ?>
            <li><a href="<?php echo $blogPost['link']; ?>"><?php echo $blogPost['title']; ?></a></li>
            <?php }; ?>
          </ul>
        </div>
      </div>
      <div class="clearfix"></div>
    </div>
  </div>