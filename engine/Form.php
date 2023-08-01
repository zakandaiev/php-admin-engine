<?php

namespace Engine;

class Form {
	private static $form = [];
	private static $token;
	private static $fields = [];
	private static $is_fields_formatted = false;

	public static function add($form_name) {
		return self::generateToken(__FUNCTION__, $form_name);
	}

	public static function edit($form_name, $item_id) {
		return self::generateToken(__FUNCTION__, $form_name, $item_id);
	}

	public static function delete($form_name, $item_id) {
		return self::generateToken(__FUNCTION__, $form_name, $item_id);
	}

	public static function get($form_name = null) {
		return isset($form_name) ? @self::$form[$form_name] : self::$form;
	}

	public static function has($form_name) {
		return isset(self::$form[$form_name]);
	}

	public static function set($form_name, $data = null) {
		self::$form[$form_name] = $data;

		return true;
	}

	public static function exists($form_name) {
		return is_file(Path::file('form') . "/$form_name.php");
	}

	private static function generateToken($action, $form_name, $item_id = null) {
		if(!self::exists($form_name)) {
			return null;
		}

		if(self::tokenExistsAndActive($action, $form_name, $item_id)) {
			return Request::$base . '/' . self::$token;
		}

		$token = Hash::token();

		$query_params = ['module' => Module::getName(), 'token' => $token, 'action' => $action, 'form_name' => $form_name, 'ip' => Request::$ip];

		$query_append_field = '';
		$query_append_binding = '';
		if($action !== 'add') {
			$query_append_field = ', item_id';
			$query_append_binding = ', :item_id';
			$query_params['item_id'] = $item_id;
		}

		$sql = '
			INSERT INTO {form}
				(token, module, action, form_name, ip' . $query_append_field . ')
			VALUES
				(:token, :module, :action, :form_name, :ip' . $query_append_binding . ')
		';

		$statement = new Statement($sql);
		$statement->execute($query_params);

		return Request::$base . "/$token";
	}

	private static function tokenExistsAndActive($action, $form_name = '', $item_id = '') {
		$query_defining = 'module = :module AND action = :action AND form_name = :form_name AND ip = :ip';
		$query_params = ['module' => Module::getName(), 'action' => $action, 'form_name' => $form_name, 'ip' => Request::$ip];

		if($action !== 'add') {
			$query_defining .= ' AND item_id = :item_id';
			$query_params['item_id'] = $item_id;
		}

		$sql = '
			SELECT
				token
			FROM
				{form}
			WHERE
				' . $query_defining . '
				AND date_created > DATE_SUB(NOW(), INTERVAL ' . LIFETIME['form'] . ' SECOND)
		';

		$statement = new Statement($sql);

		$token_query = $statement->execute($query_params)->fetch();

		if(!isset($token_query->token)) {
			return false;
		}

		self::$token = $token_query->token;

		return true;
	}

	private static function load($form_name) {
		if(self::has($form_name)) {
			return self::get($form_name);
		}

		$form_data = [];

		if(!self::exists($form_name)) {
			return $form_data;
		}

		$form = Path::file('form') . "/$form_name.php";

		$form_data = require $form;

		self::set($form_name, $form_data);

		return $form_data;
	}

	public static function checkFields($form_name) {
		$form = self::load($form_name);

		if(is_closure($form) || empty($form)) {
			return false;
		}

		if(!self::$is_fields_formatted) {
			$method = Request::$method;
			$income_data =  Request::$$method;
			self::formatFields($form['fields'], $income_data);
		}

		$error_messages = [];

		foreach(self::$fields as $field_data) {
			$check = self::isFieldTypeValid($field_data['type'], $field_data['value'], $field_data);

			if($check !== true) {
				$error_messages[$field_data['name']] = $field_data['type'];
			}

			foreach($field_data as $key => $value) {
				$check = self::isFieldValid($key, $value, $field_data);

				if($check !== true) {
					$error_messages[$field_data['name']] = $key;
				}
			}
		}

		$module_name = Module::getName();

		foreach($error_messages as $field_name => $message_key) {
			$message = __("{$module_name}.validation.{$form['table']}.{$field_name}.{$message_key}");

			if(isset($field_data['message']) && isset($field_data['message'][$message_key])) {
				$message = $field_data['message'][$message_key];
			}

			Server::answer(null, 'error', $message, 409);
		}

		return true;
	}

