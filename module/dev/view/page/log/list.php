<?php

$title = 'Logs';

Page::set('title', $title);

Page::breadcrumb('add', $title);

function getLogsHtml($logs = [])
{
  $html = '';

  foreach ($logs as $logName => $logList) {
    $html .= getLogHtml($logName, $logList);
  }

  return $html;
}

function getLogHtml($logName, $logList = [], $isChild = false)
{
  if (!is_array($logList)) {
    $logId = $logList;

    $viewLink = routeLink('log.view', ['file' => $logId]);

    return '
      <div class="accordion accordion_sm accordion_underline">
        <a href="' . $viewLink . '" class="accordion__header">
          <i class="ti ti-file-text"></i>
          <span>' . $logList . '</span>
          <i class="ti ti-eye"></i>
        </a>
      </div>
    ';
  }

  $items = '';
  foreach ($logList as $lIndex => $lName) {
    if (is_array($lName)) {
      // TODO recursive
      $items .= '<tr><td colspan="2"><div class="accordions">';
      $items .= getLogHtml($lIndex, $lName, true);
      $items .= '</div></td></tr>';
    } else {
      $items .= getLogItemHtml($lName, $logName);
    }
  }

  $accordionClass = $isChild ? 'accordion_sm' : '';

  return '
    <div class="accordion accordion_underline ' . $accordionClass . '">
      <button type="button" class="accordion__header">
        <i class="ti ti-folder"></i>
        <span>' . $logName . '</span>
        <i class="ti ti-chevron-right"></i>
      </button>

      <div class="accordion__body">
        <div class="accordion__content p-0 pb-2">
          <table class="table table_sm">
            <tbody class="last-row-borderless">
              ' . $items . '
            </tbody>
          </table>
        </div>
      </div>
    </div>
  ';
}

function getLogItemHtml($logName, $folder = null)
{
  $logId = $logName;
  if ($folder) {
    $logId .= "_$folder";
  }

  $viewLink = routeLink('log.view', ['file' => $logId]);
  $viewTooltip = 'View this log';

  $deleteToken = Form::delete('Log', $logId, true);
  $deleteConfirm = 'Do you want to delete this log?';
  $deleteTooltip = 'Delete this log';

  return '
    <tr>
      <td>
        <a href="' . $viewLink . '">
          <span class="table__action">
            <i class="ti ti-file-text"></i>
          </span>

          <span>' . $logName . '</span>
        </a>
      </td>
      <td class="table__actions">
        <a href="' . $viewLink . '" data-tooltip="top" title="' . $viewTooltip . '" class="table__action"><i class="ti ti-eye"></i></a>

        <button type="button" data-action="' . $deleteToken . '" data-confirm="' . $deleteConfirm . '" data-remove="trow" data-tooltip="top" title="' . $deleteTooltip . '" class="table__action d-none todo"><i class="ti ti-trash"></i></button>
      </td>
    </tr>
  ';
}
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
        <?php if (!empty($logs)): ?>
          <div class="accordions" data-collapse>
            <?= getLogsHtml($logs) ?>
          </div>
        <?php else: ?>
          <div class="box__body">
            <h5 class="box__subtitle">There are no logs yet</h5>
          </div>
        <?php endif; ?>
      </div>

    </div>
  </section>

  <?php Theme::template('navbar-bottom'); ?>

</main>

<?php Theme::footer(); ?>
