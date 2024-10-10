<?php Theme::template('ui-header', ['title' => 'Pagination']); ?>

<div class="box">
  <div class="box__body">
    <div>
      <nav class="pagination pagination_sm">
        <a href="/" class="pagination__item"><i class="ti ti-chevron-left"></i></a>
        <a href="/" class="pagination__item">1</a>
        <span class="pagination__item active">2</span>
        <a href="/" class="pagination__item">3</a>
        <span class="pagination__item">...</span>
        <a href="/" class="pagination__item">9</a>
        <a href="/" class="pagination__item">10</a>
        <a href="/" class="pagination__item"><i class="ti ti-chevron-right"></i></a>
      </nav>
    </div>

    <div class="mt-3">
      <nav class="pagination">
        <a href="/" class="pagination__item"><i class="ti ti-chevron-left"></i></a>
        <a href="/" class="pagination__item">1</a>
        <span class="pagination__item active">2</span>
        <a href="/" class="pagination__item">3</a>
        <span class="pagination__item">...</span>
        <a href="/" class="pagination__item">9</a>
        <a href="/" class="pagination__item">10</a>
        <a href="/" class="pagination__item"><i class="ti ti-chevron-right"></i></a>
      </nav>
    </div>

    <div class="mt-3">
      <nav class="pagination pagination_lg">
        <a href="/" class="pagination__item"><i class="ti ti-chevron-left"></i></a>
        <a href="/" class="pagination__item">1</a>
        <span class="pagination__item active">2</span>
        <a href="/" class="pagination__item">3</a>
        <span class="pagination__item">...</span>
        <a href="/" class="pagination__item">9</a>
        <a href="/" class="pagination__item">10</a>
        <a href="/" class="pagination__item"><i class="ti ti-chevron-right"></i></a>
      </nav>
    </div>

  </div>
</div>

<?php Theme::template('ui-footer'); ?>
