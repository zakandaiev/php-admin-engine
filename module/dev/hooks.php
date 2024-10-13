<?php

use engine\module\Hook;
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

Hook::run('backend.sidebar.prepend', [
  'text' => '',
  'isSeparator' => true,
  'name' => 'log'
]);

Hook::run('backend.sidebar.prepend', [
  'icon' => 'layout',
  'text' => 'UI',
  'name' => getUiSections()
]);

Hook::run('backend.sidebar.prepend', [
  'icon' => 'activity',
  'text' => 'Logs',
  'name' => 'log'
]);

Hook::run('backend.sidebar.prepend', [
  'icon' => 'box',
  'text' => 'Modules',
  'name' => 'module'
]);

Hook::run('backend.sidebar.prepend', [
  'text' => 'Dev',
  'isSeparator' => true,
  'name' => 'log'
]);