	private static function isFieldTypeValid($type, $value, $field_data) {
		$required = isset($field_data['required']) && $field_data['required'] === true ? true : false;

		if($required && empty($value)) {
			return false;
		}
		else if(!$required && empty($value)) {
			return true;
		}

		switch($type) {
			case 'color': {
				return preg_match('/^#([a-f0-9]{6}|[a-f0-9]{3})$/i', $value) ? true : false;
			}
			case 'date': {
				if((isset($field_data['multiple']) && $field_data['multiple']) || (isset($field_data['range']) && $field_data['range'])) {
					$result = true;

					foreach($value as $v) {
						if(!strtotime($v)) {
							$result = false;
						}
					}

					return $result;
				}

				return strtotime($value) ? true : false;
			}
			case 'email': {
				return filter_var($value, FILTER_VALIDATE_EMAIL) ? true : false;
			}
			case 'url': {
				return filter_var($value, FILTER_VALIDATE_URL) ? true : false;
			}
			case 'time': {
				return preg_match('/^[0-9]{2}:[0-9]{2}$/', $value) ? true : false;
			}
		}

		return true;
	}

	private static function isFieldValid($operand, $operand_value, $field_data) {
		$type = $field_data['type'];
		$value = $field_data['value'];
		$required = isset($field_data['required']) && $field_data['required'] === true ? true : false;

		if(!$required && empty($value)) {
			return true;
		}

		switch($operand) {
			case 'required': {
				if($operand_value && !empty($value)) {
					return true;
				}
				else if(!$operand_value) {
					return true;
				}
				return false;
			}
			case 'min': {
				if(isset($field_data['multiple']) && $field_data['multiple'] && in_array($type, ['date', 'datetime', 'month'])) {
					$result = true;

					foreach($value as $v) {
						if(strtotime($v) >= strtotime($operand_value)) {
							$result = false;
						}
					}

					return $result;
				}
				else if(isset($field_data['range']) && $field_data['range'] && in_array($type, ['date', 'datetime', 'month'])) {
					return strtotime($value[0]) >= strtotime($operand_value) ? true : false;
				}
				else if(isset($field_data['multiple']) && $field_data['multiple']) {
					return count($value ?? []) >= $operand_value ? true : false;
				}

				switch($type) {
					case 'date':
					case 'datetime':
					case 'month': {
						return strtotime($value) >= strtotime($operand_value) ? true : false;
					}
					case 'file': {
						// TODO
						return false;
					}
					case 'number':
					case 'range': {
						return $value >= $operand_value ? true : false;
					}
					case 'wysiwyg': {
						$value = strip_tags($value);
					}
				}

				return mb_strlen($value) >= $operand_value ? true : false;
			}
			case 'max': {
				if(isset($field_data['multiple']) && $field_data['multiple'] && in_array($type, ['date', 'datetime', 'month'])) {
					$result = true;

					foreach($value as $v) {
						if(strtotime($v) <= strtotime($operand_value)) {
							$result = false;
						}
					}

					return $result;
				}
				else if(isset($field_data['range']) && $field_data['range'] && in_array($type, ['date', 'datetime', 'month'])) {
					return strtotime($value[1]) <= strtotime($operand_value) ? true : false;
				}
				else if(isset($field_data['multiple']) && $field_data['multiple']) {
					return count($value ?? []) <= $operand_value ? true : false;
				}

				switch($type) {
					case 'date':
						case 'datetime':
						case 'month': {
							return strtotime($value) <= strtotime($operand_value) ? true : false;
						}
						case 'file': {
						// TODO
						return false;
					}
					case 'number':
					case 'range': {
						return $value <= $operand_value ? true : false;
					}
					case 'wysiwyg': {
						$value = strip_tags($value);
					}
				}

				return mb_strlen($value) <= $operand_value ? true : false;
			}
			case 'extensions': {
				// TODO
				return false;
			}
			case 'multiple': {
				return is_array($value) ? true : false;
			}
			case 'regex': {
				return preg_match($operand_value, $value) ? true : false;
			}
		}

		return true;
	}

