<?php

namespace module\backend\controller;

use module\backend\controller\Backend;

class Feedback extends Backend
{
  public function getList()
  {
    $this->view->setData('feedbacks', $this->model->getFeedbacks());
    $this->view->render('feedback/list');
  }

  public function getReply()
  {
    $feedbackId = $this->route['parameter']['id'];
    $feedback = $this->model->getFeedbackById($feedbackId);

    if (empty($feedback)) {
      $this->view->error('404');
      return false;
    }

    $this->model->updateTable($this->model->getTable(), ['is_read' => true], $this->model->getPrimaryKey(), $feedbackId);

    $this->view->setData('feedback', $feedback);
    $this->view->render('feedback/reply');
  }
}
