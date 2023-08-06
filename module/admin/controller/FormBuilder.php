<?php

namespace Module\Admin\Controller;

use \Engine\Form;
use \Engine\Path;
use \Engine\Request;

class FormBuilder {
	protected $form_name;
	protected $form_data = [];
	protected $fields = [];

	public function __construct($forms, $module = null) {
		$_forms = [];

		if(!is_array($forms)) {
			$_forms[] = $forms;
		}
		else {
			$_forms = $forms;
		}

		foreach($_forms as $form_name) {
			$form = Path::file('form', $module) . "/$form_name.php";

			if(!is_file($form)) {
				continue;
			}

			$form_data = require $form;

			if(empty($form_data) || !isset($form_data['table']) || !isset($form_data['fields'])) {
				continue;
			}

			$this->form_name = $form_name;
			$this->form_data = $form_data;
			$this->fields = array_merge($this->fields, $form_data['fields']);
		}

		return $this;
	}

	public function setFieldValue($field_name, $value = null) {
		if(empty($this->form_data) || !isset($this->fields[$field_name])) {
			return false;
		}

		$this->fields[$field_name]['value'] = $value;

		return true;
	}

	public function render($action = 'add', $item_id = null, $form_attributes = null) {
		if(empty($this->form_data)) {
			return false;
		}

		$token = Form::$action($this->form_name, $item_id);

		if(empty($token)) {
			return false;
		}

		$form_class = isset($this->form_data['class']) ? ' class="' . $this->form_data['class'] . '"' : '';
		$row_class = isset($this->form_data['row_class']) ? ' class="' . $this->form_data['row_class'] . '"' : ' class="row gap-xs"';

		$html = "<form action=\"$token\"$form_class$form_attributes>";
		$html .= "<div$row_class>";

		foreach($this->fields as $field_name => $field) {
			$html .= $this->renderCol($field_name);
		}

		$html .= $this->renderSubmit();
		$html .= '</div>';
		$html .= '</form>';

		return $html;
	}

	public function renderCol($field_name, $value = null) {
		if(empty($this->form_data) || !isset($this->fields[$field_name])) {
			return false;
		}

		$field = $this->fields[$field_name];
		$field['value'] = $value ?? @$field['value'];

		return $this->getColHTML($field_name, $field);
	}

	public function renderSubmit() {
		if(empty($this->form_data)) {
			return false;
		}

		$submit_button = $this->form_data['submit_button'] ?? [];

		$html = '<div class="' . (isset($submit_button['col_class']) ? $submit_button['col_class'] : 'col-xs-12') . '" data-form-row="submit">';

		if(isset($submit_button['html'])) {
			return $submit_button['html'];
		}

		$html .= '<button type="submit"';
		$html .= isset($submit_button['class']) ? ' class="' . $submit_button['class'] . '">' : ' class="btn btn_primary">';
		$html .= isset($submit_button['text']) ? $submit_button['text'] : __('admin.form.submit');
		$html .= '</div>';

		return $html;
	}

	protected function getColHTML($field_name, $field) {
		if(empty($field_name) || !is_array($field)) {
			return false;
		}

		$html = '<div class="' . (isset($field['col_class']) ? $field['col_class'] : 'col-xs-12') . '" data-form-row="' . $field_name . '">';

		$html_input = $this->getColHTMLInput($field_name, $field);

		if(!$html_input) {
			return false;
		}

		$html .= $html_input;

		$html .= '</div>';

		return $html;
	}

