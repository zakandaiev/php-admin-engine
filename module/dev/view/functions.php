<?php

############################# DEV #############################
function getButtonColors()
{
  return array_merge(['default', 'cancel'], getButtonAccentColors());
}

function getButtonAccentColors()
{
  return ['primary', 'secondary', 'success', 'error', 'warning', 'info'];
}

function getButtonIcon($color = null)
{
  $icons = ['default' => 'ti-accessible', 'cancel' => 'ti-ad-circle', 'primary' => 'ti-mood-smile', 'secondary' => 'ti-world', 'success' => 'ti-check', 'error' => 'ti-x', 'warning' => 'ti-alert-circle', 'info' => 'ti-info-circle'];

  return isset($color) ? @$icons[$color] : 'ti-check';
}

function getButtonSocials()
{
  return ['apple', 'facebook', 'google', 'instagram', 'telegram', 'twitter', 'youtube'];
}

function getUiSectionFiles($isFormatName = false)
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
    $link = routeLink('ui.section', ['section' => $uiSectionName]);

    $uiSectionsFormatted[$isFormatName ? $uiSectionNameFormatted : $uiSectionName] = $link;
  }

  return $uiSectionsFormatted;
}
