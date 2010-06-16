<h2><a href="<?=SITE_URL . 'admin/user/' . $user->id?>"><?=$user->fullname?></a></h2>
<a href="<?=SITE_URL . 'admin/user/' . $user->id?>">
	<img src="http://www.gravatar.com/avatar/<?=md5(strtolower('%email%')) . '?' . http_build_query(array('s' => 48, 'd' => 'identicon', 'r' => 'g'))?>" alt="Avatar" />
</a>
<ul>
	<li>Username - <?=$user->username?></li>
	<li>Email - <?=$user->email?></li>
</ul>
