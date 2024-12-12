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
    $uiSectionsFormatted[] = [
      'id' => 'dev.ui.' . $uiSectionName,
      'text' => $uiSectionNameFormatted,
      'name' => 'ui.section',
      'parameter' => ['section' => $uiSectionName]
    ];
  }

  return $uiSectionsFormatted;
}

Hook::run('sidebar.prepend', [
  'id' => 'dev.ui-separator',
  'text' => '',
  'isSeparator' => true,
  'name' => 'log.list'
]);

Hook::run('sidebar.prepend', [
  'id' => 'dev.ui',
  'icon' => 'layout',
  'text' => 'UI',
  'name' => getUiSections()
]);

Hook::run('sidebar.prepend', [
  'id' => 'dev.log',
  'icon' => 'activity',
  'text' => 'Logs',
  'name' => 'log.list'
]);

Hook::run('sidebar.prepend', [
  'id' => 'dev.module',
  'icon' => 'box',
  'text' => 'Modules',
  'name' => 'module'
]);

Hook::run('sidebar.prepend', [
  'id' => 'dev.log-separator',
  'text' => 'Dev',
  'isSeparator' => true,
  'name' => 'log.list'
]);
