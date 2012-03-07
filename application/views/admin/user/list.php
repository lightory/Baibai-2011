<h2>已注册用户</h2>
<table>
	<tr>
		<th>用户名</th>
		<th>摆摆券</th>
		<th>捐赠的书</th>
		<th>想借的书</th>
		<th>已借入的书</th>
		<th>可借出的书</th>
		<th>借过的书</th>
	</tr>
	<?php foreach($registerUsers as $user): ?>
	<tr>
		<td><a href="<?php echo $this->MUser->getUrl($user->uid); ?>" target="_blank"><?php echo $user->nickname; ?></a></td>
		<td><?php echo $user->borrowQuote; ?></td>
		<td><?php echo $user->realDonateCount; ?>/<?php echo $user->donateCount; ?>本</td>
		<td><?php echo $user->wishesCount; ?>本</td>
		<td><?php echo $user->readingCount; ?>本</td>
		<td><?php echo $user->availableCount; ?>本</td>
		<td><?php echo $user->borrowedCount; ?>本</td>
	</tr>
	<?php endforeach; ?>
</table>