<?php

use engine\module\Hook;

############################# SIDEBAR #############################
function getUiSections()
{
  $uiSectionsPath = pathResolve(pathFile('page'), 'ui');
  $uiSections = is_dir($uiSectionsPath) ? scandir($uiSectionsPath) : [];
  $uiSectionsFormatted = [];

  foreach ($uiSections as $uiSection) {
    if (in_array($uiSection, ['.', '..'], true)) {
      continue;
    }

    $uiSectionName = fileGetName($uiSection);
    $uiSectionNameFormatted = ucfirst(str_replace('-', ' ', $uiSectionName));
    $uiSectionsFormatted[$uiSectionNameFormatted] = [
      'name' => 'ui-section',
      'parameter' => ['section' => $uiSectionName]
    ];
  }

  return $uiSectionsFormatted;
}

Hook::run('sidebar.prepend', [
  'text' => '',
  'isSeparator' => true,
  'name' => 'log'
]);

Hook::run('sidebar.prepend', [
  'icon' => 'layout',
  'text' => 'UI',
  'name' => getUiSections()
]);

Hook::run('sidebar.prepend', [
  'icon' => 'activity',
  'text' => 'Logs',
  'name' => 'log'
]);

Hook::run('sidebar.prepend', [
  'icon' => 'box',
  'text' => 'Modules',
  'name' => 'module'
]);

Hook::run('sidebar.prepend', [
  'text' => 'Dev',
  'isSeparator' => true,
  'name' => 'log'
]);
