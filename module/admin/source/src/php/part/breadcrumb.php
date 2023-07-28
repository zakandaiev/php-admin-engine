<?php if(!empty($breadcrumb) || $options['render_homepage']): ?>
	<nav class="breadcrumb">
		<?php if($options['render_homepage']): ?>
			<span class="breadcrumb__item"><a href="<?= site('url_language') . $options['homepage_url'] ?>"><?= $options['homepage_name'] ?></a></span>
		<?php endif; ?>

		<?php foreach($breadcrumb as $crumb): ?>
			<?php if(!empty($crumb->url)): ?>
				<span class="breadcrumb__item"><a href="<?= site('url_language') . $crumb->url ?>"><?= $crumb->name ?></a></span>
			<?php else: ?>
				<span class="breadcrumb__item"><?= $crumb->name ?></span>
			<?php endif; ?>
		<?php endforeach; ?>
	</nav>
<?php endif; ?>
