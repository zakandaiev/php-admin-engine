<?php

namespace Module\Admin\Controller;

use \Engine\Path;
use \Engine\Request;

class FilterBuilder {
	protected $filter_name;
	protected $fields = [];

	public function __construct($filter_name, $module = null) {
		$path = Path::file('filter', $module) . "/$filter_name.php";

		if(!is_file($path)) {
			return false;
		}

		$fields = require $path;

		if(empty($fields)) {
			return false;
		}

		$this->filter_name = $filter_name;

		foreach($fields as $field_alias => $field) {
			if(!is_array($field)) continue;

			$field['alias'] = $field_alias;
			$field['options'] = null;
			$field['value'] = Request::$get[$field_alias] ?? @$field['default'];

			$this->fields[$field_alias] = $field;
		}

		return $this;
	}

	public function setFieldOptions($field_alias, $value = null) {
		if(!isset($this->fields[$field_alias])) {
			return false;
		}

		$this->fields[$field_alias]['options'] = $value;

		return true;
	}

	public function render($row_class = null, $row_attributes = null) {
		if(empty($this->fields)) {
			return false;
		}

		$row_class = isset($row_class) ? ' class="' . $row_class . '"' : ' class="row gap-xs"';
		$row_attributes = !empty($row_attributes) ? " $row_attributes" : '';

		$html = '<form action="' . site('permalink') . '" method="get" data-submit-onchange data-submit-native data-unset-null>';
		$html .= "<div$row_class$row_attributes>";

		foreach($this->fields as $field_alias => $field) {
			$html .= $this->renderCol($field_alias);
		}

		$html .= '</div>';
		$html .= '</form>';

		return $html;
	}

	public function getSelected() {
		return array_filter($this->fields, function($field) {
			if(!isset(Request::$get[$field['alias']])) {
				return false;
			}

			return $field;
		});
	}

	public function renderCol($field_alias, $value = null) {
		if(!isset($this->fields[$field_alias])) {
			return false;
		}

		$field = $this->fields[$field_alias];
		$field['value'] = Request::$get[$field_alias] ?? @$field['default'];

		return $this->getColHTML($field_alias, $field);
	}

	protected function getColHTML($field_alias, $field) {
		if(empty($field_alias) || !is_array($field)) {
			return false;
		}

		$html = '<div class="' . (isset($field['col_class']) ? $field['col_class'] : 'col-xs-12') . '" data-form-row="' . $field_alias . '">';

		$html_input = $this->getColHTMLInput($field_alias, $field);

		if(!$html_input) {
			return false;
		}

		$html .= $html_input;

		$html .= '</div>';

		return $html;
	}

	protected function getColHTMLInput($field_alias, $field) {
		if(empty($field_alias) || !is_array($field)) {
			return false;
		}

		$html = '';

		// SET LABEL
		if(isset($field['label_html'])) {
			$html .= $field['label_html'];
		}
		else if(isset($field['label'])) {
			$html .= '<label';
			if(isset($field['label_class'])) {
				$html .= ' class="' . $field['label_class'] . '">';
			}
			else {
				$html .= '>';
			}
			$html .= $field['label'];
			$html .= '</label>';
		}

		// SET ATTRIBUTES
		$attributes = [];
		$attributes[] = isset($field['multiple']) && $field['multiple'] ? 'name="' . $field_alias . '[]"' : 'name="' . $field_alias . '"';
		$enabled_attributes = ['pattern','multiple','range','placeholder','step','min','max','minlength','maxlength'];
		$valueless_attributes = ['multiple','range'];

		foreach($field as $attr => $attr_value) {
			if(!in_array($attr, $enabled_attributes) && !str_starts_with($attr, 'data-')) {
				continue;
			}

			if(in_array($attr, $valueless_attributes)) {
				if($attr_value) {
					$attributes[$attr] = $attr;
				}
				continue;
			}

			$attributes[$attr] = $attr . '="' . addcslashes(strval($attr_value), '"') . '"';
		}

		$value = $field['value'] ?? $field['default'] ?? null;
		$value = is_scalar($value) ? addcslashes(strval($value), '"') : $value;

		// FORMAT ATTRIBUTES & INIT HTML BY RIGHT TAG
		switch($field['type']) {
			case 'checkbox':
			case 'radio': {
				$value = $value ? ' checked' : '';

				$html .= '<label class="d-block">';
				$html .= '<input type="radio"  ' . implode(' ', $attributes) . $value . '>';

				if(isset($field['label_html'])) {
					$html .= $field['label_html'];
				}
				else if(isset($field['label'])) {
					$html .= '<span';
					if(isset($field['label_class'])) {
						$html .= ' class="' . $field['label_class'] . '">';
					}
					else {
						$html .= '>';
					}
					$html .= $field['label'];
					$html .= '</span>';
				}

				$html .= '</label>';
				break;
			}
			case 'date': {
				$value = $value ? ' value="' . $value . '"' : '';
				$html .= '<input type="text" data-picker="date" ' . implode(' ', $attributes) . $value . '>';
				break;
			}
			case 'number': {
				$value = $value ? ' value="' . $value . '"' : '';
				$html .= '<input type="number" ' . implode(' ', $attributes) . $value . '>';
				break;
			}
			case 'range': {
				$value = $value ? ' value="' . $value . '"' : '';
				$html .= '<input type="range" ' . implode(' ', $attributes) . $value . '>';
				break;
			}
			case 'maska':
			case 'text': {
				$value = $value ? ' value="' . $value . '"' : '';
				$html .= '<input type="text" ' . implode(' ', $attributes) . $value . '>';
				break;
			}
			case 'select': {
				if(isset($attributes['placeholder'])) $attributes['placeholder'] = 'data-' . $attributes['placeholder'];

				$html .= '<select ' . implode(' ', $attributes) . '>';

				if(isset($attributes['placeholder'])) {
					$html .= '<option data-placeholder="true"></option>';
				}

				$field['value'] = $field['value'] ?? [];
				foreach($field['value'] as $value) {
					$selected = $value->selected ? ' selected' : '';
					$html .= '<option value="' . $value->value . '"' . $selected . '>' . $value->name . '</option>';
				}

				$html .= '</select>';

				break;
			}
			case 'switch': {
				$value = $value ? ' checked' : '';

				$html = '<label class="switch">';
				$html .= '<input type="checkbox"  ' . implode(' ', $attributes) . $value . '>';
				$html .= '<span class="switch__slider"></span>';

				if(isset($field['label_html'])) {
					$html .= $field['label_html'];
				}
				else if(isset($field['label'])) {
					$html .= '<span';
					if(isset($field['label_class'])) {
						$html .= ' class="' . $field['label_class'] . '">';
					}
					else {
						$html .= '>';
					}
					$html .= $field['label'];
					$html .= '</span>';
				}

				$html .= '</label>';
				break;
			}
			default: {
				return false;
			}
		}

		return $html;
	}
}
