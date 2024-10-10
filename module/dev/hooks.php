<?php

use engine\module\Hook;
use engine\router\Route;
use engine\util\Path;

############################# SIDEBAR #############################
function getUiSections()
{
  $uiSectionsPath = Path::resolve(Path::file('page'), 'ui');
  $uiSections = is_dir($uiSectionsPath) ? scandir($uiSectionsPath) : [];
  $uiSectionsFormatted = [];

  foreach ($uiSections as $uiSection) {
    if (in_array($uiSection, ['.', '..'], true)) {
      continue;
    }

    $uiSectionName = getFileName($uiSection);
    $uiSectionNameFormatted = ucfirst(str_replace('-', ' ', $uiSectionName));
    $uiSectionsFormatted[$uiSectionNameFormatted] = [
      'name' => 'ui-section',
      'parameter' => ['section' => $uiSectionName]
    ];
  }

  return $uiSectionsFormatted;
}

Hook::run('admin_sidebar_prepend', [
  'name' => '',
  'is_separator' => true,
  'route' => 'log'
]);

Hook::run('admin_sidebar_prepend', [
  'icon' => 'layout',
  'name' => 'UI',
  'route' => getUiSections()
]);

Hook::run('admin_sidebar_prepend', [
  'icon' => 'activity',
  'name' => 'Logs',
  'route' => 'log'
]);

Hook::run('admin_sidebar_prepend', [
  'icon' => 'box',
  'name' => 'Modules',
  'route' => 'module'
]);

Hook::run('admin_sidebar_prepend', [
  'name' => 'Dev',
  'is_separator' => true,
  'route' => 'log'
]);
