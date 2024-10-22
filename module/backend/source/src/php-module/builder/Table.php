<?php

namespace module\backend\builder;

use engine\database\Pagination;
use engine\http\Request;
use engine\util\Path;

class Table
{
  protected $filter;
  protected $filterBuilder;
  protected $header;
  protected $title;
  protected $actions = [];
  protected $columns = [];
  protected $data;
  protected $placeholder;

  public function __construct($interface = [])
  {
    // TODO
    // $this->filter = @$interface['filter'];
    // $this->filterBuilder = new FilterBuilder($this->filter);
    $this->header = @$interface['header'];
    $this->title = @$interface['title'];
    $this->actions = $interface['actions'] ?? [];
    $this->columns = $interface['columns'] ?? [];
    $this->data = @$interface['data'];
    $this->placeholder = @$interface['placeholder'];

    return $this;
  }

  public function render()
  {
    $html = '<h2 class="section__title">';
    $html .= '<span>' . $this->title . '</span>';
    $html .= $this->getActionsHtml();
    $html .= '</h2>';

    if ($this->isFilters()) {
      $html .= '<div class="mt-2">';
      $html .= '<div class="row gap-xs">';

      $html .= '<div class="col-xs-12 col-xxl-3 order-xs-1 order-xxl-2">';
      $html .= $this->getFiltersHtml();
      $html .= '</div>';

      $html .= '<div class="col-xs-12';
      if (Request::has('show-filters')) {
        $html .= ' col-xxl-9 order-xs-1 order-xxl-1';
      }
      $html .= '">';
      $html .= $this->getTableHtml();
      $html .= '</div>';

      $html .= '</div>';
      $html .= '</div>';
    } else {
      $html .= $this->getTableHtml();
    }

    echo $html;
  }

  protected function getActionsHtml()
  {
    $html = '<span class="section__actions">';

    if ($this->isFilters()) {
      $filterActionTitle = Request::has('show-filters') ? t('admin.filter.hide_filters') : t('admin.filter.show_filters');
      $filterActionUrl = Request::has('show-filters') ? site('permalink') : getLinkFilter('show-filters');
      $filterActionIcon = Request::has('show-filters') ? 'minus' : 'plus';
      $html .= '<a href="' . $filterActionUrl . '" class="btn btn_info" data-tooltip="top" title="' . $filterActionTitle . '"><i class="ti ti-filter-' . $filterActionIcon . '"></i></a>';
    }

    foreach ($this->actions as $action) {
      $actionClass = $action['className'] ?? 'btn btn_primary';
      $html .= '<a href="' . $action['url'] . '" class="' . $actionClass . '">' . $action['name'] . '</a>';
    }

    $html .= '</span>';

    return $html;
  }

  protected function isFilters()
  {
    return (!empty($this->columns) && !empty($this->filter) && isset($this->filterBuilder->get) && !empty($this->filterBuilder->get()));
  }

  protected function getFiltersHtml()
  {
    if (!$this->isFilters() || !Request::has('show-filters')) {
      return false;
    }

    $filterSelected = $this->filterBuilder->getSelected();

    $html = '<div class="box">';

    if (!empty($filterSelected)) {
      $html .= '<div class="box__header">';
      $html .= '<h4 class="box__title">' . t('admin.filter.selected') . '</h4>';
      $html .= $this->filterBuilder->getSelectedHtml();
      $html .= '</div>';
    }

    $html .= '<div class="box__body">';
    $html .= $this->filterBuilder->render();
    $html .= '</div>';

    $html .= '</div>';

    return $html;
  }

  protected function getTableHtml()
  {
    $html = '<div class="box">';

    if ($this->header) {
      $html .= '<div class="box__header">';
      $html .= isClosure($this->header) ? $this->header->__invoke() : ('<h4 class="box__title">' . $this->header . '</h4>');
      $html .= '</div>';
    }

    $html .= '<div class="box__body">';

    if (empty($this->data) && $this->placeholder !== false) {
      $html .= '<h5 class="box__subtitle">' . $this->placeholder . '</h5>';
      $html .= '</div>';
      $html .= '</div>';

      return $html;
    }

    $html .= '<table class="table table_striped table_sm">';

    $html .= '<thead>';
    $html .= '<tr>';
    foreach ($this->columns as $columnName => $column) {
      $html .= '<th';
      $html .= isset($column['width']) ? ' width="' . $column['width'] . '"' : '';
      $html .= isset($column['thClass']) ? ' class="' . $column['thClass'] . '"' : '';
      $html .= '>';

      $orderAlias = $this->getFilterOrderAlias($columnName);
      $html .= $orderAlias ? '<a href="' . getLinkSort($orderAlias) . '">' . @$column['title'] . '</a>' : @$column['title'];

      $html .= '</th>';
    }
    $html .= '</tr>';
    $html .= '</thead>';

    $html .= '<tbody>';
    foreach ($this->data as $item) {
      $html .= '<tr>';
      foreach ($this->columns as $columnName => $column) {
        $html .= '<td';
        $html .= isset($column['width']) ? ' width="' . $column['width'] . '"' : '';
        $html .= isset($column['tdClassName']) ? ' class="' . $column['tdClassName'] . '"' : '';
        $html .= '>';

        $html .= $this->getTableHtmlItem($column, @$item->$columnName, $item);

        $html .= '</td>';
      }
      $html .= '</tr>';
    }
    $html .= '</tbody>';

    $html .= '</table>';

    $html .= '</div>';

    $html .= $this->getPaginationHtml();

    $html .= '</div>';

    return $html;
  }