	private static function formatFields($fields, $income_data = []) {
		if(self::$is_fields_formatted) {
			return true;
		}

		foreach($fields as $field_name => $field_data) {
			if(!isset($field_data['type'])) {
				continue;
			}

			$income_value = @$income_data[$field_name];

			$field_data['name'] = $field_name;
			$field_data['value'] = isset($income_value) && $income_value === '' ? null : $income_value;
			if(isset($field_data['multiple']) && $field_data['multiple'] && $field_data['value'] === null) {
				$field_data['value'] = [];
			}

			switch($field_data['type']) {
				case 'checkbox':
				case 'switch': {
					$field_data['value'] = $income_value == 'on' ? true : false;
					break;
				}
				case 'file': {
					// TODO
					break;
				}
				case 'number':
				case 'range': {
					$field_data['value'] = isset($income_value) && $income_value !== '' && is_numeric($income_value) ? floatval($income_value) : null;
					break;
				}
				case 'radio': {
					$field_data['value'] = isset($income_value) && $income_value !== '' ? $income_value : null;
					break;
				}
			}

			self::$fields[$field_name] = $field_data;
		}

		return true;
	}

	public static function modifyFields($form_name) {
		$form = self::load($form_name);

		if(is_closure($form) || empty($form)) {
			return false;
		}

		foreach(self::$fields as $key => $field_data) {
			if(isset($field_data['foreign'])) {
				// TODO
				unset(self::$fields[$key]);
				continue;
			}

			if(isset($field_data['unset_null']) && $field_data['unset_null'] && empty($field_data['value'])) {
				unset(self::$fields[$key]);
				continue;
			}

			if(isset($field_data['modify']) && is_closure($field_data['modify'])) {
				self::$fields[$key]['value'] = $field_data['modify']($field_data['value'], $field_data);
			}
		}

		return true;
	}

	public static function execute($action, $form_name, $item_id = null, $force_no_answer = false) {
		self::clearExpired();

		$form = self::load($form_name);

		$method = Request::$method;
		$income_data =  Request::$$method;

		if(is_closure($form)) {
			$data = new \stdClass();
			$data->action = $action;
			$data->form_name = $form_name;
			$data->item_id = $item_id;

			$form($data, $income_data);

			exit;
		}
		else if(empty($form)) {
			Server::answer(null, 'error', __('engine.form.unknown_error'), 406);
		}

		self::formatFields($form['fields'], $income_data);
		self::checkFields($form_name);
		self::processFields($action, $form_name, $item_id, $force_no_answer);

		return true;
	}

