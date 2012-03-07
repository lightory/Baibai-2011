  <div id="body">
    <div class="inner">
      <div id="mailBox" class="content_box content">
        <h3 class="mailBox_h3">站内信</h3>
        <ul class="mailBox_menu">
          <li><a href="<?php echo site_url('mail/inbox/'); ?>">收件箱(<?php echo "$unreadInboxMailCount/$inboxMailCount"; ?>)</a></li>
          <li><span>发件箱(<?php echo $outboxMailCount; ?>)</span></li>
        </ul>
        <ul class="mailBox_mailList">
          <?php 
            foreach($mails as $mail):
              $mail->receiver = $this->MUser->getByUid($mail->receiverUid);
              $mail->receiver->avatar = getGravatar($mail->receiver->email, 32);
          ?>
          <li class="mailBox_mailList_mail">
            <?php if($mail->receiverUid!=0): ?>
            <img src="<?php echo $mail->receiver->avatar; ?>" class="avatar" />
            <?php endif; ?>
            <div>
              <p class="mailBox_mailList_mail_title">
                <a href="<?php echo $this->MUser->getUrl($mail->receiverUid); ?>"><?php echo $mail->receiver->nickname; ?></a> 
                <?php echo mdate('%Y-%m-%d %H:%i', $mail->time); ?>
              </p>
              <p class="mailBox_mailList_mail_content">
                <?php echo $mail->content; ?>
              </p>
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