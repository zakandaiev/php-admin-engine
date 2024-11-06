<?php

namespace module\backend\model;

use engine\auth\User;
use engine\database\Model;
use engine\database\Query;
use engine\util\Mail;
use engine\util\Hash;

class Feedback extends Model
{
  public function __construct()
  {
    parent::__construct();

    $this->setTable('feedback');
    $this->setPrimaryKey('id');

    $this->setColumn('id', [
      'type' => 'text',
      'required' => true,
      'min' => 16,
      'max' => 32,
      'value' => Hash::token()
    ]);

    $this->setColumn('user_id', [
      'type' => 'text',
      'required' => true,
      'min' => 16,
      'max' => 32
    ]);

    $this->setColumn('email', [
      'type' => 'email',
      'required' => true,
      'min' => 6,
      'max' => 256
    ]);

    $this->setColumn('subject', [
      'type' => 'text',
      'min' => 2,
      'max' => 128
    ]);

    $this->setColumn('message', [
      'type' => 'wysiwyg',
      'required' => true,
      'min' => 2,
      'max' => 1024
    ]);

    $this->setColumn('ip', [
      'type' => 'text',
      'min' => 8,
      'max' => 64
    ]);

    $this->setColumn('is_read', [
      'type' => 'boolean',
      'value' => false
    ]);

    $this->setColumn('is_replied', [
      'type' => 'boolean',
      'value' => false
    ]);
  }

  public function reply()
  {
    if (!$this->hasTable()) {
      return false;
    }

    $this->modifyColumns();

    $this->validate();
    if ($this->hasError()) {
      return false;
    }

    $subject = $this->getColumnValue('subject');
    $message = $this->getColumnValue('message');

    $feedbackId = $this->getItemId();
    $feedback = $this->getFeedbackById($feedbackId);
    if (empty($feedback)) {
      return false;
    }

    $mailData = [
      'subjectRe' => $feedback->subject,
      'messageRe' => $feedback->message,
      'subject' => $subject,
      'message' => $message,
    ];

    $mailOptions = [
      'to' => $feedback->email,
      'isForced' => true
    ];

    $mail = new Mail('FeedbackReply', $mailData, $mailOptions);

    $mailResult = $mail->send();
    if (!$mailResult) {
      return false;
    }

    $this->updateTable($this->getTable(), ['is_replied' => true], $this->getPrimaryKey(), $this->getItemId());

    return true;
  }

  public function getFeedbacks()
  {
    $sql = 'SELECT * FROM {feedback} ORDER BY date_created ASC';
    $query = new Query($sql);
    // TODO
    // ->filter()
    $feedbacks = $query->paginate()->execute()->fetchAll();

    $feedbacks = array_map(function ($feedback) {
      $feedback->user = User::getUserById($feedback->user_id);

      return $feedback;
    }, $feedbacks);

    return $feedbacks;
  }

  public function getFeedbackById($id)
  {
    $sql = 'SELECT * FROM {feedback} WHERE id=:id';
    $query = new Query($sql);
    $feedback = $query->execute(['id' => $id])->fetch();

    if (isset($feedback->user_id)) {
      $feedback->user = User::getUserById($feedback->user_id);
    }

    return $feedback;
  }

  public function countUnreadFeedback()
  {
    $sql = 'SELECT count(*) FROM {feedback} WHERE is_read IS false';

    $count = new Query($sql);

    return $count->execute()->fetchColumn();
  }
}
