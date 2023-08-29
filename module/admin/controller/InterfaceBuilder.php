<?php

namespace Module\Admin\Controller;

use \Engine\Request;

class InterfaceBuilder {
	protected $filter;
	protected $filter_builder;
	protected $title;
	protected $actions = [];
	protected $fields = [];
	protected $data;
	protected $placeholder;

	public function __construct($interface) {
		$this->filter = @$interface['filter'];
		$this->filter_builder = new FilterBuilder($this->filter);
		$this->title = @$interface['title'];
		$this->actions = $interface['actions'] ?? [];
		$this->fields = $interface['fields'] ?? [];
		$this->data = @$interface['data'];
		$this->placeholder = @$interface['placeholder'];

		return $this;
	}

	public function render() {
		$html = '<h2 class="section__title">';
		$html .= '<span>' . $this->title . '</span>';
		$html .= $this->renderActions();
		$html .= '</h2>';

		if(empty($this->fields)) {
			return $html;
		}

		$html .= '<div class="row gap-xs">';

		$html .= '<div class="col-xs-12 col-xxl-3 order-xs-1 order-xxl-2">';
		$html .= $this->renderFilters();
		$html .= '</div>';

		$html .= '<div class="col-xs-12';
		if(Request::has('show-filters')) {
			$html .= ' col-xxl-9 order-xs-1 order-xxl-1';
		}
		$html .= '">';
		$html .= $this->renderTable();
		$html .= '</div>';

		$html .= '</div>';

		return $html;
	}

	protected function renderActions() {
		$html = '<div class="section__actions">';

		if(!empty($this->filter)) {
			$filter_action_title = Request::has('show-filters') ? __('admin.filter.hide_filters') : __('admin.filter.show_filters');
			$filter_action_url = Request::has('show-filters') ? site('permalink') : link_filter('show-filters');
			$filter_action_icon = Request::has('show-filters') ? 'minus' : 'plus';
			$html .= '<a href="' . $filter_action_url . '" class="btn btn_secondary" data-tooltip="top" title="' . $filter_action_title . '"><i class="icon icon-filter-' . $filter_action_icon . '"></i></a>';
		}

		foreach($this->actions as $action) {
			$action_class = $action['class'] ?? 'btn btn_primary';
			$html .= '<a href="' . site('url_language') . $action['url'] . '" class="' . $action_class . '">' . $action['name'] . '</a>';
		}

		$html .= '</div>';

		return $html;
	}

	protected function renderFilters() {
		if(empty($this->filter) || empty($this->filter_builder->get()) || !Request::has('show-filters')) {
			return false;
		}

		$filter_selected = $this->filter_builder->getSelected();

		$html = '<div class="box">';

		if(!empty($filter_selected)) {
			$html .= '<div class="box__header">';
			$html .= '<h4 class="box__title">' . __('admin.filter.selected') . '</h4>';
			$html .= $this->filter_builder->renderSelected();
			$html .= '</div>';
		}

		$html .= '<div class="box__body">';
		$html .= $this->filter_builder->render();
		$html .= '</div>';

		$html .= '</div>';

		return $html;
	}

	protected function renderTable() {
		$html = '<div class="box">';
		$html .= '<div class="box__body">';

		if(empty($this->data)) {
			$html .= '<h5 class="box__subtitle">' . $this->placeholder . '</h5>';
			$html .= '</div>';
			$html .= '</div>';
			return $html;
		}

		$html .= '<table class="table table_striped table_sm">';

		$html .= '<thead>';
		$html .= '<tr>';
		foreach($this->fields as $field_name => $field) {
			$html .= '<th';
			$html .= isset($field['width']) ? ' width="' . $field['width'] . '"' : '';
			$html .= isset($field['th_class']) ? ' class="' . $field['th_class'] . '"' : '';
			$html .= '>';

			$order_alias = $this->getFilterOrderAlias($field_name);
			$html .= $order_alias ? '<a href="' . link_sort($order_alias) . '">' . @$field['title'] . '</a>' : @$field['title'];

			$html .= '</th>';
		}
		$html .= '</tr>';
		$html .= '</thead>';

		$html .= '<tbody>';
		foreach($this->data as $item) {
			$html .= '<tr>';
			foreach($this->fields as $field_name => $field) {
				$html .= '<td';
				$html .= isset($field['width']) ? ' width="' . $field['width'] . '"' : '';
				$html .= isset($field['td_class']) ? ' class="' . $field['td_class'] . '"' : '';
				$html .= '>';

				$html .= $this->renderTableItem($field, @$item->$field_name, $item);

				$html .= '</td>';
			}
			$html .= '</tr>';
		}
		$html .= '</tbody>';

		$html .= '</table>';

		$html .= '</div>';
		$html .= '</div>';

		return $html;
	}

	protected function getFilterOrderAlias($key) {
		foreach($this->filter_builder->get() as $field) {
			if($field['type'] === 'order' && $key === @$field['column'] && isset($field['alias'])) {
				return $field['alias'];
			}
		}

		return false;
	}

	protected function renderTableItem($field, $value = null, $item = null) {
		if(is_closure($field['type'])) {
			return $field['type']($value, $item, $field);
		}

		$html = '';

		switch($field['type']) {
			case 'boolean': {
				$html .= icon_boolean($value);
				break;
			}
			case 'date': {
				$html .= format_date($value, $field['format']);
				break;
			}
			case 'date_when': {
				$html .= date_when($value, $field['format']);
				break;
			}
			case 'text': {
				$html .= $value;
				break;
			}
			default: {
				$html .= '<i class="icon icon-minus"></i>';
				break;
			}
		}

		return $html;
	}
}
