<div class="newFancy sendMail">
  <div class="newFancy_header sendMail_header">
    <h3>发信给</h3>
    <div class="sendMail_header_receiver">
      <p class="sendMail_header_receiver_info">
        <?php echo $receiver->nickname; ?><br/>
        <?php echo $receiver->address->province.' , '.$receiver->address->city; ?>
      </p>
      <img class="sendMail_header_receiver_avatar newFancy_avatar" src="<?php echo $receiver->avatar; ?>" />
    </div>
    <div class="newFancy_postmark"></div>
  </div>
  <div class="newFancy_middle sendMail_middle">
    <form class="sendMail_middle_form" method="post" action="<?php echo site_url("mail/send_do/$receiver->uid/"); ?>">
      <textarea name="mailContent"></textarea>
      <a href="javascript:void(0);" class="newFancy_button sendMail_middle_form_button">发信</a>
    </form>
    <div class="clearfix"></div>
  </div>
</div>

<script type="text/javascript">
$("a.sendMail_middle_form_button").click(function(){      
  $.fancybox.showActivity();

  $.ajax({
    type    : "POST",
    url   : $(this).parent().attr('action'),
    data    : $(this).parent().serializeArray(),
    success: function(data) {
      $.fancybox(data);
    }
  });
  
  return false;
});

$(document).keypress(function(e){
  if(e.ctrlKey && e.which == 13 || e.which == 10) {
    $("a.sendMail_middle_form_button").click();
  }
});
</script>