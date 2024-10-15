<?php

$message = '
  <p><span style="font-size:16px"><strong>' . t('Good day') . '</strong></span></p>
  <br>
  <p>' . t('This is test mail from the site') . ' <a href="' . site('url') . '" target="_blank">' . site('name') . '</a></p>
  <p>' . t('This is an automatic email, no need to reply') . '.</p>
  <br>
  <p>' . t('Sincerely') . ',<br>' . t('Administration') . ' <a href="' . site('url') . '" target="_blank">' . site('name') . '</a></p>
';

return [
  'subject' => t('Mail test'),
  'message' => $message,
  'from' => 'dummy@mail.org', // if set to null then 'from' => Setting::getProperty('email', 'contact')
  'type' => 'change_login' // if isset then it will check user preferences from $user->setting->notifications->{'mail_' . $type}
];
