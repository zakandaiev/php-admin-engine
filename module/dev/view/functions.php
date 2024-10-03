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
