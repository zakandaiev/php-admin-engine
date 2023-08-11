<?php

namespace Module\Admin\Controller;

use \Engine\Filter;
use \Engine\Path;
use \Engine\Request;

class InterfaceBuilder {
	protected $filter;
	protected $title;
	protected $fields = [];
	protected $actions = [];

	public function __construct($interface) {
		$this->filter = @$interface['filter'];
		$this->title = @$interface['title'];
		$this->fields = $interface['fields'] ?? [];
		$this->actions = $interface['actions'] ?? [];

		return $this;
	}

	public function render() {
		// if(empty($this->fields)) {
		// 	return false;
		// }

		$html = '<h2 class="section__title">';
		$html .= '<span>' . $this->title . '</span>';
		$html .= $this->renderActions();
		$html .= '</h2>';

		return $html;
	}

	public function renderActions($actions = null) {
		$this->actions = $actions ?? $this->actions;

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
}
