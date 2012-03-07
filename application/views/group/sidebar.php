      <div class="sidebar">
		<!--
        <div class="content_box sidebar_groupsModule">
          <h3>我参加的小组</h3>
          <div class="iconsGrid">
            <?php foreach($joinedGroups as $group): ?>
            <?php $group->memberCount = $this->MGroup->getMembersCount($group->id); ?>
            <dl>
              <dt>
                <a href="<?php echo site_url("group/$group->url/"); ?>">
                  <img src="<?php echo getGroupIcon($group); ?>" />
                </a>
              </dt>
              <dd>
                <a href="<?php echo site_url("group/$group->url/"); ?>"><?php echo utf_substr($group->name, 16); ?></a>
                <span>(<?php echo $group->memberCount; ?>)</span>
              </dd>
            </dl>
            <?php endforeach; ?>
            <p class="clearfix"></p>
          </div>
        </div>
		-->
        <!--<div class="content_box sidebar_groupsModule">
          <h3 style="font-weight: normal;"><a href="<?php echo site_url("group/newgroup/"); ?>">>> 申请建立小组</a></h3>
        </div>-->
        <?php 
			$this->load->view("sidebar/components/sns_links.php"); 
			$this->load->view("sidebar/components/ext_links.php"); 
		?>
      </div>
      <div class="clearfix"></div>
    </div>
  </div>