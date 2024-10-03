<?php

use engine\theme\Asset;

Theme::header(); ?>

<?php Theme::block('sidebar'); ?>

<main class="page-content__inner">

  <?php Theme::block('navbar/top'); ?>

  <section class="section section_grow section_offset">
    <div class="container-fluid">

      <nav class="breadcrumb">
        <span class="breadcrumb__item"><a href="<?= Route::link('dashboard') ?>">Home</a></span>
        <span class="breadcrumb__item">Dev UI</span>
      </nav>

      <h2 class="section__title">Dev UI</h2>

      <div class="box mt-2">
        <div class="box__header">
          <h4 class="box__title">List of all UI sections</h4>
        </div>

        <nav class="list-group">
          <?php
          $uiSectionsPath = Path::resolve(Path::file('view'), 'ui');
          $uiSections = is_dir($uiSectionsPath) ? scandir($uiSectionsPath) : [];

          foreach ($uiSections as $uiSection) {
            if (in_array($uiSection, ['.', '..'], true)) {
              continue;
            }

            $uiSectionName = getFileName($uiSection);
            $link = Route::link('ui-section', ['section' => $uiSectionName]);

            echo '<a href="' . $link . '" class="list-group__item">' . ucfirst(str_replace('-', ' ', $uiSectionName)) . '</a>';
          }
          ?>
        </nav>
      </div>
  </section>

  <?php Theme::block('navbar/bottom'); ?>

</main>

<?php Theme::footer(); ?>
