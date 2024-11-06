<?php

$title = t('translation.list.title');

Page::set('title', $title);

Page::breadcrumb('add', $title);
?>

<?php Theme::header(); ?>

<?php Theme::template('sidebar'); ?>

<main class="page-content__inner">

  <?php Theme::template('navbar-top'); ?>

  <section class="section section_grow section_offset">
    <div class="container-fluid">

      <?php Theme::breadcrumb(); ?>

      <h2 class="section__title"><?= $title ?></h2>

      <div class="box">
        <div class="accordions" data-collapse>

          <?php foreach ($modules as $module): ?>
            <div class="accordion accordion_underline">
              <button type="button" class="accordion__header">
                <i class="ti ti-folder"></i>
                <span><?= $module['name'] ?></span>
                <i class="ti ti-chevron-right"></i>
              </button>

              <div class="accordion__body">
                <div class="accordion__content p-0 pb-2">

                  <table class="table table_sm">
                    <tbody class="last-row-borderless">
                      <tr>
                        <td>
                          <a href="<?= routeLink('translation.add', null, ['module' => $module['name']]) ?>">
                            <span class="table__action">
                              <i class="ti ti-plus"></i>
                            </span>

                            <span><?= t('translation.list.add') ?></span>
                          </a>
                        </td>
                        <td></td>
                      </tr>

                      <?php foreach ($module['languages'] as $language): ?>
                        <?php
                        $editLink = routeLink('translation.edit', ['module' => $module['name'], 'language' => $language['key']]);
                        $editId = $module['name'] . '@' . $language['key'] . '-' . $language['region'];
                        ?>
                        <tr>
                          <td>
                            <a href="<?= $editLink  ?>">
                              <span class="table__action">
                                <i class="ti ti-file-text"></i>
                              </span>

                              <span><?= $language['key'] ?>_<?= $language['region'] ?> - <?= t('i18n.' . $language['key']) ?></span>
                            </a>
                          </td>
                          <td class="table__actions">
                            <a href="<?= $editLink  ?>" data-tooltip="top" title="<?= t('translation.list.edit')  ?>" class="table__action"><i class="ti ti-edit"></i></a>

                            <button type="button" data-action="<?= Form::delete('Translation', $editId, true) ?>" data-confirm="<?= t('translation.list.delete_confirm') ?>" data-remove="trow" data-tooltip="top" title="<?= t('translation.list.delete') ?>" class="table__action"><i class="ti ti-trash"></i></button>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>

                </div>
              </div>
            </div>
          <?php endforeach; ?>

        </div>
      </div>

    </div>
  </section>

  <?php Theme::template('navbar-bottom'); ?>

</main>

<?php Theme::footer(); ?>