	private static function processFields($action, $form_name, $item_id = null, $force_no_answer = false) {
		$form = self::load($form_name);

		if(is_closure($form) || empty($form)) {
			return false;
		}

		$form_data = [
			'action' => $action,
			'form_name' => $form_name,
			'item_id' => $item_id,
			'force_no_answer' => $force_no_answer,
			'table' => $form['table'],
			'pk_name' => null,
			'columns' => [],
			'sql' => null,
			'sql_binding' => [],
			'rowCount' => 0
		];

		if($action !== 'delete') {
			self::modifyFields($form_name);
			$form_data['columns'] = array_keys(self::$fields);
		}

		$pk_statement = new Statement('SHOW KEYS FROM {' . $form_data['table'] . '} WHERE Key_name=\'PRIMARY\'');
		$form_data['pk_name'] = $pk_statement->execute()->fetch()->Column_name;

		if(isset($form['modify_fields']) && is_closure($form['modify_fields'])) {
			self::$fields = $form['modify_fields'](self::$fields, $form_data);
		}

		if(isset($form['execute_pre']) && is_closure($form['execute_pre'])) {
			$form['execute_pre'](self::$fields, $form_data);
		}

		if(isset($form['execute']) && is_closure($form['execute'])) {
			$form['execute'](self::$fields, $form_data);
			exit;
		}

		// TODO
		// $fields_foreign = [];
		// $fields_foreign_value = [];
		// if($action !== 'delete') {
		// 	foreach($form['fields'] as $field => $values_array) {
		// 		if(isset($values_array['foreign']) && !empty($values_array['foreign'])) {

		// 			if(is_closure($values_array['foreign'])) {
		// 				$fields_foreign[$field] = $values_array['foreign'];
		// 			} else {
		// 				$foreign_t = explode('@', $values_array['foreign'], 2);
		// 				$foreign_k = explode('/', $foreign_t[1], 2);

		// 				$foreign_table = $foreign_t[0];
		// 				$foreign_key_1 = $foreign_k[0];
		// 				$foreign_key_2 = $foreign_k[1];

		// 				$fields_foreign[$field]['table'] = $foreign_table;
		// 				$fields_foreign[$field]['key_1'] = $foreign_key_1;
		// 				$fields_foreign[$field]['key_2'] = $foreign_key_2;
		// 			}

		// 			if(is_array($fields[$field])) {
		// 				$fields_foreign_value[$field] = $fields[$field];
		// 			} else if(@json_decode($fields[$field]) || $fields[$field] === '[]') {
		// 				$fields_foreign_value[$field] = json_decode($fields[$field]) ?? [];
		// 			} else if(!empty($fields[$field])) {
		// 				$fields_foreign_value[$field] = array($fields[$field]);
		// 			} else {
		// 				$fields_foreign_value[$field] = [];
		// 			}

		// 			unset($fields[$field]);
		// 		}
		// 	}
		// }

		switch($action) {
			case 'add': {
				$columns = implode(', ', $form_data['columns']);
				$bindings = ':' . implode(', :', $form_data['columns']);
				$form_data['sql'] = 'INSERT INTO {' . $form_data['table'] . '} (' . $columns . ') VALUES (' . $bindings . ')';
				break;
			}
			case 'edit': {
				$bindings = array_reduce($form_data['columns'],function($carry,$v){return ($carry?"$carry, ":'')."$v = :$v";});
				$form_data['sql'] = 'UPDATE {' . $form_data['table'] . '} SET ' . $bindings . ' WHERE ' . $form_data['pk_name'] . ' = :' . $form_data['pk_name'];
				break;
			}
			case 'delete': {
				$form_data['sql'] = 'DELETE FROM {' . $form_data['table'] . '} WHERE ' . $form_data['pk_name'] . ' = :' . $form_data['pk_name'];
				break;
			}
			default: {
				Server::answer(null, 'error', __('engine.form.unknown_error'), 406);
			}
		}

		if(isset($form['modify_sql']) && is_closure($form['modify_sql'])) {
			$form_data['sql'] = $form['modify_sql']($form_data['sql'], self::$fields, $form_data);
		}

		foreach(self::$fields as $field) {
			$form_data['sql_binding'][$field['name']] = $field['value'];
		}

		if($action !== 'add') {
			$form_data['sql_binding'][$form_data['pk_name']] = $form_data['item_id'];
		}

		if(isset($form['modify_sql_binding']) && is_closure($form['modify_sql_binding'])) {
			$form_data['sql_binding'] = $form['modify_sql_binding']($form_data['sql_binding'], self::$fields, $form_data);
		}

		$statement = new Statement($form_data['sql']);
		$statement->execute($form_data['sql_binding']);

		$form_data['rowCount'] = $statement->rowCount();

		if($action === 'add') {
			$form_data['item_id'] = $statement->insertId();
		}

		// foreach($fields_foreign as $field_name => $field) {
		// 	if(is_closure($field)) {
		// 		$data = new \stdClass();

		// 		$data->fields = $fields;
		// 		$data->form_data = $form_data;

		// 		$field($fields_foreign_value[$field_name], $data);
		// 	}
		// 	else if(is_array($field)) {
		// 		$sql = 'DELETE FROM {' . $field['table'] . '} WHERE ' . $field['key_1'] . ' = :' . $field['key_1'];

		// 		$statement = new Statement($sql);
		// 		$statement->execute([$field['key_1'] => $item_id]);

		// 		if(empty($fields_foreign_value[$field_name])) {
		// 			continue;
		// 		}

		// 		foreach($fields_foreign_value[$field_name] as $value) {
		// 			$sql = '
		// 				INSERT INTO {' . $field['table'] . '}
		// 					(' . $field['key_1'] . ', ' . $field['key_2'] . ')
		// 				VALUES
		// 					(:' . $field['key_1'] . ', :' . $field['key_2'] . ')
		// 			';

		// 			$statement = new Statement($sql);
		// 			$statement->execute([$field['key_1'] => $item_id, $field['key_2'] => $value]);
		// 		}
		// 	}
		// }

		if(isset($form['execute_post']) && is_closure($form['execute_post'])) {
			$form['execute_post']($form_data['rowCount'], self::$fields, $form_data);
		}

		if(isset($form['submit_message']) && is_closure($form['submit_message'])) {
			$submit_message = $form['submit_message'](self::$fields, $form_data);
		}
		else if(isset($form['submit_message'])) {
			$submit_message = $form['submit_message'];
		}

		if(!$force_no_answer) {
			Server::answer(null, 'success', @$submit_message);
		}

		return true;
	}

