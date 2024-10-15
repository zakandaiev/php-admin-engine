<?php

namespace engine\database;

use engine\http\Request;
use engine\module\Setting;
use engine\router\Route;
use engine\util\Text;

class Pagination
{
  protected $uriKey;
  protected $uri;
  protected $limit;
  protected $totalRows;
  protected $totalPages;
  protected $currentPage;
  protected $offset;
  protected static $instance;

  public function __construct($totalRows, $options = [])
  {
    $this->uriKey = $options['uriKey'] ?? 'page';
    $this->uri = $this->handleUri();
    $this->limit = $options['limit'] ?? Setting::getProperty('pagination_limit') ?? 10;
    $this->limit = intval($this->limit);
    $this->totalRows = intval($totalRows);
    $this->totalPages = $this->countPages();
    $this->currentPage = $this->currentPage();
    $this->offset = ($this->currentPage - 1) * $this->limit;

    self::$instance = $this;
  }

  public static function getInstance($totalRows = 0, $options = [])
  {
    if (!self::$instance instanceof self) {
      self::$instance = new self($totalRows, $options);
    }

    return self::$instance;
  }

  public function get($key)
  {
    if ($key === 'uriKey') {
      return $this->uriKey;
    } else if ($key === 'uri') {
      return $this->uri;
    } else if ($key === 'limit') {
      return $this->limit;
    } else if ($key === 'totalRows') {
      return $this->totalRows;
    } else if ($key === 'totalPages') {
      return $this->totalPages;
    } else if ($key === 'currentPage') {
      return $this->currentPage;
    } else if ($key === 'offset') {
      return $this->offset;
    }

    return null;
  }

  protected function handleUri()
  {
    return Text::html(Route::changeQuery([$this->uriKey => ''], true));
  }

  protected function countPages()
  {
    return ceil($this->totalRows / $this->limit) ?? 1;
  }

  protected function currentPage()
  {
    $page = Request::get($this->uriKey);

    if (isset($page) && !empty($page) && is_numeric($page)) {
      $page = intval($page);
    } else {
      $page = 1;
    }

    if ($page < 1) {
      $page = 1;
    }

    if ($page > $this->totalPages && $this->totalPages > 0) {
      $page = $this->totalPages;
    }

    return $page;
  }
}
