<?php

namespace Module\Admin\Controller;

use \Engine\Form;
use \Engine\Path;

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

	public function renderCol($name, $value = null) {
		if(empty($this->form_data) || !isset($this->fields[$name])) {
			return false;
		}

		$field = $this->fields[$name];
		$field['value'] = $value ?? @$field['value'];

		return $this->getColHTML($name, $field);
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

	protected function getColHTML($name, $field) {
		if(empty($name) || !is_array($field)) {
			return false;
		}

		// INIT COL
		$html = '<div class="' . (isset($field['col_class']) ? $field['col_class'] : 'col-xs-12') . '" data-form-row="' . $name . '">';

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
		$attributes[] = 'name="' . $name . '"';
		$enabled_attributes = ['required','min','max','pattern','multiple','range','extensions','autofocus','placeholder','step'];
		$valueless_attributes = ['required','multiple','range','autofocus'];
		$min_max_to_datamin_datamax_replace_types = ['date','datetime','month','select'];
		$min_max_to_minlength_maxlength_replace_types = ['email','hidden','password','tel','text','url','textarea','wysiwyg'];

		foreach($field as $attr => $attr_value) {
			if(!in_array($attr, $enabled_attributes) && !str_starts_with($attr, 'data-')) {
				continue;
			}

			if(in_array($attr, $valueless_attributes)) {
				$attributes[$attr] = $attr;
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
				$attr_value = array_map(function($v) {
					return '.' . $v;
				}, $attr_value);
				$attributes[$attr] = 'accept="' . implode(',', $attr_value) . '"';
				continue;
			}

			$attributes[$attr] = $attr . '="' . addslashes(strval($attr_value)) . '"';
		}

		// FORMAT ATTRIBUTES & INIT HTML BY RIGHT TAG
		switch($field['type']) {
			case 'checkbox': {
				$html .= '';
				break;
			}
			case 'color': {
				$html .= '';
				break;
			}
			case 'date': {
				$html .= '';
				break;
			}
			case 'datetime': {
				$html .= '';
				break;
			}
			case 'email': {
				$html .= '<input ' . implode(' ', $attributes) . '>';
				break;
			}
			case 'file': {
				$html .= '';
				break;
			}
			case 'hidden': {
				$html .= '';
				break;
			}
			case 'month': {
				$html .= '';
				break;
			}
			case 'number': {
				$html .= '';
				break;
			}
			case 'password': {
				$html .= '';
				break;
			}
			case 'radio': {
				$html .= '';
				break;
			}
			case 'range': {
				$html .= '';
				break;
			}
			case 'tel': {
				$html .= '';
				break;
			}
			case 'text': {
				$html .= '<input ' . implode(' ', $attributes) . '>';
				break;
			}
			case 'time': {
				$html .= '';
				break;
			}
			case 'url': {
				$html .= '';
				break;
			}
			case 'textarea': {
				if(isset($attributes['value'])) unset($attributes['value']);
				$html .= '<textarea ' . implode(' ', $attributes) . ' rows="1">' . @$field['value'] . '</textarea>';
				break;
			}
			case 'wysiwyg': {
				$html .= '';
				break;
			}
			case 'select': {
				if(isset($attributes['placeholder'])) $attributes['placeholder'] = 'data-' . $attributes['placeholder'];

				$html .= '<select ' . implode(' ', $attributes) . '>';

				if(isset($attributes['placeholder'])) {
					$html .= '<option data-placeholder="true"></option>';
				}

				foreach($field['value'] as $value) {
					$html .= '<option value="' . $value->value . '">' . $value->name . '</option>';
				}

				$html .= '</select>';

				break;
			}
			case 'switch': {
				$html .= '';
				break;
			}
			case 'maska': {
				$html .= '';
				break;
			}
			default: {
				return false;
			}
		}

		// CLOSE COL
		$html .= '</div>';

		return $html;
	}
}
