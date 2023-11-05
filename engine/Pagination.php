<?php

namespace Engine;

class Pagination
{
	protected $uri_key;
	protected $uri;
	protected $limit;
	protected $total_rows;
	protected $total_pages;
	protected $current_page;
	protected $offset;

	protected static $instance;

	public static function getInstance()
	{
		if (!self::$instance instanceof self) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function __construct($total_rows, $options = [])
	{
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

	public function get($key)
	{
		switch ($key) {
			case 'uri_key': {
					return $this->uri_key;
				}
			case 'uri': {
					return $this->uri;
				}
			case 'limit': {
					return $this->limit;
				}
			case 'total_rows': {
					return $this->total_rows;
				}
			case 'total_pages': {
					return $this->total_pages;
				}
			case 'current_page': {
					return $this->current_page;
				}
			case 'offset': {
					return $this->offset;
				}
		}

		return null;
	}

	protected function handleUri()
	{
		$uri = explode('?', Request::uri_full(), 2);
		$uri_handle = $uri[0] . '?';

		if (isset($uri[1]) && !empty($uri[1])) {
			$params = explode('&', $uri[1]);

			foreach ($params as $param) {
				if (!preg_match('#' . $this->uri_key . '=#', $param)) $uri_handle .= $param . '&';
			}
		}

		$uri_handle .= $this->uri_key . '=';

		return html($uri_handle);
	}

	protected function countPages()
	{
		return ceil($this->total_rows / $this->limit) ?? 1;
	}

	protected function currentPage()
	{
		$page = Request::get($this->uri_key);

		if (isset($page) && !empty($page) && is_numeric($page)) {
			$page = intval($page);
		} else {
			$page = 1;
		}

		if ($page < 1) $page = 1;
		if ($page > $this->total_pages && $this->total_pages > 0) $page = $this->total_pages;

		return $page;
	}
}
