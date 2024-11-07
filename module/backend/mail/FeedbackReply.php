<?php

use engine\auth\User;

if (empty($subject) && !empty($subjectRe)) {
  $subject = 'RE: ' . textWord($subjectRe);
} else if (empty($subject) && empty($subjectRe)) {
  $subject = 'RE: ' . textExcerpt(textWord($messageRe, 20));
}

$userName = User::get('name') ?? t('mail.administration');

$message .= '
  <br>
  <p>' . t('mail.sincerely') . '<br>' . $userName . '<br><a href="' . site('url') . '" target="_blank">' . site('name') . '</a></p>
';

return [
  'subject' => $subject,
  'message' => $message
];
