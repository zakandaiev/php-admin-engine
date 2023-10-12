<?php

namespace Engine;

use \PDO;
use \PDOException;

class Statement {
	private $sql;
	private $cache;
	private $debug;
	private $prefix;
	private $statement;
	private $binding = [];
	private $filter;
	private $filter_sql;
	private $filter_binding = [];
	private $pagination = [];
	private $is_select;
	private $is_paginate = false;
	private $is_filter = false;

	public function __construct($sql, $cache = null, $debug = null) {
		$is_cached = false;
		$this->is_select = preg_match('/^\s*SELECT/mi', $sql) ? true : false;

		if(isset($cache) && $cache && $this->is_select) {
			$is_cached = true;
		}
		else if(!isset($cache) && $this->is_select && Module::getName() === 'public' && Setting::get('engine')->cache_db == 'true') {
			$is_cached = true;
		}

		$this->cache = $is_cached;
		$this->debug = isset($debug) && $debug ? true : false;

		$this->prefix = DATABASE['prefix'];

		$replacement = '$1';
		if(!empty($this->prefix)) {
			$replacement = $this->prefix.'_$1';
		}

		$this->sql = preg_replace('/{([\w\d\-\_]+)}/miu', $replacement, $sql);

		return $this;
	}

	public function filter($name, $mixed = null) {
		if(!$this->is_select) {
			return $this;
		}

		$mixed = isset($mixed) ? $mixed : true;

		$this->is_filter = false;
		$this->filter_sql = $this->sql;
		$this->filter_binding = [];

		if($mixed === true) {
			$this->is_filter = true;
		}
		else if(is_array($mixed) && !empty($mixed)) {
			foreach($mixed as $alias => $options) {
				$this->filter->setOptions($alias, $options);
			}
		}

		$this->filter = new Filter($name);

		if(empty($this->filter->sql) && empty($this->filter->order)) {
			return $this;
		}

		$sql = !empty($this->filter->sql) ? "WHERE {$this->filter->sql}" : '';
		$sql = "SELECT * FROM ({$this->filter_sql}) t_filter $sql";

		foreach($this->filter->binding as $key => $value) {
			$this->filter_binding[$key] = $key;
			$this->addBinding($key, $value);
		}

		if(!empty($this->filter->order)) {
			$sql .= " ORDER BY {$this->filter->order}";
		}

		$this->sql = $sql;

		return $this;
	}

	private function setFilterValues() {
		if(!$this->is_paginate || empty($this->filter_sql) || empty($this->filter->data) || preg_match('/GROUP\s+BY/mi',  $this->filter_sql)) {
			return false;
		}

		$sql_cuted = $this->cutSelectionPartFromSQL($this->filter_sql);
		$sql_cuted = preg_replace('/\s*ORDER\s+BY\s+[\w\s\@\<\>\.\,\=\-\'\"\`]+$/mi', '', $sql_cuted);

		foreach($this->filter->data as $alias => $filter_data) {
			if(is_array($filter_data['column']) || !in_array($filter_data['type'], ['checkbox','radio','select'])) {
				continue;
			}

			$filter_sql = "SELECT COUNT(*) as `count`, {$filter_data['column']} as `id`, {$filter_data['column']} as `text` FROM $sql_cuted";

			$filter_data['show_all_options'] = $filter_data['show_all_options'] ?? false;

			if(!$filter_data['show_all_options'] && !empty($this->filter->sql)) {
				$filter_sql .= " WHERE {$this->filter->sql}";

				foreach($this->filter->binding as $key => $value) {
					$this->filter_binding[$key] = $value;
				}
			}

			$filter_sql .= " GROUP BY {$filter_data['column']} ORDER BY count DESC";

			$filter_options = new Statement($filter_sql);

			$filter_options = $filter_options->execute($this->filter_binding)->fetchAll();

			$this->filter->setOptions($alias, $filter_options);
		}

		return true;
	}

	public function paginate($total = null, $options = []) {
		if(!$this->is_select) {
			return $this;
		}

		if(isset($total)) {
			$this->pagination['total'] = $total;
		}

		foreach($options as $key => $option) {
			$this->pagination[$key] = $option;
		}

		$this->is_paginate = true;

		return $this;
	}

	private function initializePagination() {
		if(!$this->is_paginate) {
			return false;
		}

		$this->sql = preg_replace('/\s+(LIMIT|OFFSET)[\w\s\@\<\>\.\,\=\-\'\"\`]+$/mi', ' ', $this->sql);

		if(!isset($this->pagination['total'])) {
			// $total = "SELECT COUNT(*) FROM ({$this->sql}) as total";
			$total_sql = "SELECT COUNT(*) as total FROM " . $this->cutSelectionPartFromSQL($this->sql);

			$total_binding = [];

			foreach($this->binding as $key => $value) {
				if(str_contains($total_sql, ':' . $key)) {
					$total_binding[$key] = $value;
				}
			}

			$total = new Statement($total_sql);

			$this->pagination['total'] = $total->execute($total_binding)->fetchColumn();
		}

		$pagination = new Pagination($this->pagination['total'], $this->pagination);

		$this->sql = rtrim($this->sql, ';') . ' LIMIT :limit OFFSET :offset';

		$this->addBinding('limit', $pagination->limit);
		$this->addBinding('offset', $pagination->offset);

		return true;
	}

