<?php


if (empty($subject) && !empty($subjectRe)) {
  $subject = 'RE: ' . textWord($subjectRe);
} else if (empty($subject) && empty($subjectRe)) {
  $subject = 'RE: ' . textWord(textExcerpt($messageRe, 20));
}

$message .= '
  <br>
  <p>' . t('Sincerely') . ',<br>' . t('Administration') . ' <a href="' . site('url') . '" target="_blank">' . site('name') . '</a></p>
';

return [
  'subject' => $subject,
  'message' => $message
];
