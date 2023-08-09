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
	private $is_select;
	private $is_paginating = false;
	private $pagination = [];

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

	public function filter($name, $set_options = true) {
		if(!$this->is_select) {
			return $this;
		}

		$filter = new Filter($name);

		if($set_options) {
			$this->setFilterValues($filter);
		}

		if(empty($filter->sql) && empty($filter->order)) {
			return $this;
		}

		$sql = !empty($filter->sql) ? "WHERE {$filter->sql}" : '';
		$sql = "SELECT * FROM ({$this->sql}) t_filter $sql";

		foreach($filter->binding as $key => $value) {
			$this->addBinding($key, $value);
		}

		if(!empty($filter->order)) {
			$sql .= " ORDER BY {$filter->order}";
		}

		$this->sql = $sql;

		return $this;
	}

	private function setFilterValues($filter) {
		if(empty($filter->data) || preg_match('/GROUP\s+BY/mi',  $this->sql)) {
			return false;
		}

		$sql_cuted = $this->cutSelectionPartFromSQL($this->sql);
		$sql_cuted = preg_replace('/\s*ORDER\s+BY\s+[\w\s\@\<\>\.\,\=\-\'\"\`]+$/mi', '', $sql_cuted);

		foreach($filter->data as $alias => $filter_data) {
			if(is_array($filter_data['column']) || !in_array($filter_data['type'], ['checkbox','radio','select'])) {
				continue;
			}

			$filter_sql = "SELECT COUNT(*) as `count`, {$filter_data['column']} as `id`, {$filter_data['column']} as `text` FROM $sql_cuted";

			$filter_binding = $this->binding;

			$filter_data['show_all_options'] = $filter_data['show_all_options'] ?? false;

			if(!$filter_data['show_all_options'] && !empty($filter->sql)) {
				$filter_sql .= " WHERE {$filter->sql}";

				foreach($filter->binding as $key => $value) {
					$filter_binding[$key] = $value;
				}
			}

			$filter_sql .= " GROUP BY {$filter_data['column']} ORDER BY count DESC";

			$filter_options = new Statement($filter_sql);

			$filter_options = $filter_options->execute($filter_binding)->fetchAll();

			$filter->setOptions($alias, $filter_options);
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

		$this->is_paginating = true;

		return $this;
	}

	private function initializePagination() {
		if(!$this->is_paginating) {
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
		// 	elseif (!$from_position && strtolower(substr($sql, $i, 4)) == 'from' && $paren_count == 0) {
    //     $from_position = $i;
    //   }
    // }

    // if ($from_position !== false) {
    //   $output = substr($sql, $from_position + 4);
    // }

    // return trim($output);
	}

	public function execute($params = []) {
		if($this->cache) {
			$this->addBinding($params);

			if($this->debug) {
				debug(trim($this->sql ?? ''), $this->binding);
			}

			return $this;
		}

		$this->addBinding($params);

		if($this->debug) {
			debug(trim($this->sql ?? ''), $this->binding);
		}

		$this->initializePagination();
		$this->prepare();
		$this->bind();

		try {
			$this->statement->execute();
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

		return $this;
	}

	private function fetchCache($type, $mode) {
		if($this->cache) {
			$cache_key =  $this->sql . '@' . json_encode($this->binding);

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

	public function fetchColumn(int $column = 0) {
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
				$value = json_encode($value);
			}

			$this->statement->bindValue(':' . $key, $value, $pdo_param);
		}

		return true;
	}
}
