<?php

use engine\module\Hook;

############################# EXTEND BACKEND SIDEBAR #############################
Hook::run('sidebar.append.after', 'backend.setting.frontend', [
  'id' => 'analytic.setting.analytic',
  'text' => t('sidebar.analytic'),
  'name' => 'setting.section',
  'parameter' => ['section' => 'analytic'],
  'module' => 'backend'
]);

############################# EXTEND SETTING MODEL #############################
Hook::run('setting.column.add', 'google_tag', [
  'type' => 'text',
  'min' => 2,
  'max' => 64
]);

############################# SET PAGE META #############################
$gtagHtml = '';
$gtagId = site('google_tag');
if (!empty($gtagId)) {
  $gtagHtml = '
    <script async src="https://www.googletagmanager.com/gtag/js?id=' . $gtagId . '"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag("js", new Date());
      gtag("config", "' . $gtagId . '");
    </script>
  ';
}

Hook::run('page.meta.add', $gtagHtml);
