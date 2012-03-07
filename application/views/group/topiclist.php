  <div id="body">
    <div class="inner">
      <div class="content_box content group">
        <div class="group_bread brd-bottom">
          <a href="<?php echo site_url('group'); ?>">小组</a>
          <ul class="group_myListNav">
            <li <?php if($type=='latest'): ?>class="current"<?php endif; ?>><a href="<?php echo site_url("group/"); ?>">最新话题</a></li>
            <li <?php if($type=='mytopics'): ?>class="current"<?php endif; ?>><a href="<?php echo site_url("group/mytopics/"); ?>">我发起的话题</a></li>
            <li <?php if($type=='myreplies'): ?>class="current"<?php endif; ?>><a href="<?php echo site_url("group/myreplies/"); ?>">我回应的话题</a></li>
          </ul>
        </div>
        <div class="group_main">
          <?php if(sizeof($topics)>0): ?>
          <table class="group_main_topics clearfix" style="border:none;">
            <tr class="group_main_topics_th">
              <th style="width:300px;">主题</th>
              <th style="width:100px; text-align:center;">小组</th>
              <th style="widhth:90px; text-align:center;">作者</th>
              <th style="width:60px; text-align:center;">回复</th>
              <th style="text-align:center;">更新</th>
            </tr>
            <?php foreach($topics as $topic): ?>
            <?php $topic->author = $this->MUser->getByUid($topic->userId); ?>
            <?php $topic->replysCount = $this->MGroupTopic->getTopicReplysCount($topic->id); ?>
            <tr>
              <td><a href="<?php echo site_url("group/topic/$topic->id/"); ?>" title="<?php echo $topic->title; ?>"><?php echo utf_substr($topic->title,46); ?></a></td>
              <td style="text-align:center;"><a href="<?php echo site_url("group/$topic->url/"); ?>"><?php echo $topic->name; ?></a></td>
              <td style="text-align:center;"><a href="<?php echo $this->MUser->getUrl($topic->author->uid); ?>"><?php echo $topic->author->nickname; ?></a></td>
              <td style="text-align:center;"><?php echo $topic->replysCount; ?></td>
              <td style="text-align:center;"><?php echo toRelativeTime($topic->activeTime); ?></td>
            </tr>
            <?php endforeach; ?>
          </table>
          <?php echo $this->pagination->create_links(); ?>
          <?php endif; ?>
          <?php if($type=='latest'): ?>
          <div style="margin-top:20px;">
            <h3>全部小组</h3>
            <div class="iconsGrid">
              <?php foreach($groups as $group): ?>
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
          <?php endif; ?>
        </div>
      </div>