<a href="<?=SITE_URL . 'admin/user/' . $user->id?>">
	<img src="http://www.gravatar.com/avatar/<?=md5(strtolower($user->email)) . '?' . http_build_query(array('s' => 48, 'd' => 'identicon', 'r' => 'g'))?>" alt="Avatar" />
</a>
<a href="<?=SITE_URL . 'admin/user/' . $user->id?>"><?=$user->fullname?> - <?=$user->username?></a>