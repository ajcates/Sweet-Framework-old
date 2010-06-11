<header>
	<hgroup>
	<? if(isset($title)): ?>
		<h1><?=$title?></h1>
	<? endif; ?>
	<? if(isset($subtitle)): ?>
		<h2><?=$subtitle?></h2>
	<? endif; ?>
	</hgroup>
	<nav>
		<?=$nav?>
	</nav>
</header>