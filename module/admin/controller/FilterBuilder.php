<?php

namespace Module\Admin\Controller;

use Engine\Filter;
use Engine\Path;
use Engine\Request;

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

		$filter_options = Filter::getInstance()->get('options') ?? [];

		foreach($fields as $field_alias => $field) {
			if(!is_array($field)) continue;

			$field['alias'] = $field_alias;
			$field['options'] = $filter_options[$field_alias] ?? [];
			$field['value'] = Request::get($field_alias) ?? @$field['default'];

			$this->fields[$field_alias] = $field;
		}

		return $this;
	}

	public function get($key = null) {
		return isset($key) ? @$this->fields[$key] : $this->fields;
	}

	public function has($key) {
		return isset($this->fields[$key]);
	}

	public function setOptions($field_alias, $value = null) {
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

		foreach(Request::get() as $hidden_alias => $hidden_value) {
			if($this->has($hidden_alias)) {
				continue;
			}

			if(is_array($hidden_value)) {
				foreach($hidden_value as $v) {
					$html .= '<input type="hidden" class="hidden" name="' . $hidden_alias . '[]" value="' . $v . '">';
				}
			}
			else {
				$html .= '<input type="hidden" class="hidden" name="' . $hidden_alias . '" value="' . $hidden_value . '">';
			}
		}

		$html .= '</form>';

		return $html;
	}

	public function getSelected() {
		return array_filter($this->fields, function($field) {
			if(!Request::has($field['alias'])) {
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
		$field['value'] = Request::get($field_alias) ?? @$field['default'];

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
		$attributes['name'] = (isset($field['multiple']) && $field['multiple']) || $field['type'] === 'checkbox' ? 'name="' . $field_alias . '[]"' : 'name="' . $field_alias . '"';
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
				foreach($field['options'] as $key => $option) {
					$checked = '';
					if(
						(isset($field['multiple']) && $field['multiple'] && is_array($field['value']) && in_array($option->id, $field['value']))
						|| ($field['type'] === 'checkbox' && is_array($field['value']) && in_array($option->id, $field['value']))
						|| ($option->id == $field['value'] && is_scalar($field['value']))
					) {
						$checked = ' checked';
					}

					$class = $key === 0 ? 'd-block' : 'd-block mt-1';

					$html .= '<label class="' . $class . '">';
					$html .= '<input type="' . $field['type'] . '" value="' . $option->id . '" ' . implode(' ', $attributes) . $checked . '>';
					$html .= '<span>' . $option->text . '</span>';
					$html .= '</label>';
				}

				break;
			}
			case 'date': {
				if(isset($attributes['name'])) $attributes['name'] = 'name="' . $field_alias . '"';
				if(isset($attributes['range'])) $attributes['range'] = 'data-' . $attributes['range'];
				if(isset($attributes['multiple'])) $attributes['multiple'] = 'data-' . $attributes['multiple'];

				$value = isset($value) ? ' value="' . $value . '"' : '';

				$html .= '<input type="text" data-picker="date" ' . implode(' ', $attributes) . $value . '>';

				break;
			}
			case 'number': {
				$value = isset($value) ? ' value="' . $value . '"' : '';
				$html .= '<input type="number" ' . implode(' ', $attributes) . $value . '>';
				break;
			}
			case 'range': {
				$value = isset($value) ? ' value="' . $value . '"' : '';
				$html .= '<input type="range" ' . implode(' ', $attributes) . $value . '>';
				break;
			}
			case 'text': {
				$value = isset($value) ? ' value="' . $value . '"' : '';
				$html .= '<input type="text" ' . implode(' ', $attributes) . $value . '>';
				break;
			}
			case 'select': {
				if(isset($attributes['placeholder'])) $attributes['placeholder'] = 'data-' . $attributes['placeholder'];

				$html .= '<select ' . implode(' ', $attributes) . '>';

				if(isset($attributes['placeholder'])) {
					$html .= '<option data-placeholder="true"></option>';
				}

				foreach($field['options'] as $option) {
					$selected = $option->id == $field['value'] && is_scalar($field['value']) ? ' selected' : '';
					$html .= '<option value="' . $option->id . '"' . $selected . '>' . $option->text . '</option>';
				}

				$html .= '</select>';

				break;
			}
			case 'switch': {
				$value = isset($value) ? ' checked' : '';

				$html = '<label class="switch">';
				$html .= '<input type="checkbox" value="1" ' . implode(' ', $attributes) . $value . '>';
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

	public function renderSelected($row_class = null, $row_attributes = null) {
		$filter_selected = $this->getSelected();

		if(empty($filter_selected)) {
			return false;
		}

		$row_class = isset($row_class) ? ' class="' . $row_class . '"' : ' class="row gap-1"';
		$row_attributes = !empty($row_attributes) ? " $row_attributes" : '';

		$html = "<div$row_class$row_attributes>";

		foreach($filter_selected as $selected) {
			$html .= $this->renderColSelected($selected);
		}

		$html .= '</div>';

		$html .= '<a href="' . site('permalink') . '" class="d-inline-block mt-3">' . __('admin.filter.reset') . '</a>';

		return $html;
	}

	public function renderColSelected($selected) {
		if(empty($selected)) {
			return false;
		}

		$html = '';

		if(is_array($selected['value'])) {
			foreach($selected['value'] as $value) {
				$selected['value'] = $value;

				$html .= $this->renderColSelected($selected);
			}

			return $html;
		}

		$text = $selected['value'];

		if(isset($selected['classifier'])) {
			$text = is_closure($selected['classifier']) ? $selected['classifier']($selected['value']) : __($selected['classifier'] . '.' . $selected['value']);
		}

		if(isset($selected['selected_label']) && $selected['selected_label']) {
			$text = ($selected['label'] ?? $selected['selected_label']) . ': ' . $text;
		}

		$html .= '<div class="col">';
		$html .= '<div class="label label_close label_info">';

		$html .= '<span>' . html($text) . '</span>';
		$html .= '<a href="' . link_unfilter($selected['alias']) . '" class="label__close"><i class="icon icon-x"></i></a>';

		$html .= '</div>';
		$html .= '</div>';

		return $html;
	}
}
