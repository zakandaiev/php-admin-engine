<?php Theme::header(); ?>

<?php Theme::block('sidebar'); ?>

<main class="page-content__inner">

  <?php Theme::block('navbar/top'); ?>

  <section class="section section_grow section_offset">
    <div class="container-fluid">

      <nav class="breadcrumb">
        <span class="breadcrumb__item"><a href="<?= Route::link('dashboard') ?>">Home</a></span>
        <span class="breadcrumb__item"><a href="<?= Route::link('ui-home') ?>">Dev UI</a></span>
        <span class="breadcrumb__item">Modals</span>
      </nav>

      <h2 class="section__title">Modals</h2>

      <div class="mt-2">
        <div class="row cols-xs-1 cols-md-2 gap-xs fill">

          <div class="col">
            <div class="box">
              <div class="box__header">
                <h4 class="box__title">Default modal</h4>
              </div>
              <div class="box__body text-center">
                <button class="btn btn_primary" data-modal="#modal-primary">Primary</button>
                <div id="modal-primary" class="modal">
                  <header class="modal__header">
                    <span>Default modal</span>
                    <button type="button" class="modal__close" data-modal-close>
                      <i class="ti ti-x"></i>
                    </button>
                  </header>
                  <div class="modal__body">
                    Use modals to add dialogs to your site for lightboxes, user notifications, or completely custom content.
                  </div>
                  <footer class="modal__footer">
                    <button class="btn btn_cancel" data-modal-close>Cancel</button>
                    <button class="btn btn_primary">Save changes</button>
                  </footer>
                </div>

                <button class="btn btn_secondary" data-modal="#modal-secondary">Secondary</button>
                <div id="modal-secondary" class="modal">
                  <header class="modal__header">
                    <span>Default modal</span>
                    <button type="button" class="modal__close" data-modal-close>
                      <i class="ti ti-x"></i>
                    </button>
                  </header>
                  <div class="modal__body">
                    Use modals to add dialogs to your site for lightboxes, user notifications, or completely custom content.
                  </div>
                  <footer class="modal__footer">
                    <button class="btn btn_cancel" data-modal-close>Cancel</button>
                    <button class="btn btn_secondary">Save changes</button>
                  </footer>
                </div>

                <button class="btn btn_success" data-modal="#modal-success">Success</button>
                <div id="modal-success" class="modal">
                  <header class="modal__header">
                    <span>Default modal</span>
                    <button type="button" class="modal__close" data-modal-close>
                      <i class="ti ti-x"></i>
                    </button>
                  </header>
                  <div class="modal__body">
                    Use modals to add dialogs to your site for lightboxes, user notifications, or completely custom content.
                  </div>
                  <footer class="modal__footer">
                    <button class="btn btn_cancel" data-modal-close>Cancel</button>
                    <button class="btn btn_success">Save changes</button>
                  </footer>
                </div>

                <button class="btn btn_error" data-modal="#modal-error">Error</button>
                <div id="modal-error" class="modal">
                  <header class="modal__header">
                    <span>Default modal</span>
                    <button type="button" class="modal__close" data-modal-close>
                      <i class="ti ti-x"></i>
                    </button>
                  </header>
                  <div class="modal__body">
                    Use modals to add dialogs to your site for lightboxes, user notifications, or completely custom content.
                  </div>
                  <footer class="modal__footer">
                    <button class="btn btn_cancel" data-modal-close>Cancel</button>
                    <button class="btn btn_error">Save changes</button>
                  </footer>
                </div>

                <button class="btn btn_warning" data-modal="#modal-warning">Warning</button>
                <div id="modal-warning" class="modal">
                  <header class="modal__header">
                    <span>Default modal</span>
                    <button type="button" class="modal__close" data-modal-close>
                      <i class="ti ti-x"></i>
                    </button>
                  </header>
                  <div class="modal__body">
                    Use modals to add dialogs to your site for lightboxes, user notifications, or completely custom content.
                  </div>
                  <footer class="modal__footer">
                    <button class="btn btn_cancel" data-modal-close>Cancel</button>
                    <button class="btn btn_warning">Save changes</button>
                  </footer>
                </div>

                <button class="btn btn_info" data-modal="#modal-info">Info</button>
                <div id="modal-info" class="modal">
                  <header class="modal__header">
                    <span>Default modal</span>
                    <button type="button" class="modal__close" data-modal-close>
                      <i class="ti ti-x"></i>
                    </button>
                  </header>
                  <div class="modal__body">
                    Use modals to add dialogs to your site for lightboxes, user notifications, or completely custom content.
                  </div>
                  <footer class="modal__footer">
                    <button class="btn btn_cancel" data-modal-close>Cancel</button>
                    <button class="btn btn_info">Save changes</button>
                  </footer>
                </div>

              </div>
            </div>
          </div>

          <div class="col">
            <div class="box">
              <div class="box__header">
                <h4 class="box__title">Modal sizes and position</h4>
              </div>
              <div class="box__body text-center">
                <div class="mb-6">
                  <button class="btn btn_primary" data-modal="#modal-small">Small</button>
                  <div id="modal-small" class="modal modal_sm">
                    <header class="modal__header">
                      <span>Small modal</span>
                      <button type="button" class="modal__close" data-modal-close>
                        <i class="ti ti-x"></i>
                      </button>
                    </header>
                    <div class="modal__body">
                      Use modals to add dialogs to your site for lightboxes, user notifications, or completely custom content.
                    </div>
                    <footer class="modal__footer">
                      <button class="btn btn_cancel" data-modal-close>Cancel</button>
                      <button class="btn btn_primary">Save changes</button>
                    </footer>
                  </div>

                  <button class="btn btn_primary" data-modal="#modal-medium">Medium</button>
                  <div id="modal-medium" class="modal">
                    <header class="modal__header">
                      <span>Medium modal</span>
                      <button type="button" class="modal__close" data-modal-close>
                        <i class="ti ti-x"></i>
                      </button>
                    </header>
                    <div class="modal__body">
                      Use modals to add dialogs to your site for lightboxes, user notifications, or completely custom content.
                    </div>
                    <footer class="modal__footer">
                      <button class="btn btn_cancel" data-modal-close>Cancel</button>
                      <button class="btn btn_primary">Save changes</button>
                    </footer>
                  </div>

                  <button class="btn btn_primary" data-modal="#modal-large">Large</button>
                  <div id="modal-large" class="modal modal_lg">
                    <header class="modal__header">
                      <span>Large modal</span>
                      <button type="button" class="modal__close" data-modal-close>
                        <i class="ti ti-x"></i>
                      </button>
                    </header>
                    <div class="modal__body">
                      Use modals to add dialogs to your site for lightboxes, user notifications, or completely custom content.
                    </div>
                    <footer class="modal__footer">
                      <button class="btn btn_cancel" data-modal-close>Cancel</button>
                      <button class="btn btn_primary">Save changes</button>
                    </footer>
                  </div>

                </div>
                <div>
                  <button class="btn btn_primary" data-modal="#modal-centered-small">Centered small</button>
                  <div id="modal-centered-small" class="modal modal_sm modal_center">
                    <header class="modal__header">
                      <span>Centered small modal</span>
                      <button type="button" class="modal__close" data-modal-close>
                        <i class="ti ti-x"></i>
                      </button>
                    </header>
                    <div class="modal__body">
                      Use modals to add dialogs to your site for lightboxes, user notifications, or completely custom content.
                    </div>
                    <footer class="modal__footer">
                      <button class="btn btn_cancel" data-modal-close>Cancel</button>
                      <button class="btn btn_primary">Save changes</button>
                    </footer>
                  </div>

                  <button class="btn btn_primary" data-modal="#modal-centered-medium">Centered medium</button>
                  <div id="modal-centered-medium" class="modal modal_center">
                    <header class="modal__header">
                      <span>Centered medium modal</span>
                      <button type="button" class="modal__close" data-modal-close>
                        <i class="ti ti-x"></i>
                      </button>
                    </header>
                    <div class="modal__body">
                      Use modals to add dialogs to your site for lightboxes, user notifications, or completely custom content.
                    </div>
                    <footer class="modal__footer">
                      <button class="btn btn_cancel" data-modal-close>Cancel</button>
                      <button class="btn btn_primary">Save changes</button>
                    </footer>
                  </div>

                  <button class="btn btn_primary" data-modal="#modal-centered-large">Centered large</button>
                  <div id="modal-centered-large" class="modal modal_lg modal_center">
                    <header class="modal__header">
                      <span>Centered large modal</span>
                      <button type="button" class="modal__close" data-modal-close>
                        <i class="ti ti-x"></i>
                      </button>
                    </header>
                    <div class="modal__body">
                      Use modals to add dialogs to your site for lightboxes, user notifications, or completely custom content.
                    </div>
                    <footer class="modal__footer">
                      <button class="btn btn_cancel" data-modal-close>Cancel</button>
                      <button class="btn btn_primary">Save changes</button>
                    </footer>
                  </div>

                </div>
              </div>
            </div>
          </div>

        </div>
      </div>

    </div>
  </section>

  <?php Theme::block('navbar/bottom'); ?>

</main>

<?php Theme::footer(); ?>
