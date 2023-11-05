<?php

namespace Engine;

class Form
{
	protected static $form = [];
	protected static $token;
	protected static $fields = [];
	protected static $form_formatted_fields = [];

	public static function add($form_name, $is_translation = false)
	{
		return self::generateToken(__FUNCTION__, $form_name, null, $is_translation);
	}

	public static function edit($form_name, $item_id, $is_translation = false)
	{
		return self::generateToken(__FUNCTION__, $form_name, $item_id, $is_translation);
	}

	public static function delete($form_name, $item_id, $is_translation = false)
	{
		return self::generateToken(__FUNCTION__, $form_name, $item_id, $is_translation);
	}

	public static function get($form_name = null)
	{
		return isset($form_name) ? @self::$form[$form_name] : self::$form;
	}

	public static function has($form_name)
	{
		return isset(self::$form[$form_name]);
	}

	public static function set($form_name, $data = null)
	{
		self::$form[$form_name] = $data;

		return true;
	}

	public static function exists($form_name)
	{
		return is_file(Path::file('form') . "/$form_name.php");
	}

	public static function checkFields($form_name)
	{
		$form = self::load($form_name);

		if (is_closure($form) || empty($form)) {
			return false;
		}

		if (!in_array($form_name, self::$form_formatted_fields)) {
			$income_data =  Request::get();
			self::formatFields($form_name, $form['fields'], $income_data);
		}

		$error_messages = [];

		foreach (self::$fields as $field_data) {
			$check = self::isFieldTypeValid($field_data['type'], $field_data['value'], $field_data);

			if ($check !== true) {
				$error_messages[$field_data['name']] = $field_data['type'];
			}

			foreach ($field_data as $key => $value) {
				$check = self::isFieldValid($key, $value, $field_data);

				if ($check !== true) {
					$error_messages[$field_data['name']] = $key;
				}
			}
		}

		$module_name = Module::getName();

		foreach ($error_messages as $field_name => $message_key) {
			$message = __("{$module_name}.{$form['table']}.{$field_name}.validation.{$message_key}");

			if (isset($field_data['message'][$message_key])) {
				$message = $field_data['message'][$message_key];
			}

			Server::answer(null, 'error', $message, 405);
		}

		return true;
	}

	public static function execute($action, $form_name, $item_id = null, $is_translation = false, $force_no_answer = false)
	{
		self::clearExpired();

		$form = self::load($form_name, $is_translation);

		$income_data =  Request::get();

		if (is_closure($form)) {
			$data = new \stdClass();
			$data->action = $action;
			$data->form_name = $form_name;
			$data->item_id = $item_id;

			$form($data, $income_data);

			exit;
		} else if (empty($form)) {
			Server::answer(null, 'error', __('engine.form.unknown_error'), 406);
		}

		if ($action !== 'delete') {
			self::formatFields($form_name, $form['fields'], $income_data);
			self::checkFields($form_name);
		}
		self::processFields($action, $form_name, $item_id, $force_no_answer);

		return true;
	}

	protected static function generateToken($action, $form_name, $item_id = null, $is_translation = false)
	{
		if (!self::exists($form_name)) {
			return null;
		}

		if (self::tokenExistsAndActive($action, $form_name, $item_id, $is_translation)) {
			return Request::base() . '/' . self::$token;
		}

		$token = Hash::token();

		$query_params = [
			'module' => Module::getName(),
			'token' => $token,
			'action' => $action,
			'form_name' => $form_name,
			'ip' => Request::ip(),
			'is_translation' => $is_translation
		];

		$query_append_field = '';
		$query_append_binding = '';
		if ($action !== 'add') {
			$query_append_field = ', item_id';
			$query_append_binding = ', :item_id';
			$query_params['item_id'] = $item_id;
		}

		$sql = '
			INSERT INTO {form}
				(token, module, action, form_name, is_translation, ip' . $query_append_field . ')
			VALUES
				(:token, :module, :action, :form_name, :is_translation, :ip' . $query_append_binding . ')
		';

		$statement = new Statement($sql);
		$statement->execute($query_params);

		return Request::base() . "/$token";
	}

