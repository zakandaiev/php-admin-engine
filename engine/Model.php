<?php

namespace Engine;

abstract class Model
{
	protected static $instances = [];

	public function __construct()
	{
		self::$instances[get_called_class()] = $this;
	}

	public static function getInstance()
	{
		$class = get_called_class();

		if (!array_key_exists($class, self::$instances)) {
			self::$instances[$class] = new $class();
		}

		return self::$instances[$class];
	}

	public function createTranslation($table, $data)
	{
		if (!is_array($data) || empty($data) || !isset($data['language'])) {
			return false;
		}

		$columns = implode(', ', array_keys($data));
		$bindings = ':' . implode(', :', array_keys($data));

		$sql = 'INSERT INTO {' . $table . '} (' . $columns . ') VALUES (' . $bindings . ')';

		$statement = new Statement($sql);

		$statement->execute($data);

		if ($statement->rowCount() > 0) {
			return true;
		}

		return false;
	}

	protected function executeStatementWithOptions($sql, $options = [])
	{
		// TODO
		foreach (['fields', 'where', 'order'] as $replacement_type) {
			$replacement = '$2';
			$replacement_pattern = '/\/' . $replacement_type . '(\+)?\/([\s\S]+)\/' . $replacement_type . '[\+]?\//miu';

			if (isset($options[$replacement_type])) {
				$replacement_match = preg_match($replacement_pattern, $sql, $replacement_matches);

				if ($replacement_match && is_array($replacement_matches) && count($replacement_matches) > 1) {
					$replacement = ($replacement_matches[1] == '+') ? "{$replacement_matches[2]}, {$options[$replacement_type]}" : $options[$replacement_type];
				}
			}

			$sql = preg_replace($replacement_pattern, $replacement, $sql);
		}

		$options['limit'] = @$options['limit'];
		$options['offset'] = @$options['offset'];
		$options['filter'] = @$options['filter'];
		$options['paginate'] = @$options['paginate'];
		$options['cache'] = @$options['cache'];
		$options['debug'] = @$options['debug'];

		if ($options['limit'] && !$options['paginate']) {
			$sql .= " LIMIT {$options['limit']}";
		}

		if ($options['offset'] && !$options['paginate']) {
			$sql .= " OFFSET {$options['offset']}";
		}

		$statement = new Statement($sql, $options['cache'], $options['debug']);

		if ($options['filter']) {
			$statement->filter($options['filter']);
		}

		if ($options['paginate']) {
			if ($options['limit']) {
				$statement->paginate(null, ['limit' => $options['limit']]);
			} else {
				$statement->paginate();
			}
		}

		unset(
			$options['fields'],
			$options['where'],
			$options['order'],
			$options['limit'],
			$options['offset'],
			$options['filter'],
			$options['paginate'],
			$options['debug']
		);

		return $statement->execute($options);
	}
}
