<?php

namespace Engine;

class Filter {
	public $name;
	public $data = [];

	public $sql;
	public $binding = [];
	public $order = [];

	public $options = [];

	private static $instance;

	public static function getInstance() {
		return self::$instance;
	}

	public function __construct($name) {
		$this->name = $name;

		$this->load();
		$this->process();

		self::$instance = $this;
	}

	private function load() {
		$path = Path::file('filter') . '/' . $this->name . '.php';

		if(!is_file($path)) {
			return false;
		}

		$filter = include $path;

		if(!is_array($filter) || empty($filter)) {
			return false;
		}

		$this->data = $filter;

		return $filter;
	}

	private function process() {
		if(empty($this->data)) {
			return false;
		}

		$sql = [];
		$binding = [];
		$order = [];

		foreach($this->data as $alias => $filter) {
			if(!Request::has($alias) || empty($filter['type']) || empty($filter['column'])) {
				continue;
			}

			$value = Request::get($alias);

			if((is_array($value) && empty($value)) || (is_string($value) && mb_strlen($value) <= 0)) {
				continue;
			}

			$type = $filter['type'];
			$sql_token = Hash::token(8);
			$columns = !is_array($filter['column']) ? [$filter['column']] : $filter['column'];

			$sql_part = [];

			foreach($columns as $column_name) {
				switch($type) {
					case 'checkbox':
					case 'radio':
					case 'switch': {
						$value = !is_array($value) ? [$value] : $value;

						foreach($value as $key => $v) {
							if(!is_numeric($v)){
								continue;
							}

							$token = $key === 0 ? $sql_token : Hash::token(8);

							$sql_part[] = "$column_name = :$token";
							$binding[$token] = ($v === 'true' || $v === '1') ? true : false;
						}

						break;
					}
					case 'number':
					case 'range': {
						$value = !is_array($value) ? [$value] : $value;

						if((isset($filter['range']) && $filter['range']) || (isset($filter['data-range']) && $filter['data-range'])) {
							$value_from = $value[0];
							$value_to = $value[1] ?? $value[0];

							if(!is_numeric($value_from) || !is_numeric($value_to)){
								break;
							}

							$key_from = $sql_token;
							$key_to = Hash::token(8);

							$sql_part[] = "$column_name BETWEEN :$key_from AND :$key_to";

							$binding[$key_from] = $value_from;
							$binding[$key_to] = $value_to;

							break;
						}

						foreach($value as $key => $number) {
							if(!is_numeric($number)){
								continue;
							}

							$token = $key === 0 ? $sql_token : Hash::token(8);

							$sql_part[] = "$column_name = :$token";
							$binding[$token] = $number;
						}

						break;
					}
					case 'date': {
						$value = !is_array($value) ? explode(' - ', $value) : $value;

						if((isset($filter['range']) && $filter['range']) || (isset($filter['data-range']) && $filter['data-range'])) {
							$value_from = $value[0];
							$value_to = $value[1] ?? $value[0];

							if(!strtotime($value_from) || !strtotime($value_to)){
								break;
							}

							$key_from = $sql_token;
							$key_to = Hash::token(8);

							$sql_part[] = "CAST($column_name AS DATE) BETWEEN CAST(:$key_from AS DATE) AND CAST(:$key_to AS DATE)";

							$binding[$key_from] = $value_from;
							$binding[$key_to] = $value_to;

							break;
						}

						foreach($value as $key => $date) {
							if(!strtotime($date)){
								continue;
							}

							$token = $key === 0 ? $sql_token : Hash::token(8);

							$sql_part[] = "CAST($column_name AS DATE) = CAST(:$token AS DATE)";
							$binding[$token] = $date;
						}

						break;
					}
					case 'select': {
						$value = !is_array($value) ? [$value] : $value;

						foreach($value as $key => $v) {
							if(!is_scalar($v)){
								continue;
							}

							$token = $key === 0 ? $sql_token : Hash::token(8);

							$sql_part[] = "$column_name = :$token";
							$binding[$token] = $v;
						}

						break;
					}
					case 'order': {
						$order[$column_name] = $value;
						break;
					}
					default: { // text, maska, etc.
						if(!is_scalar($value)){
							break;
						}

						$sql_part[] = "$column_name LIKE :$sql_token";

						$binding[$sql_token] = '%' . addcslashes($value, '%') . '%';

						break;
					}
				}
			}

			if(!empty($sql_part)) {
				$sql[] = '(' . implode(' OR ', $sql_part) . ')';
			}
		}

		$order_sql = [];
		foreach($order as $column_name => $value) {
			if(strtolower($value ?? '') === 'asc') {
				$order_sql[] = "$column_name ASC";
			}
			else if(strtolower($value ?? '') === 'desc') {
				$order_sql[] = "$column_name DESC";
			}
			else {
				$token = Hash::token(8);
				$binding[$token] = $value;
				$order_sql[] = "$column_name = :$token";
			}
		}

		$this->sql = implode(' AND ', $sql);
		$this->binding = $binding;
		$this->order = implode(', ', $order_sql);

		return true;
	}

	public function setOptions($alias, $options = []) {
		$this->options[$alias] = $options;

		if(!isset($this->data[$alias]['classifier'])) {
			return true;
		}

		$classifier = $this->data[$alias]['classifier'];

		$this->options[$alias] = array_map(function($opt) use($classifier, $options) {
			$opt->text = is_closure($classifier) ? $classifier($opt->id) : $classifier . '.' . $opt->id;

			return $opt;
		}, $options);

		return true;
	}
}
