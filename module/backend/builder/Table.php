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
  protected $fields = [];
  protected $data;
  protected $placeholder;

  public function __construct($interface)
  {
    // TODO
    // $this->filter = @$interface['filter'];
    // $this->filterBuilder = new FilterBuilder($this->filter);
    $this->header = @$interface['header'];
    $this->title = @$interface['title'];
    $this->actions = $interface['actions'] ?? [];
    $this->fields = $interface['fields'] ?? [];
    $this->data = @$interface['data'];
    $this->placeholder = @$interface['placeholder'];

    return $this;
  }

  public function render()
  {
    $html = '<h2 class="section__title">';
    $html .= '<span>' . $this->title . '</span>';
    $html .= $this->renderActions();
    $html .= '</h2>';

    if ($this->isFilters()) {
      $html .= '<div class="mt-2">';
      $html .= '<div class="row gap-xs">';

      $html .= '<div class="col-xs-12 col-xxl-3 order-xs-1 order-xxl-2">';
      $html .= $this->renderFilters();
      $html .= '</div>';

      $html .= '<div class="col-xs-12';
      if (Request::has('show-filters')) {
        $html .= ' col-xxl-9 order-xs-1 order-xxl-1';
      }
      $html .= '">';
      $html .= $this->renderTable();
      $html .= '</div>';

      $html .= '</div>';
      $html .= '</div>';
    } else {
      $html .= $this->renderTable();
    }

    echo $html;
  }

  protected function renderActions()
  {
    $html = '<span class="section__actions">';

    if ($this->isFilters()) {
      $filterActionTitle = Request::has('show-filters') ? t('admin.filter.hide_filters') : t('admin.filter.show_filters');
      $filterActionUrl = Request::has('show-filters') ? site('permalink') : getLinkFilter('show-filters');
      $filterActionIcon = Request::has('show-filters') ? 'minus' : 'plus';
      $html .= '<a href="' . $filterActionUrl . '" class="btn btn_info" data-tooltip="top" title="' . $filterActionTitle . '"><i class="ti ti-filter-' . $filterActionIcon . '"></i></a>';
    }

    foreach ($this->actions as $action) {
      $actionClass = $action['class'] ?? 'btn btn_primary';
      $html .= '<a href="' . $action['url'] . '" class="' . $actionClass . '">' . $action['name'] . '</a>';
    }

    $html .= '</span>';

    return $html;
  }

  protected function isFilters()
  {
    return (!empty($this->fields) && !empty($this->filter) && isset($this->filterBuilder->get) && !empty($this->filterBuilder->get()));
  }

  protected function renderFilters()
  {
    if (!$this->isFilters() || !Request::has('show-filters')) {
      return false;
    }

    $filterSelected = $this->filterBuilder->getSelected();

    $html = '<div class="box">';

    if (!empty($filterSelected)) {
      $html .= '<div class="box__header">';
      $html .= '<h4 class="box__title">' . t('admin.filter.selected') . '</h4>';
      $html .= $this->filterBuilder->renderSelected();
      $html .= '</div>';
    }

    $html .= '<div class="box__body">';
    $html .= $this->filterBuilder->render();
    $html .= '</div>';

    $html .= '</div>';

    return $html;
  }

  protected function renderTable()
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
    foreach ($this->fields as $fieldName => $field) {
      $html .= '<th';
      $html .= isset($field['width']) ? ' width="' . $field['width'] . '"' : '';
      $html .= isset($field['thClass']) ? ' class="' . $field['thClass'] . '"' : '';
      $html .= '>';

      $orderAlias = $this->getFilterOrderAlias($fieldName);
      $html .= $orderAlias ? '<a href="' . getLinkSort($orderAlias) . '">' . @$field['title'] . '</a>' : @$field['title'];

      $html .= '</th>';
    }
    $html .= '</tr>';
    $html .= '</thead>';

    $html .= '<tbody>';
    foreach ($this->data as $item) {
      $html .= '<tr>';
      foreach ($this->fields as $fieldName => $field) {
        $html .= '<td';
        $html .= isset($field['width']) ? ' width="' . $field['width'] . '"' : '';
        $html .= isset($field['tdClass']) ? ' class="' . $field['tdClass'] . '"' : '';
        $html .= '>';

        $html .= $this->renderTableItem($field, @$item->$fieldName, $item);

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

  protected function getFilterOrderAlias($key)
  {
    if (!isset($this->filterBuilder->get)) {
      return false;
    }

    foreach ($this->filterBuilder->get() as $field) {
      if ($field['type'] === 'order' && $key === @$field['column'] && isset($field['alias'])) {
        return $field['alias'];
      }
    }

    return false;
  }

  protected function renderTableItem($field, $value = null, $item = null)
  {
    if (isClosure($field['type'])) {
      return $field['type']($value, $item, $field);
    }

    $html = '';

    switch ($field['type']) {
      case 'boolean': {
          $html .= iconBoolean($value);
          break;
        }
      case 'date': {
          $html .= dateFormat($value, @$field['format']);
          break;
        }
      case 'dateWhen': {
          $html .= dateWhen($value, @$field['format']);
          break;
        }
      case 'text': {
          $html .= $value;
          break;
        }
      case 'json': {
          $html .= json_encode($value);
          break;
        }
      default: {
          $html .= '<i class="ti ti-minus"></i>';
          break;
        }
    }

    return $html;
  }

  protected function renderPagination()
  {
    $pagination = Pagination::getInstance();

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
    $html .= '<output class="pagination-output">' . t('pagination.total', $pagination->get('totalRows')) . '</output>';
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
