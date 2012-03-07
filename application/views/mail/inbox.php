  <div id="body">
    <div class="inner">
      <div id="mailBox" class="content_box content">
        <h3 class="mailBox_h3">站内信</h3>
        <ul class="mailBox_menu">
          <li><span>收件箱(<?php echo "$unreadInboxMailCount/$inboxMailCount"; ?>)</span></li>
          <li><a href="<?php echo site_url('mail/outbox/'); ?>">发件箱(<?php echo $outboxMailCount; ?>)</a></li>
        </ul>
        <ul class="mailBox_mailList">
          <?php 
            foreach($mails as $mail):
              if($mail->senterUid == 0){
                $mail->senter->uid = 0;
                $mail->senter->nickname = '摆摆书架';
                $mail->senter->email = 'baibaibook@gmail.com';
              } else{
                $mail->senter = $this->MUser->getByUid($mail->senterUid);
              }
              $mail->senter->avatar = getGravatar($mail->senter->email, 32);
          ?>
          <li class="mailBox_mailList_mail">
            <img src="<?php echo $mail->senter->avatar; ?>" class="avatar" />
            <div>
              <p class="mailBox_mailList_mail_title">
                <a href="<?php echo $this->MUser->getUrl($mail->senterUid); ?>"><?php echo $mail->senter->nickname; ?></a> 
                <?php echo mdate('%Y-%m-%d %H:%i', $mail->time); ?>
              </p>
              <p class="mailBox_mailList_mail_content">
                <?php echo $mail->content; ?>
              </p>
              <?php if($mail->senterUid!=0): ?>
              <p class="mailBox_mailList_mail_replyButton">
                <a href="<?php echo site_url("mail/send/$mail->senterUid/"); ?>" class="fancybox" onclick="return false;" >回复...</a>
              </p>
              <?php endif; ?>
              <a href="javascript:void(0)"; url="<?php echo site_url("mail/delete_do/$mail->id/"); ?>" class="mailBox_mailList_mail_deleteButton">X</a>
            </div>
          </li>
          <?php endforeach;?>
        </ul>
      </div>
      <?php echo $this->Common->sidebar(); ?>
      <div class="clearfix"></div>
    </div>
  </div>
  
<script type="text/javascript">
$('a.mailBox_mailList_mail_deleteButton').click(function(){
  $.ajax({
    type    : "GET",
    url   : $(this).attr('url')
  });
  
  $(this).parents('li').hide();
});
</script>