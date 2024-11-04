<?php

use engine\module\Hook;

############################# EXTEND BACKEND SIDEBAR #############################
Hook::run('sidebar.append.after', 'backend.setting.frontend', [
  'id' => 'analytic.setting.analytic',
  'text' => t('setting.sidebar.analytic'),
  'name' => 'setting.section',
  'parameter' => ['section' => 'analytic'],
  'module' => 'backend'
]);

############################# EXTEND SETTING MODEL #############################
Hook::run('setting.column.add', 'google_analytics', [
  'type' => 'text',
  'min' => 2,
  'max' => 64,
  'className' => 'col-xs-12 col-md-6 col-lg-3'
]);

Hook::run('setting.column.add', 'google_tag_manager', [
  'type' => 'text',
  'min' => 2,
  'max' => 64,
  'className' => 'col-xs-12 col-md-6 col-lg-3'
]);

############################# SET PAGE META #############################
$googleAnalyticsHtml = '';
$googleAnalyticsId = site('google_analytics');
if (!empty($googleAnalyticsId)) {
  $googleAnalyticsHtml = '
    <script async src="https://www.googletagmanager.com/gtag/js?id=' . $googleAnalyticsId . '"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag("js", new Date());
      gtag("config", "' . $googleAnalyticsId . '");
    </script>
  ';
}

$googleTagManagerHtml = '';
$googleTagManagerId = site('google_tag_manager');
if (!empty($googleTagManagerId)) {
  $googleTagManagerHtml = "
  <script>
    (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer','$googleTagManagerId');
  </script>
  ";
}

Hook::run('page.meta.add', $googleAnalyticsHtml);
Hook::run('page.meta.add', $googleTagManagerHtml);
