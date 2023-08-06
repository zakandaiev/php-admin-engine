<?php

namespace Engine;

class Pagination {
	public $uri_key;
	public $uri;
	public $limit;
	public $total_rows;
	public $total_pages;
	public $current_page;
	public $offset;

	private static $instance;

	public static function getInstance() {
		if(!self::$instance instanceof self) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function __construct($total_rows, $options = []) {
		$this->uri_key = $options['uri_key'] ?? PAGINATION['uri_key'];
		$this->uri = $this->handleUri();
		$this->limit = $options['limit'] ?? Setting::get('engine')->pagination_limit ?? 10;
		$this->limit = intval($this->limit);
		$this->total_rows = intval($total_rows);
		$this->total_pages = $this->countPages();
		$this->current_page = $this->currentPage();
		$this->offset = ($this->current_page - 1) * $this->limit;

		self::$instance = $this;
	}

	private function handleUri() {
		$uri = explode('?', Request::$uri_full, 2);
		$uri_handle = $uri[0] . '?';

		if(isset($uri[1]) && !empty($uri[1])) {
			$params = explode('&', $uri[1]);

			foreach($params as $param) {
				if(!preg_match('#' . $this->uri_key . '=#', $param)) $uri_handle .= $param.'&';
			}
		}

		$uri_handle .= $this->uri_key . '=';

		return html($uri_handle);
	}

	private function countPages() {
		return ceil($this->total_rows / $this->limit) ?? 1;
	}

	private function currentPage() {
		$page = Request::get($this->uri_key);

		if(isset($page) && !empty($page) && is_numeric($page)) {
			$page = intval($page);
		} else {
			$page = 1;
		}

		if($page < 1) $page = 1;
		if($page > $this->total_pages && $this->total_pages > 0) $page = $this->total_pages;

		return $page;
	}
}
