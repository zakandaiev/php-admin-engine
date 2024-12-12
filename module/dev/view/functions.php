<?php

############################# UI #############################
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

############################# LOG #############################
function formatLog($content)
{
  $date = '<span class="log__bracket">[</span><span class="log__date cursor-pointer" data-copy data-toast="Date copied">$1</span><span class="log__bracket">]</span>';
  $ip = '<span class="log__bracket">[</span><span class="log__ip cursor-pointer" data-copy data-toast="IP copied">$2</span><span class="log__bracket">]</span>';
  $userId = '<span class="log__bracket">[</span><span class="log__user-id cursor-pointer" data-copy data-toast="User ID copied">$3</span><span class="log__bracket">]</span>';
  $hyphen = '<span class="log__hyphen">$4</span>';
  $message = '<span class="log__message cursor-pointer" data-copy data-toast="Message copied">$5</span>';

  $pattern = '/\[(.*)\] \[(.*)\] \[(.*)\] (-) (.*)/miu';
  $replacement = "$date $ip $userId $hyphen $message";

  return preg_replace($pattern, $replacement, trim($content ?? ''));
}
