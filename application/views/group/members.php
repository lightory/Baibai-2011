  <div id="body">
    <div class="inner">
			<div class="breadcrumbs">
				<a href="<?php echo site_url("group/"); ?>">小组</a>
				<span style="font-size:12px;">&gt;</span>
				<a href="<?php echo site_url("group/$group->url/"); ?>"><?php echo $group->name; ?></a>
			</div>
      <div class="content_box content group">
        <div class="contentBox_tabNav">
          <a href="<?php echo site_url("group/$group->url/"); ?>" class="contentBox_tabNav_tab first">讨论</a><!--
          --><a href="<?php echo site_url("group/$group->url/books"); ?>" class="contentBox_tabNav_tab">藏书</a><!--
          --><a href="<?php echo site_url("group/$group->url/members/"); ?>" class="contentBox_tabNav_tab current">成员</a>
        </div>
        <div class="group_main">
          <div style="margin-top:20px;">
            <h3>组长</h3>
            <div class="iconsGrid">
              <?php $owner->avatar = getGravatar($owner->email, 45); ?>
              <dl>
                <dt>
                  <a href="<?php echo $this->MUser->getUrl($owner->uid); ?>">
                    <img src="<?php echo $owner->avatar; ?>" class="avatar avatar-45" />
                  </a>
                </dt>
                <dd>
                  <a href="<?php echo $this->MUser->getUrl($owner->uid); ?>"><?php echo $owner->nickname; ?></a>
                </dd>
              </dl>
              <p class="clearfix"></p>
            </div>
          </div>
          <div>
            <h3>成员</h3>
            <div class="iconsGrid">
              <?php foreach($members as $member): ?>
              <?php $member->avatar = getGravatar($member->email, 45); ?>
              <dl>
                <dt>
                  <a href="<?php echo $this->MUser->getUrl($member->uid); ?>">
                    <img src="<?php echo $member->avatar; ?>" class="avatar avatar-45" />
                  </a>
                </dt>
                <dd>
                  <a href="<?php echo $this->MUser->getUrl($member->uid); ?>"><?php echo $member->nickname; ?></a>
                </dd>
              </dl>
              <?php endforeach; ?>
              <p class="clearfix"></p>
            </div>
          </div>
        </div>
      </div>