<?php

use engine\module\Hook;
use engine\router\Route;
use engine\util\Path;

############################# SIDEBAR #############################
function getUiSections()
{
  $uiSectionsPath = Path::resolve(Path::file('view'), 'ui');
  $uiSections = is_dir($uiSectionsPath) ? scandir($uiSectionsPath) : [];
  $uiSectionsFormatted = [];

  foreach ($uiSections as $uiSection) {
    if (in_array($uiSection, ['.', '..'], true)) {
      continue;
    }

    $uiSectionName = getFileName($uiSection);
    $uiSectionNameFormatted = ucfirst(str_replace('-', ' ', $uiSectionName));
    $link = Route::link('ui-section', ['section' => $uiSectionName]);

    $uiSectionsFormatted[$uiSectionNameFormatted] = $link;
  }

  return $uiSectionsFormatted;
}

Hook::run('admin_sidebar_prepend', [
  'name' => '',
  'is_separator' => true,
  'route' => Route::link('log')
]);

Hook::run('admin_sidebar_prepend', [
  'icon' => 'layout',
  'name' => 'UI',
  'route' => getUiSections()
]);

Hook::run('admin_sidebar_prepend', [
  'icon' => 'activity',
  'name' => 'Logs',
  'route' => Route::link('log')
]);

Hook::run('admin_sidebar_prepend', [
  'icon' => 'box',
  'name' => 'Modules',
  'route' => Route::link('module')
]);

Hook::run('admin_sidebar_prepend', [
  'name' => 'Dev',
  'is_separator' => true,
  'route' => Route::link('log')
]);