  protected function getFilterOrderAlias($key)
  {
    if (!isset($this->filterBuilder->get)) {
      return false;
    }

    foreach ($this->filterBuilder->get() as $column) {
      if ($column['type'] === 'order' && $key === @$column['column'] && isset($column['alias'])) {
        return $column['alias'];
      }
    }

    return false;
  }

  protected function getTableHtmlItem($column, $value = null, $item = null)
  {
    if (isClosure($column['type'])) {
      return $column['type']($value, $item, $column);
    }

    $html = '';

    if ($column['type'] === 'boolean') {
      $html .= iconBoolean($value);
    } else if ($column['type'] === 'date') {
      $html .= dateFormat($value, @$column['format']);
    } else if ($column['type'] === 'dateWhen') {
      $html .= dateWhen($value, @$column['format']);
    } else if ($column['type'] === 'text') {
      $html .= $value;
    } else {
      $html .= '<i class="ti ti-minus"></i>';
    }

    return $html;
  }

  protected function getPaginationHtml()
  {
    $pagination = Pagination::getInstance();

    if (!$pagination) {
      return null;
    }

    $html = '<div class="box__footer">';

    $prev = null;
    $next = null;
    $currentBefore = null;
    $currentAfter = null;
    $currentPrevPrev = null;
    $currentAfterAfter = null;
    $first = null;
    $last = null;

    $url = Path::resolveUrl(null, $pagination->get('uri'));

    $current = '<span class="pagination__item active">' . $pagination->get('currentPage') . '</span>';

    if ($pagination->get('currentPage') > 1) {
      $num = $pagination->get('currentPage') - 1;
      $prev = '<a href="' . $url . $num . '" class="pagination__item"><i class="ti ti-chevron-left"></i></a>';
    }

    if ($pagination->get('currentPage') < $pagination->get('totalPages')) {
      $num = $pagination->get('currentPage') + 1;
      $next = '<a href="' . $url . $num . '" class="pagination__item"><i class="ti ti-chevron-right"></i></a>';
    }

    if ($pagination->get('currentPage') - 1 > 0) {
      $num = $pagination->get('currentPage') - 1;
      $currentBefore = '<a href="' . $url . $num . '" class="pagination__item">' . $num . '</a>';
    }

    if ($pagination->get('currentPage') + 1 <= $pagination->get('totalPages')) {
      $num = $pagination->get('currentPage') + 1;
      $currentAfter = '<a href="' . $url . $num . '" class="pagination__item">' . $num . '</a>';
    }

    if ($pagination->get('currentPage') - 2 > 0) {
      $num = $pagination->get('currentPage') - 2;
      $currentPrevPrev = '<a href="' . $url . $num . '" class="pagination__item">' . $num . '</a>';
    }

    if ($pagination->get('currentPage') + 2 <= $pagination->get('totalPages')) {
      $num = $pagination->get('currentPage') + 2;
      $currentAfterAfter = '<a href="' . $url . $num . '" class="pagination__item">' . $num . '</a>';
    }

    if ($pagination->get('currentPage') > 4) {
      $num = 1;
      $first = '<a href="' . $url . $num . '" class="pagination__item">' . $num . '</a><span class="pagination__item">...</span>';
    }

    if ($pagination->get('currentPage') <= $pagination->get('totalPages') - 4) {
      $num = $pagination->get('totalPages');
      $last = '<span class="pagination__item">...</span><a href="' . $url . $num . '" class="pagination__item">' . $num . '</a>';
    }

    $html .= '<div class="flex-grow-1 row gap-xs justify-content-between align-items-center">';
    $html .= '<div class="col">';
    $html .= '<output class="pagination-output">';
    $html .= t('pagination.total');
    $html .= ': <span>';
    $html .= $pagination->get('totalRows');
    $html .= '</span>';
    $html .= '</output>';
    $html .= '</div>';
    if ($pagination->get('totalPages') > 1) {
      $html .= '<div class="col">';
      $html .= '<nav class="pagination m-0">' . $prev . $first . $currentPrevPrev . $currentBefore . $current . $currentAfter . $currentAfterAfter . $last . $next . '</nav>';
      $html .= '</div>';
    }
    $html .= '</div>';

    $html .= '</div>';

    return $html;
  }
}