	private static function clearExpired() {
		$statement = new Statement('DELETE FROM {form} WHERE date_created <= DATE_SUB(NOW(), INTERVAL ' . intval(LIFETIME['form']) * 2 . ' SECOND)');
		$statement->execute();

		return true;
	}

	public static function XXX_processFields($form_name) {
		$form = self::load($form_name);

		if(empty($form)) {
			return [];
		}

		$post = Request::$post;
		$files = Request::$files;
		$fields = [];

		foreach($form['fields'] as $field => $values_array) {
			$field_value = null;

			if(!isset($post[$field]) || empty($post[$field])) {
				if(isset($values_array['boolean']) && $values_array['boolean']) {
					$field_value = false;
				}
				if(isset($values_array['unset_null']) && $values_array['unset_null']) {
					continue;
				}

				$fields[$field] = $field_value;
			} else {
				$field_value = $post[$field];
				$is_field_formatted = false;

				if(isset($values_array['boolean']) && $values_array['boolean']) {
					$field_value = $field_value === 'null' ? false : true;
					$is_field_formatted = true;
				}
				if(isset($values_array['html']) && $values_array['html']) {
					$field_value = trim($field_value ?? '');
					$is_field_formatted = true;
				}
				if(isset($values_array['json']) && $values_array['json']) {
					$field_value = trim($field_value ?? '');
					$is_field_formatted = true;
				}
				if(isset($values_array['foreign'])) {
					$field_value = $field_value;
					$is_field_formatted = true;
				}
				if(is_array($field_value)) {
					$field_value = json_encode($field_value);
					$is_field_formatted = true;
				}

				if(!$is_field_formatted) {
					$field_value = trim($field_value ?? '');
				}

				$fields[$field] = $field_value;
			}

			if(isset($values_array['modify']) && is_closure($values_array['modify'])) {
				$fields[$field] = $values_array['modify']($fields[$field]);
			}
		}

		foreach($form['fields'] as $field => $values_array) {
			if(isset($values_array['file']) && $values_array['file']) {

				if(!isset($files[$field]) || empty($files[$field]['tmp_name'])) {
					continue;
				}

				$files_array = [];
				$is_multiple = false;

				if(is_array($files[$field]['tmp_name'])) {
					$is_multiple = true;
					foreach($files[$field] as $key => $file) {
						foreach($file as $num => $val) {
							$files_array[$num][$key] = $val;
						}
					}
				} else {
					$files_array[] = $files[$field];
				}

				foreach($files_array as $file) {
					if(empty($file['tmp_name'])) {
						continue;
					}

					$upload = Upload::file($file, $values_array['folder'] ?? null, $values_array['extensions'] ?? null);

					if($upload->status === true) {
						if(!$is_multiple) {
							$fields[$field] = $upload->message;
							continue;
						}

						if(isset($fields[$field]) && !empty($fields[$field])) {
							$fields[$field] = json_decode($fields[$field]);

							if(!empty($fields[$field])) {
								$fields[$field][] = $upload->message;
							} else {
								$fields[$field] = array($upload->message);
							}
						} else {
							$fields[$field] = array($upload->message);
						}

						$fields[$field] = json_encode($fields[$field]);
					} else {
						Server::answer(null, 'error', $upload->message, 415);
					}
				}
			}
		}

		return $fields;
	}

	public static function populateFiles($files = null) {
		$output_array = [];

		if(empty($files)) {
			return json_encode($output_array);
		}

		if(is_array($files)) {
			$files_array = $files;
		} else if($files[0] === "[") {
			$files_array = json_decode($files);
		} else {
			$files_array = array($files);
		}

		foreach($files_array as $file) {
			$poster = Request::$base . '/' . $file;

			$output_array[] = [
				'value' => $file,
				'poster' => $poster
			];
		}

		return json_encode($output_array);
	}
}
