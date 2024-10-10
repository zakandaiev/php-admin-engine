<?php Theme::template('ui-header', ['title' => 'Accordion']); ?>

<div class="box">
  <div class="accordions" data-collapse>
    <div class="accordion accordion_underline" data-active>
      <button type="button" class="accordion__header">
        <span>Accordion</span>
        <i class="ti ti-chevron-right"></i>
      </button>

      <div class="accordion__body">
        <div class="accordion__content">
          Lorem, ipsum dolor sit amet consectetur adipisicing elit. Et, sint tempore? Rem sequi culpa esse numquam earum nemo quos alias repellat, hic necessitatibus. Voluptates accusamus dolores tempore accusantium repellat numquam?
        </div>
      </div>
    </div>

    <div class="accordion accordion_underline">
      <button type="button" class="accordion__header">
        <span>Accordion 2</span>
        <i class="ti ti-chevron-right"></i>
      </button>

      <div class="accordion__body">
        <div class="accordion__content">
          Lorem, ipsum dolor sit amet consectetur adipisicing elit. Et, sint tempore? Rem sequi culpa esse numquam earum nemo quos alias repellat, hic necessitatibus. Voluptates accusamus dolores tempore accusantium repellat numquam?
        </div>
      </div>
    </div>

    <div class="accordion accordion_underline">
      <button type="button" class="accordion__header">
        <span>Accordion 3</span>
        <i class="ti ti-chevron-right"></i>
      </button>

      <div class="accordion__body">
        <div class="accordion__content">
          Lorem, ipsum dolor sit amet consectetur adipisicing elit. Et, sint tempore? Rem sequi culpa esse numquam earum nemo quos alias repellat, hic necessitatibus. Voluptates accusamus dolores tempore accusantium repellat numquam?
        </div>
      </div>
    </div>
  </div>
</div>

<?php Theme::template('ui-footer'); ?>
