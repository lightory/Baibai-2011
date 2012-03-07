<?php 
  foreach($records as $record): 
    $record->receiver = $this->MUser->getByUid($record->receiverUid);
    $record->senter = $this->MUser->getByUid($record->senterUid);
    $record->book = $this->MBook->getById($record->bookId);
?>
<hr/>
<p>
  <a href="<?php echo $this->MUser->getUrl($record->senterUid); ?>"><?php echo $record->senter->nickname; ?></a> 借给
  <a href="<?php echo $this->MUser->getUrl($record->receiverUid); ?>"><?php echo $record->receiver->nickname; ?></a> 
  一本
  <a href="<?php echo site_url("book/subject/$record->bookId/"); ?>" target="_blank">《<?php echo $record->book->name; ?>》</a>，
  等待收货确认
  <?php echo mdate('%Y-%m-%d %H:%i', $record->time3); ?>
</p>
<p>
  <span><?php echo $record->expressType; ?></span>
  <span><?php echo $record->expressId; ?></span>
</p>
<?php endforeach; ?>