	protected static function tokenExistsAndActive($action, $form_name = '', $item_id = '', $is_translation = false)
	{
		$query_defining = 'module = :module AND action = :action AND form_name = :form_name AND is_translation = :is_translation AND ip = :ip';
		$query_params = [
			'module' => Module::getName(),
			'action' => $action,
			'form_name' => $form_name,
			'is_translation' => $is_translation,
			'ip' => Request::ip()
		];

		if ($action !== 'add') {
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

		if (!isset($token_query->token)) {
			return false;
		}

		self::$token = $token_query->token;

		return true;
	}

	protected static function clearExpired()
	{
		$statement = new Statement('DELETE FROM {form} WHERE date_created <= DATE_SUB(NOW(), INTERVAL ' . intval(LIFETIME['form']) * 2 . ' SECOND)');
		$statement->execute();

		return true;
	}

	protected static function load($form_name, $is_translation = false)
	{
		if (self::has($form_name)) {
			return self::get($form_name);
		}

		$form_data = [];

		if (!self::exists($form_name)) {
			return $form_data;
		}

		$form = Path::file('form') . "/$form_name.php";

		$form_data = require $form;

		if ($is_translation) {
			$form_name = $form_name . '_translation';

			$form_data_translation = [
				'table' => $form_data['table'] . '_translation',
				'fields' => [],
				'modify_sql' => function ($sql, $fields, $data) {
					if ($data['action'] === 'edit') {
						$sql .= ' AND language = :language';
					}

					return $sql;
				},
				'message' => @$form_data['submit_message_translation']
			];

			$translation_fields = $form_data['translation'] ?? [];

			foreach ($form_data['fields'] as $key => $field) {
				if (in_array($key, $translation_fields)) {
					$form_data_translation['fields'][$key] = $field;
				}
			}

			$form_data = $form_data_translation;
		}

		self::set($form_name, $form_data);

		return $form_data;
	}

	protected static function formatFields($form_name, $fields, $income_data = [])
	{
		if (in_array($form_name, self::$form_formatted_fields)) {
			return true;
		}

		self::$fields = [];

		foreach ($fields as $field_name => $field_data) {
			if (!isset($field_data['type'])) {
				continue;
			}

			$income_value = @$income_data[$field_name];

			$field_data['name'] = $field_name;
			$field_data['value'] = isset($income_value) && $income_value === '' ? null : $income_value;
			if (isset($field_data['multiple']) && $field_data['multiple'] && $field_data['value'] === null) {
				$field_data['value'] = [];
			}

			switch ($field_data['type']) {
				case 'switch': {
						$field_data['value'] = in_array($income_value, ['on', '1', 'true']) ? true : false;
						break;
					}
				case 'file': {
						$field_data['upload'] = null;
						$field_data['to_upload'] = false;
						$field_data['max_size'] = $field_data['max_size'] ?? Upload::getMaxSize();

						$files = Request::files($field_data['name']) ?? [];

						if (empty($files) || !isset($files['tmp_name']) || empty($files['tmp_name'])) {
							break;
						}

						$files_formatted = [];
						if (is_array($files['tmp_name'])) {
							for ($i = 0; $i < count($files['name']); $i++) {
								if (@$files['error'][$i] || empty($files['tmp_name'][$i]) || empty($files['name'][$i]) || empty($files['size'][$i])) {
									continue;
								}

								$files_formatted[] = array(
									'name' => $files['name'][$i],
									'type' => $files['type'][$i],
									'tmp_name' => $files['tmp_name'][$i],
									'error' => $files['error'][$i],
									'size' => $files['size'][$i],
									'full_path' => $files['full_path'][$i]
								);
							}
						} else {
							$files_formatted = [$files];
						}

						$field_data['value'] = $files_formatted;
						$field_data['to_upload'] = true;

						break;
					}
				case 'number':
				case 'range': {
						$field_data['value'] = isset($income_value) && $income_value !== '' && is_numeric($income_value) ? floatval($income_value) : null;
						break;
					}
			}

			self::$fields[$field_name] = $field_data;
		}

		self::$form_formatted_fields[] = $form_name;

		return true;
	}

	protected static function isFieldTypeValid($type, $value, $field_data)
	{
		$required = isset($field_data['required']) && $field_data['required'] === true ? true : false;

		if ($required && empty($value)) {
			return false;
		} else if (!$required && empty($value)) {
			return true;
		}

		switch ($type) {
			case 'color': {
					return preg_match('/^#([a-f0-9]{6}|[a-f0-9]{3})$/i', $value ?? '') ? true : false;
				}
			case 'date': {
					if ((isset($field_data['multiple']) && $field_data['multiple']) || (isset($field_data['range']) && $field_data['range'])) {
						$result = true;

						foreach ($value as $v) {
							if (!strtotime($v)) {
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
					return preg_match('/^[0-9]{2}:[0-9]{2}$/', $value ?? '') ? true : false;
				}
		}

		return true;
	}

	protected static function isFieldValid($operand, $operand_value, $field_data)
	{
		$type = $field_data['type'];
		$value = $field_data['value'];
		$required = isset($field_data['required']) && $field_data['required'] === true ? true : false;

		if (!$required && empty($value)) {
			return true;
		}

		switch ($operand) {
			case 'required': {
					if ($operand_value && !empty($value)) {
						return true;
					} else if (!$operand_value) {
						return true;
					}
					return false;
				}
			case 'min': {
					if (isset($field_data['multiple']) && $field_data['multiple'] && in_array($type, ['date', 'datetime', 'month'])) {
						$result = true;

						foreach ($value as $v) {
							if (strtotime($v) >= strtotime($operand_value)) {
								$result = false;
							}
						}

						return $result;
					} else if (isset($field_data['range']) && $field_data['range'] && in_array($type, ['date', 'datetime', 'month'])) {
						return strtotime($value[0]) >= strtotime($operand_value) ? true : false;
					} else if (isset($field_data['multiple']) && $field_data['multiple']) {
						return count($value ?? []) >= $operand_value ? true : false;
					}

					switch ($type) {
						case 'date':
						case 'datetime':
						case 'month': {
								return strtotime($value) >= strtotime($operand_value) ? true : false;
							}
						case 'number':
						case 'range': {
								return $value >= $operand_value ? true : false;
							}
						case 'wysiwyg': {
								$value = html($value);
							}
					}

					return mb_strlen($value ?? '') >= $operand_value ? true : false;
				}
			case 'max': {
					if (isset($field_data['multiple']) && $field_data['multiple'] && in_array($type, ['date', 'datetime', 'month'])) {
						$result = true;

						foreach ($value as $v) {
							if (strtotime($v) <= strtotime($operand_value)) {
								$result = false;
							}
						}

						return $result;
					} else if (isset($field_data['range']) && $field_data['range'] && in_array($type, ['date', 'datetime', 'month'])) {
						return strtotime($value[1]) <= strtotime($operand_value) ? true : false;
					} else if (isset($field_data['multiple']) && $field_data['multiple']) {
						return count($value ?? []) <= $operand_value ? true : false;
					}

					switch ($type) {
						case 'date':
						case 'datetime':
						case 'month': {
								return strtotime($value) <= strtotime($operand_value) ? true : false;
							}
						case 'number':
						case 'range': {
								return $value <= $operand_value ? true : false;
							}
						case 'wysiwyg': {
								$value = html($value);
							}
					}

					return mb_strlen($value ?? '') <= $operand_value ? true : false;
				}
			case 'extensions': {
					if (isset($field_data['to_upload']) && $field_data['to_upload'] === false) {
						return true;
					}

					foreach ($value as $file) {
						$file_extension = strtolower(file_extension($file['name']));

						$allowed_extensions = is_array($operand_value) ? $operand_value : UPLOAD['extensions'];

						if (!in_array($file_extension, $allowed_extensions)) {
							return false;
						}
					}

					return true;
				}
			case 'max_size': {
					if (isset($field_data['to_upload']) && $field_data['to_upload'] === false) {
						return true;
					}

					foreach ($value as $file) {
						if ($file['size'] > $field_data['max_size']) {
							return false;
						}
					}

					return true;
				}
			case 'multiple': {
					return is_array($value) ? true : false;
				}
			case 'regex': {
					return preg_match($operand_value, $value ?? '') ? true : false;
				}
		}

		return true;
	}

	protected static function modifyFields()
	{
		foreach (self::$fields as $key => $field_data) {
			if (isset($field_data['unset_null']) && $field_data['unset_null'] && empty($field_data['value']) && $field_data['value'] != 0 && $field_data['value'] != false) {
				unset(self::$fields[$key]);
				continue;
			}

			if (isset($field_data['modify']) && is_closure($field_data['modify'])) {
				self::$fields[$key]['value'] = $field_data['modify']($field_data['value'], $field_data);
			}
		}

		return true;
	}

	protected static function prepareMediaFields()
	{
		foreach (self::$fields as $key => $field_data) {
			if ($field_data['type'] !== 'file' || empty($field_data['value']) || !isset($field_data['value'][0]['tmp_name'])) {
				continue;
			}

			$value = $field_data['value'];

			$upload = new Upload($value, @$field_data['folder'], @$field_data['extensions']);

			if (!$upload->get('status')) {
				Server::answer(null, 'error', $upload->get('message'), 415);
			}

			if (!isset($field_data['multiple']) || !$field_data['multiple']) {
				self::$fields[$key]['value'] = @$upload->get('files')[0];
			} else {
				self::$fields[$key]['value'] = $upload->get('files') ?? [];
			}

			self::$fields[$key]['upload'] = $upload;
		}

		return true;
	}

	protected static function uploadMediaFields()
	{
		foreach (self::$fields as $key => $field_data) {
			if ($field_data['type'] !== 'file' || !isset($field_data['upload']) || empty($field_data['upload'])) {
				continue;
			}

			$field_data['upload']->execute();
		}

		return true;
	}

	protected static function processFields($action, $form_name, $item_id = null, $force_no_answer = false)
	{
		$form = self::load($form_name);

		if (is_closure($form) || empty($form)) {
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

		$pk_statement = new Statement('SHOW KEYS FROM {' . $form_data['table'] . '} WHERE Key_name=\'PRIMARY\'');
		$form_data['pk_name'] = $pk_statement->execute()->fetch()->Column_name;
		if (empty($form_data['pk_name'])) {
			Server::answer(null, 'error', __('engine.form.unknown_error'), 409);
		}

		self::prepareMediaFields();

		if (isset($form['modify_fields']) && is_closure($form['modify_fields'])) {
			self::$fields = $form['modify_fields'](self::$fields, $form_data);
		}

		self::modifyFields();

		$foreign_data = self::getForeignFields();
		$translation_data = self::getTranslationFields($form_data['form_name']);

		$form_data['columns'] = array_keys(self::$fields);
		if (!empty($form_data['item_id'])) {
			$form_data['columns'][] = $form_data['pk_name'];
		}

		switch ($form_data['action']) {
			case 'add': {
					$columns = implode(', ', $form_data['columns']);
					$bindings = ':' . implode(', :', $form_data['columns']);
					$form_data['sql'] = 'INSERT INTO {' . $form_data['table'] . '} (' . $columns . ') VALUES (' . $bindings . ')';
					break;
				}
			case 'edit': {
					$bindings = array_reduce($form_data['columns'], function ($carry, $v) {
						return ($carry ? "$carry, " : '') . "$v = :$v";
					});
					$form_data['sql'] = 'UPDATE {' . $form_data['table'] . '} SET ' . $bindings . ' WHERE ' . $form_data['pk_name'] . ' = :' . $form_data['pk_name'];
					break;
				}
			case 'delete': {
					$form_data['sql'] = 'DELETE FROM {' . $form_data['table'] . '} WHERE ' . $form_data['pk_name'] . ' = :' . $form_data['pk_name'];
					break;
				}
			default: {
					Server::answer(null, 'error', __('engine.form.unknown_error'), 409);
				}
		}

		if (isset($form['modify_sql']) && is_closure($form['modify_sql'])) {
			$form_data['sql'] = $form['modify_sql']($form_data['sql'], self::$fields, $form_data);
		}

		foreach (self::$fields as $field) {
			$form_data['sql_binding'][$field['name']] = $field['value'];
		}

		if (in_array($form_data['pk_name'], $form_data['columns'])) {
			$form_data['sql_binding'][$form_data['pk_name']] = $form_data['item_id'];
		}

		if (isset($form['modify_sql_binding']) && is_closure($form['modify_sql_binding'])) {
			$form_data['sql_binding'] = $form['modify_sql_binding']($form_data['sql_binding'], self::$fields, $form_data);
		}

		if (isset($form['execute_pre']) && is_closure($form['execute_pre'])) {
			$form['execute_pre'](self::$fields, $form_data);
		}

		$statement = new Statement($form_data['sql']);
		$statement->execute($form_data['sql_binding']);

		$form_data['rowCount'] = $statement->rowCount();

		if ($form_data['action'] === 'add') {
			$form_data['item_id'] = $statement->insertId();
		}

		self::uploadMediaFields();
		self::processForeignFields($foreign_data, $form_data);
		self::processTranslationFields($translation_data, $form_data);

		if (isset($form['execute_post']) && is_closure($form['execute_post'])) {
			$form['execute_post']($form_data['rowCount'], self::$fields, $form_data);
		}

		if (isset($form['submit_message']) && is_closure($form['submit_message'])) {
			$submit_message = $form['submit_message'](self::$fields, $form_data);
		} else if (isset($form['submit_message'])) {
			$submit_message = $form['submit_message'];
		}

		if (!$form_data['force_no_answer']) {
			Server::answer(null, 'success', @$submit_message);
		}

		return true;
	}

	protected static function getForeignFields()
	{
		$data = [];

		foreach (self::$fields as $key => $field_data) {
			if (!isset($field_data['foreign']) || empty($field_data['foreign'])) {
				continue;
			}

			$field_name = $field_data['name'];
			$foreign = $field_data['foreign'];

			$data[$field_name]['value'] = $field_data['value'];

			if (is_closure($foreign)) {
				$data[$field_name]['closure'] = $foreign;
			} else {
				preg_match('/(\w+)\@(\w+)\/(\w+)/i', $foreign, $matches);

				if (empty($matches) || count($matches) !== 4) {
					continue;
				}

				$data[$field_name]['table'] = $matches[1];
				$data[$field_name]['key_pk'] = $matches[2];
				$data[$field_name]['key_fk'] = $matches[3];
			}

			unset(self::$fields[$key]);
		}

		return $data;
	}

	protected static function processForeignFields($foreign_data, $form_data)
	{
		if (empty($foreign_data) || empty($form_data)) {
			return false;
		}

		foreach ($foreign_data as $foreign) {
			if (isset($foreign['closure']) && is_closure($foreign['closure'])) {
				$foreign['closure']($foreign['value'], $form_data);
			} else if (isset($foreign['table']) && isset($foreign['key_pk']) && isset($foreign['key_fk'])) {
				$table = $foreign['table'];
				$key_pk = $foreign['key_pk'];
				$key_fk = $foreign['key_fk'];
				$value = $foreign['value'];
				$item_id = $form_data['item_id'];

				$sql = 'DELETE FROM {' . $table . '} WHERE ' . $key_pk . ' = :' . $key_pk;

				$statement = new Statement($sql);
				$statement->execute([$key_pk => $item_id]);

				if (empty($value)) {
					continue;
				}

				$value = is_array($value) ? $value : [$value];

				foreach ($value as $v) {
					$sql = '
						INSERT INTO {' . $table . '}
							(' . $key_pk . ', ' . $key_fk . ')
						VALUES
							(:' . $key_pk . ', :' . $key_fk . ')
					';

					$statement = new Statement($sql);
					$statement->execute([$key_pk => $item_id, $key_fk => $v]);
				}
			}
		}

		return true;
	}

	protected static function getTranslationFields($form_name)
	{
		$data = [];

		$form_data = self::get($form_name);

		if (!isset($form_data['translation']) || empty($form_data['translation'])) {
			return false;
		}

		foreach ($form_data['translation'] as $key) {
			if (!isset(self::$fields[$key])) {
				continue;
			}

			$data[$key] = self::$fields[$key];

			unset(self::$fields[$key]);
		}

		return $data;
	}

	protected static function processTranslationFields($fields, $form_data)
	{
		if (!is_array($fields) || empty($form_data) || empty($form_data['item_id']) || ($form_data['action'] !== 'delete' && empty($fields)) || str_contains($form_data['table'], '_translation')) {
			return false;
		}

		if (!isset($fields['language']['value']) || empty($fields['language']['value'])) {
			$fields['language']['type'] = 'hidden';
			$fields['language']['value'] = Language::current();
		}

		$name = $form_data['form_name'] . '_translation';
		$table = $form_data['table'] . '_translation';
		$action = $form_data['action'];
		$data = [
			'table' => $table,
			'fields' => $fields,
			'modify_sql' => function ($sql, $fields, $data) {
				if ($data['action'] === 'edit') {
					$sql .= ' AND language = :language';
				}

				return $sql;
			},
		];

		self::set($name, $data);
		self::execute($action, $name, $form_data['item_id'], false, true);

		return true;
	}
}
