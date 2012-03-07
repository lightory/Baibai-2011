<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<script type="text/javascript" src="<?php echo site_url('include/script/').'jq.js'; ?>"></script>
</head>
<body>
	<form method="post" action="<?php echo $postURL; ?>" style="display:none;">
		<input type="text" name="<?php echo $name; ?>" value="<?php echo $expressId; ?>" />
	</form>
	<script type="text/javascript">
	$('form').submit();
	</script>
</body>
</html>