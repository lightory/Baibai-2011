  <div id="body">
    <div class="inner">
      <div id="config" class="content_box content">
        <div class="contentBox_tabNav">
          <a href="<?php echo site_url("user/{$user->username}/
follows/"); ?>" class="contentBox_tabNav_tab first current">我关注的</a><!--
          --><a href="<?php echo site_url("user/{$user->username}/fans/"); ?>" class="contentBox_tabNav_tab">关注我的</a>
        </div>
				<div class="iconsGrid" style="margin:15px 0 0 15px;">
		      <?php foreach($contacts as $contact): ?>
		      <dl>
		        <dt>
		          <a href="<?php echo site_url("user/$contact->username/"); ?>">
		            <img src="<?php echo getGravatar($contact->email, 50); ?>" class="avatar avatar-50" />
		          </a>
		        </dt>
		        <dd>
		          <a href="<?php echo site_url("user/$contact->username/"); ?>"><?php echo $contact->nickname; ?></a>
		        </dd>
		      </dl>
		      <?php endforeach; ?>
		      <p class="clearfix"></p>
		    </div>
      </div>
      <?php echo $this->Common->sidebar($user->uid, $isSelf); ?>
      <div class="clearfix"></div>
    </div>
  </div>