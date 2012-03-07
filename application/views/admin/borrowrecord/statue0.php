<?php 
  foreach($requests as $request): 
    $request->receiver = $this->MUser->getByUid($request->userId);
    $request->book = $this->MBook->getById($request->bookId);
?>
<hr/>
<p>
  <a href="<?php echo $this->MUser->getUrl($request->userId); ?>"><?php echo $request->receiver->nickname; ?></a> 
  想借 
  <a href="<?php echo site_url("book/subject/$request->bookId/"); ?>" target="_blank">《<?php echo $request->book->name; ?>》</a>
  <?php echo mdate('%Y-%m-%d %H:%i', $request->time); ?>
</p>
<p><?php echo $request->message; ?></p>
<?php endforeach; ?>