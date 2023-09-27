<?php

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

?>

<div class="row gap-xs justify-content-between align-items-center">
	<div class="col">
		<output class="pagination-output"><?= __('admin.pagination.total', $pagination->total_rows) ?></output>
	</div>
	<?php if($pagination->total_pages > 1): ?>
		<div class="col">
			<nav class="pagination m-0"><?= $prev.$first.$page2prev.$page1prev.$current.$page1next.$page2next.$last.$next ?></nav>
		</div>
	<?php endif; ?>
</div>