	protected function getColHTMLInput($field_name, $field) {
		if(empty($field_name) || !is_array($field)) {
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
		$attributes[] = isset($field['multiple']) && $field['multiple'] ? 'name="' . $field_name . '[]"' : 'name="' . $field_name . '"';
		$enabled_attributes = ['required','min','max','pattern','multiple','range','extensions','autofocus','placeholder','step'];
		$valueless_attributes = ['required','multiple','range','autofocus'];
		$min_max_to_datamin_datamax_replace_types = ['date','datetime','month','select'];
		$min_max_to_minlength_maxlength_replace_types = ['email','hidden','password','tel','text','url','textarea','wysiwyg'];

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

			if(($attr === 'min' || $attr === 'max') && in_array($field['type'], $min_max_to_datamin_datamax_replace_types)) {
				$attributes[$attr] = 'data-' . $attr . '="' . addslashes(strval($attr_value)) . '"';
				continue;
			}

			if(($attr === 'min' || $attr === 'max') && in_array($field['type'], $min_max_to_minlength_maxlength_replace_types)) {
				$attributes[$attr] = $attr . 'length="' . addslashes(strval($attr_value)) . '"';
				continue;
			}

			if($attr === 'extensions') {
				$mime_map = include_once __DIR__ . '/FormBuilder/extension_to_mime.php';

				$accept = array_map(function($v) use($mime_map) {
					return $mime_map[$v] ?? '.' . $v;
				}, $attr_value);

				$accept = implode(',', array_unique($accept));

				$attributes[$attr] = 'accept="' . $accept . '"';

				continue;
			}

			$attributes[$attr] = $attr . '="' . addcslashes(strval($attr_value), '"') . '"';
		}

		$value = $field['value'] ?? $field['default'] ?? null;
		$value = is_scalar($value) ? addcslashes(strval($value), '"') : $value;

		// FORMAT ATTRIBUTES & INIT HTML BY RIGHT TAG
		switch($field['type']) {
			case 'checkbox': {
				$value = $value ? ' checked' : '';

				$html = '<label>';
				$html .= '<input type="checkbox"  ' . implode(' ', $attributes) . $value . '>';

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
			case 'color': {
				$value = $value ? ' value="' . $value . '"' : '';
				$html .= '<input type="color" ' . implode(' ', $attributes) . $value . '>';
				break;
			}
			case 'date': {
				// TODO
				$html .= '';
				break;
			}
			case 'datetime': {
				// TODO
				$html .= '';
				break;
			}
			case 'email': {
				$value = $value ? ' value="' . $value . '"' : '';
				$html .= '<input type="email" ' . implode(' ', $attributes) . $value . '>';
				break;
			}
			case 'file': {
				$value = is_array($value) ? $value : ($value ? [$value] : []);

				$value = array_map(function($v) {
					return [
						'value' => $v,
						'poster' => Request::$base . '/' . $v
					];
				}, $value);

				$value = !empty($value) ? json_encode($value, JSON_UNESCAPED_SLASHES) : '';

				$value = $value ? " data-value='$value'" : '';

				$html .= '<input type="file" ' . implode(' ', $attributes) . $value . '>';

				break;
			}
			case 'hidden': {
				$value = $value ? ' value="' . $value . '"' : '';
				$html .= '<input type="hidden" class="hidden" ' . implode(' ', $attributes) . $value . '>';
				break;
			}
			case 'month': {
				// TODO
				$html .= '';
				break;
			}
			case 'number': {
				$value = $value ? ' value="' . $value . '"' : '';
				$html .= '<input type="number" ' . implode(' ', $attributes) . $value . '>';
				break;
			}
			case 'password': {
				$value = $value ? ' value="' . $value . '"' : '';
				$html .= '<input type="password" ' . implode(' ', $attributes) . $value . '>';
				break;
			}
			case 'radio': {
				// TODO
				$html .= '';
				break;
			}
			case 'range': {
				// TODO
				$html .= '';
				break;
			}
			case 'tel': {
				// TODO
				$html .= '';
				break;
			}
			case 'text': {
				$value = $value ? ' value="' . $value . '"' : '';
				$html .= '<input type="text" ' . implode(' ', $attributes) . $value . '>';
				break;
			}
			case 'time': {
				// TODO
				$html .= '';
				break;
			}
			case 'url': {
				// TODO
				$html .= '';
				break;
			}
			case 'textarea': {
				$value = $value ? @json_encode($value) : '';
				$html .= '<textarea ' . implode(' ', $attributes) . ' rows="1">' . $value . '</textarea>';
				break;
			}
			case 'wysiwyg': {
				// TODO
				$html .= '';
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
			case 'maska': {
				$html .= '<input type="text" ' . implode(' ', $attributes) . '>';
				break;
			}
			default: {
				return false;
			}
		}

		return $html;
	}
}
