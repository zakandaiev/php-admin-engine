<?php

namespace Module\Admin\Controller;

use Engine\Request;
use Engine\Pagination;

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

		if($this->isFilters()) {
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
		}
		else {
			$html .= $this->renderTable();
		}

		return $html;
	}

	protected function renderActions() {
		$html = '<span class="section__actions">';

		if($this->isFilters()) {
			$filter_action_title = Request::has('show-filters') ? __('admin.filter.hide_filters') : __('admin.filter.show_filters');
			$filter_action_url = Request::has('show-filters') ? site('permalink') : link_filter('show-filters');
			$filter_action_icon = Request::has('show-filters') ? 'minus' : 'plus';
			$html .= '<a href="' . $filter_action_url . '" class="btn btn_secondary" data-tooltip="top" title="' . $filter_action_title . '"><i class="icon icon-filter-' . $filter_action_icon . '"></i></a>';
		}

		foreach($this->actions as $action) {
			$action_class = $action['class'] ?? 'btn btn_primary';
			$html .= '<a href="' . site('url_language') . $action['url'] . '" class="' . $action_class . '">' . $action['name'] . '</a>';
		}

		$html .= '</span>';

		return $html;
	}

	protected function isFilters() {
		return (!empty($this->filter) && !empty($this->filter_builder->get()));
	}

	protected function renderFilters() {
		if(!$this->isFilters() || !Request::has('show-filters')) {
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

		$html .= $this->renderPagination();

		$html .= '</div>';

		return $html;
	}

	protected function renderPagination() {
		$pagination = Pagination::getInstance();

		$html = '<div class="box__footer">';

		$prev = null;
		$next = null;
		$page1prev = null;
		$page1next = null;
		$page2prev = null;
		$page2next = null;
		$first = null;
		$last = null;

		$url = site('url') . $pagination->uri;

		$current = '<span class="pagination__item active">' . $pagination->current_page . '</span>';

		if($pagination->current_page > 1) {
			$num = $pagination->current_page - 1;
			$prev = '<a href="' . $url . $num . '" class="pagination__item"><i class="icon icon-chevron-left"></i></a>';
		}

		if($pagination->current_page < $pagination->total_pages) {
			$num = $pagination->current_page + 1;
			$next = '<a href="' . $url . $num . '" class="pagination__item"><i class="icon icon-chevron-right"></i></a>';
		}

		if($pagination->current_page - 1 > 0) {
			$num = $pagination->current_page - 1;
			$page1prev = '<a href="' . $url . $num . '" class="pagination__item">' . $num . '</a>';
		}

		if($pagination->current_page + 1 <= $pagination->total_pages) {
			$num = $pagination->current_page + 1;
			$page1next = '<a href="' . $url . $num . '" class="pagination__item">' . $num . '</a>';
		}

		if($pagination->current_page - 2 > 0) {
			$num = $pagination->current_page - 2;
			$page2prev = '<a href="' . $url . $num . '" class="pagination__item">' . $num . '</a>';
		}

		if($pagination->current_page + 2 <= $pagination->total_pages) {
			$num = $pagination->current_page + 2;
			$page2next = '<a href="' . $url . $num . '" class="pagination__item">' . $num . '</a>';
		}

		if($pagination->current_page > 4) {
			$num = 1;
			$first = '<a href="' . $url . $num . '" class="pagination__item">' . $num . '</a><span class="pagination__item">...</span>';
		}

		if($pagination->current_page <= $pagination->total_pages - 4) {
			$num = $pagination->total_pages;
			$last = '<span class="pagination__item">...</span><a href="' . $url . $num . '" class="pagination__item">' . $num . '</a>';
		}

		$html .= '<div class="flex-grow-1 row gap-xs justify-content-between align-items-center">';
		$html .= '<div class="col">';
		$html .= '<output class="pagination-output">' . __('admin.pagination.total', $pagination->total_rows) . '</output>';
		$html .= '</div>';
		if($pagination->total_pages > 1) {
			$html .= '<div class="col">';
			$html .= '<nav class="pagination m-0">' . $prev.$first.$page2prev.$page1prev.$current.$page1next.$page2next.$last.$next . '</nav>';
			$html .= '</div>';
		}
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
