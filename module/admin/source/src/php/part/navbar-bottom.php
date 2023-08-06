<footer class="footer">
	<div class="container-fluid">
		<div class="row gap-xs cols-xs-2">
			<div class="col">
				<a href="<?= Engine::AUTHOR_URL ?>" target="_blank"><?= Engine::AUTHOR ?></a>
				<span>&copy; <?= format_date(null, 'Y') ?></span>
			</div>
			<div class="col text-right">
				<a href="<?= site('url') ?>" target="_blank"><strong><?= __('admin.navbar.open_site') ?></strong></a>
			</div>
		</div>
	</div>
</footer>
