<footer class="footer">
  <div class="container-fluid">
    <div class="row gap-xs cols-xs-2">
      <div class="col">
        <a href="<?= getEngineProperty('author_url') ?>" target="_blank"><?= getEngineProperty('author') ?></a>
        <span>&copy; <?= formatDate(null, 'Y') ?></span>
      </div>
      <div class="col text-right">
        <a href="<?= site('url') ?>" target="_blank"><strong><?= t('admin.navbar.open_site') ?></strong></a>
      </div>
    </div>
  </div>
</footer>