	private function cutSelectionPartFromSQL($sql) {
		$output = '';

		$sql_to_array = str_split($sql);

		$left_bracket_count = 0;
		$right_bracket_count = 0;
		$from_position = false;

		$sql_to_array_length = count($sql_to_array);

		for($i = 0; $i < $sql_to_array_length; $i++) {
			if($sql_to_array[$i] == '(') {
				$left_bracket_count += 1;
			}

			if($sql_to_array[$i] == ')') {
				$right_bracket_count += 1;
			}

			if($sql_to_array[$i] == 'f' || $sql_to_array[$i] == 'F') {
				$checkString = $sql_to_array[$i] . $sql_to_array[$i + 1] . $sql_to_array[$i + 2] . $sql_to_array[$i + 3];

				if($checkString == 'from' || $checkString == 'FROM') {
					$from_position = $i;

					if($left_bracket_count == $right_bracket_count) {
						$output = mb_substr($sql, $from_position + 4);

						break;
					}
				}
			}
		}

		return $output;

		// SLOWER
		// $output = '';

    // $paren_count = 0;
    // $from_position = false;

    // for ($i = 0; $i < strlen($sql); $i++) {
    //   if ($sql[$i] == '(') {
    //     $paren_count++;
    //   }
		// 	elseif ($sql[$i] == ')') {
    //     $paren_count--;
    //   }
		// 	elseif (!$from_position && strtolower(mb_substr($sql, $i, 4)) == 'from' && $paren_count == 0) {
    //     $from_position = $i;
    //   }
    // }

    // if ($from_position !== false) {
    //   $output = mb_substr($sql, $from_position + 4);
    // }

    // return trim($output);
	}

	public function execute($params = []) {
		$this->addBinding($params);

		if($this->debug) {
			debug(trim($this->sql ?? ''), $this->binding);
		}

		if($this->cache) {
			return $this;
		}

		$this->filter_binding = array_diff_key($this->binding, $this->filter_binding);

		$this->initializePagination();
		$this->prepare();
		$this->bind();

		$is_execute_success = false;

		try {
			$this->statement->execute();
			$is_execute_success = true;
		}
		catch(PDOException $error) {
			// TODO all codes
			$error_message = $error->getMessage();

			if(preg_match("/Duplicate entry .+ for key '(.+)'/", $error_message, $matches)) {
				$error_message = str_replace(DATABASE['prefix'] . '_', '', $matches[1]);
				$error_message = 'duplicate.' . $error_message;
			}

			if(Request::$method === 'get') {
				if(DEBUG['is_enabled']) {
					debug(__($error_message), $this->sql, $this->binding); // TODO translation
				}
				else {
					debug(__($error_message)); // TODO translation
				}
			}
			else {
				$debug_sql = DEBUG['is_enabled'] ? ['query' => preg_replace('/(\v|\s)+/', ' ', trim($this->sql ?? ''))] : null;
				Server::answer($debug_sql, 'error', __($error_message), '409'); // TODO translation
			}
		}

		if($is_execute_success) {
			$this->setFilterValues();
		}

		return $this;
	}

	private function fetchCache($type, $mode) {
		if($this->cache) {
			$cache_key =  $this->sql . '@' . json_encode($this->binding, JSON_UNESCAPED_UNICODE);

			$cache = Cache::get($cache_key);

			if($cache) {
				return $cache;
			}
			else {
				$this->prepare();
				$this->bind();
				$this->statement->execute();

				$cache = $this->statement->{$type}($mode);

				Cache::set($cache_key, $cache);

				return $cache;
			}
		}

		return $this->statement->{$type}($mode);
	}

	public function fetchAll($mode = PDO::FETCH_OBJ) {
		return $this->fetchCache(__FUNCTION__, $mode);
	}

	public function fetch($mode = PDO::FETCH_OBJ) {
		return $this->fetchCache(__FUNCTION__, $mode);
	}

	public function fetchColumn($column = 0) {
		return intval($this->fetchCache(__FUNCTION__, $column));
	}

	public function insertId() {
		return Database::$connection->lastInsertId();
	}

	public function rowCount() {
		return $this->statement->rowCount();
	}

	private function prepare() {
		$this->statement = Database::$connection->prepare($this->sql);

		return true;
	}

	private function addBinding($key_or_array, $value = null) {
		if(empty($key_or_array)) {
			return false;
		}

		if(is_array($key_or_array)) {
			foreach($key_or_array as $k => $v) {
				$this->binding[strval($k)] = $v;
			}
		}
		else {
			$this->binding[strval($key_or_array)] = $value;
		}

		return true;
	}

	private function bind() {
		if(empty($this->binding)) {
			return false;
		}

		$pdo_param = PDO::PARAM_NULL;

		foreach($this->binding as $key => $value) {
			if(is_bool($value)) $pdo_param = PDO::PARAM_BOOL;
			if(is_int($value)) $pdo_param = PDO::PARAM_INT;
			if(is_string($value)) $pdo_param = PDO::PARAM_STR;

			if(is_array($value) || is_object($value)) {
				$pdo_param = PDO::PARAM_STR;
				$value = json_encode($value, JSON_UNESCAPED_UNICODE);
			}

			$this->statement->bindValue(':' . $key, $value, $pdo_param);
		}

		return true;
	}
}
