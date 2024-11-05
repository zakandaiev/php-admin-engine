<?php

namespace module\backend\model;

use engine\auth\User;
use engine\database\Model;
use engine\database\Query;
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
      'type' => 'text',
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

    return $feedback;
  }

  public function countUnreadFeedback()
  {
    $sql = 'SELECT count(*) FROM {feedback} WHERE is_read IS false';

    $count = new Query($sql);

    return $count->execute()->fetchColumn();
  }
}
