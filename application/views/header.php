<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="google-site-verification" content="kKQa2cDosd4U25bDwpXiiGH07gmFSPZkqGmxqcEDMfI" />
	<title><?php echo $title; ?></title>
	<link href="<?php echo site_url('include/style/').'global.css?v=20110724'; ?>" rel="stylesheet" type="text/css" />
	<?php if(isset($styles)):foreach($styles as $style): ?>
	<link href="<?php echo site_url('include/style/').$style; ?>" rel="stylesheet" type="text/css" />
	<?php endforeach;endif; ?>
	<link href="<?php echo site_url('include/style/').'fancybox.css?v=20110104'; ?>" rel="stylesheet" type="text/css" />
	<link href="<?php echo site_url('include/script/fancybox/').'jquery.fancybox-1.3.1.css'; ?>" rel="stylesheet" type="text/css" />
	<script type="text/javascript">
		var SITE_URL = '<?php echo site_url(); ?>';
	</script>
	<script type="text/javascript" src="<?php echo site_url('include/script/').'jq.js'; ?>"></script>
	<script type="text/javascript" src="<?php echo site_url('include/script/fancybox/').'jquery.fancybox-1.3.1.js';?>"></script>
	<script type="text/javascript" src="<?php echo site_url('include/script/').'global.js?v=20101205';?>"></script>
</head>
<body <?php if(isset($current)): ?>class="<?php echo $current; ?>"<?php endif; ?>>
	<div id="header">
		<div class="inner">
			<a href="<?php echo site_url(); ?>"><h2>摆摆书架</h2></a>
			<ul class="menu">
				<li class="home"><a href="<?php echo site_url('mine/'); ?>">我的</a></li>
				<li class="lib"><a href="<?php echo site_url('book/'); ?>">书架</a></li>
				<?php if($this->session->userdata('uid')): ?>
				<li class="group"><a href="<?php echo site_url('group/'); ?>">小组</a></li>
				<?php endif; ?>
			</ul>
			
			<?php if($this->session->userdata('uid')): ?>
				<a href="<?php echo site_url("book/donate/"); ?>" onclick="return false;" class="donate fancybox">捐赠</a>
				<a href="<?php echo site_url("borrow/request/"); ?>" class="borrow">求借</a>
			<?php endif; ?>
			
			<ul class="user_info">
			  <?php if($this->session->userdata('uid')): ?>
				<li class="header_username">
				  <a href="<?php echo site_url('mine/'); ?>"><?php echo $this->session->userdata('nickname') ?></a>
				  <a href="javascript:void(0); ?>" class="header_username_dropdown"><img src="<?php echo site_url('include/style/img/').'header_username_dropdownIcon.png'; ?>" /></a>
				  <ul>
				    <li><a href="<?php echo site_url("setting/profile/"); ?>">个人设置</a></li>
				    <li><a href="<?php echo site_url("account/logout_do/"); ?>">退出</a></li>
				  </ul>
				</li>
				<li class="header_mail">
				  <a href="<?php echo site_url('mail/'); ?>">
				    <img src="<?php echo site_url('include/style/img/').'mail_icon.png'; ?>" alt="站内信"/>
				    <?php echo $this->MMail->getUserInboxUnreadCount($this->session->userdata('uid')); ?>
				  </a>
				</li>
			  <?php else: ?>
			  <li><a href="<?php echo site_url('account/login/'); ?>">登录</a></li>
			  &nbsp;&nbsp;|&nbsp;&nbsp;
			  <li><a href="<?php echo site_url('account/apply/'); ?>">注册</a></li>
			  <?php endif; ?>
			</ul>
			<form id="search" class="search" method="POST" action="<?php echo site_url('book/search/'); ?>" >
				<input type="text" name="keyword" class="keyword" />
				<input type="submit" class="submit" />
			</form>
		</div>
	</div>