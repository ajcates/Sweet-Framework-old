<article>
	<header>
		<a href="<?=SITE_URL . 'admin/user/' . $user->id?>">
			<img src="http://www.gravatar.com/avatar/<?=md5(strtolower($user->email)) . '?' . http_build_query(array('s' => 48, 'd' => 'identicon', 'r' => 'g'))?>" alt="Avatar" />
		</a>
		<h2><a href="<?=SITE_URL . 'admin/user/' . $user->id?>"><?=$user->fullname?></a></h2>
	</header>
	<ul class="actions">
		<li><a href="<?=SITE_URL?>admin/user/edit/<?=$user->id?>">Edit</a></li>
		<li><a href="<?=SITE_URL?>admin/user/delete/<?=$user->id?>">Delete</a></li>
		<li><a href="<?=SITE_URL?>admin/user/duplicate/<?=$user->id?>">Duplicate</a></li>
	</ul>
	<ul>
		<li>Username - <?=$user->username?></li>
		<li>Email - <?=$user->email?></li>
	</ul>
</article>
