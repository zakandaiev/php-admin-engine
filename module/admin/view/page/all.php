<?php

$title = __('admin.page.pages');

Page::set('title', $title);

$breadcrumbs = Request::has('breadcrumbs') ? Request::get('breadcrumbs') : [];

if(!empty($breadcrumbs)) {
	Page::breadcrumb('add', ['name' => $title, 'url' => '/admin/page']);

	$cb = [];
	
	foreach($breadcrumbs as $key => $crumb) {
		$cb[] = $crumb;
		
		$crumb['url'] = $crumb['url'] . '?' . http_build_query(['breadcrumbs' => $cb]);

		if(isset($breadcrumbs[$key + 1])) {
			Page::breadcrumb('add', ['name' => $crumb['name'], 'url' => $crumb['url']]);
		}
		else {
			Page::breadcrumb('add', ['name' => $crumb['name']]);
		}
	}
}
else {
	Page::breadcrumb('add', ['name' => $title]);
}

$interface_builder = new InterfaceBuilder([
	'filter' => 'page',
	'title' => $title,
	'actions' => [
		['name' => __('admin.page.add_category'), 'url' => '/admin/page/add?is-category', 'class' => 'btn btn_secondary'],
		['name' => __('admin.page.add_page'), 'url' => '/admin/page/add']
	],
	'fields' => [
		'title' => [
			'type' => function($value, $item) use($breadcrumbs) {
				$icon = '<i class="icon icon-file-text"></i>';
				$url = site('url_language') . '/admin/page/edit/' . $item->id;

				if($item->is_category) {
					$uri = '/admin/page/category/' . $item->id;

					$breadcrumbs[] = [
						'name' => $value,
						'url' => $uri
					];
					
					$icon = '<i class="icon icon-folder"></i>';
					$url = site('url_language') . $uri . '?' . http_build_query(['breadcrumbs' => $breadcrumbs]);
				}

				return $icon . ' <a href="' . $url . '">' . $value . '</a>';
			},
			'title' => __('admin.page.title')
		],
		'translations' => [
			'type' => function($value, $item) {
				$html = '';
				$count_translations = count(array_intersect($value, array_keys(site('languages')))) + 1;
				$count_aviable_languages = count(site('languages'));

				foreach($value as $language) {
					$html .= '<a href="' . site('url_language') . '/admin/page/edit/' . $item->id . '/translation/edit/' . $language . '" title="' . lang($language, 'name') . '"><img width="18" height="18" class="d-inline-block mw-100 radius-circle" src="<?= Asset::url() ?>/' . lang($language, 'icon') . '" alt="' . $language . '"></a>';
				}

				if($count_translations < $count_aviable_languages) {
					$html .= '<div class="dropdown d-inline-block dropdown_right-top">';
					$html .= '<button type="button" class="table__action"><i class="icon icon-plus"></i></button>';
					$html .= '<div class="dropdown__menu">';

					foreach(site('languages') as $language) {
						if($language['key'] === $item->language || in_array($language['key'], $value)) {
							continue;
						}

						$html .= '<a href="' . site('url_language') . '/admin/page/edit/' . $item->id . '/translation/add/' . $language['key']  . '" class="dropdown__item d-flex align-items-center gap-2">';
						$html .= '<img src="' . Asset::url() . '/' . lang('icon', $language['key']) . '" alt="' . lang('locale', $language['key']) . '" class="flex-shrink-0 d-inline-block h-1em">';
						$html .= '<span>' . __("locale.{$language['key']}") . '</span>';
						$html .= '</a>';
					}

					$html .= '</div>';
					$html .= '</div>';					
				}

				return $html;
			},
			'title' => __('admin.page.translations')
		],
		'author' => [
			'type' => function($value, $item) {return $value->fullname;},
			'title' => __('admin.page.author')
		],
		'date_created' => [
			'type' => 'date_when',
			'format' => 'd.m.Y H:i',
			'title' => __('admin.page.date_created')
		],
		'is_enabled' => [
			'type' => function($value, $item) {
				if($item->is_pending) {
					return '<span data-tooltip="top" title="' . __('admin.page.is_pending', format_date($item->date_publish)) . '"><i class="icon icon-clock"></i></span>';
				}

				$tooltip = $item->is_enabled ? __('admin.page.deactivate_this_page') : __('admin.page.activate_this_page');

				$html = '<button type="button" data-action="' . Form::edit('page/toggle', $item->id) . '" data-fields="is_enabled:' . !$value . '" data-redirect="this" data-tooltip="top" title="' . $tooltip . '" class="table__action">';
				$html .= icon_boolean($value);
				$html .= '</button>';

				return $html;
			},
			'title' => __('admin.page.is_enabled')
		],
		'table_actions' => [
			'td_class' => 'table__actions',
			'type' => function($value, $item) {
				$html = '<a href="' . site('url_language') . '/' . ($item->url === 'home' ? '' : $item->url) . '" target="_blank" data-tooltip="top" title="' . __('admin.view') . '" class="table__action"><i class="icon icon-eye"></i></a>';

				$html .= ' <a href="' . site('url_language') . '/admin/page/edit/' . $item->id .'" data-tooltip="top" title="' . __('admin.edit') . '" class="table__action"><i class="icon icon-edit"></i></a>';

				$html .= ' <button type="button" data-action="' . Form::delete('page/page', $item->id) . '" data-confirm="' . __('admin.page.delete_confirm', $item->author->fullname) . '" data-remove="trow" data-decrement=".pagination-output" data-tooltip="top" title="' . __('admin.delete') . '" class="table__action">';
				$html .= '<i class="icon icon-trash"></i>';
				$html .= '</button>';

				return $html;
			},
		]
	],
	'data' => $pages,
	'placeholder' => false
]);
?>

<?php Theme::header(); ?>

<?php Theme::block('sidebar'); ?>

<main class="page-content__inner">

	<?php Theme::block('navbar-top'); ?>

	<section class="section section_grow section_offset">
		<div class="container-fluid">

			<?php Theme::breadcrumb(); ?>

			<?= $interface_builder->render() ?>

			<?php if(!empty($breadcrumbs)): ?>
				<?php
					$cb = $breadcrumbs;
					array_pop($cb);
					$cb = array_reverse($cb);
					$uri_back = !empty($cb) ? $cb[0]['url'] : '/admin/page';
				?>
				<script>
					const tbody = document.querySelector('tbody');

					if(tbody) {
						const trow = document.createElement('tr');
						const tcol = document.createElement('td');

						tcol.setAttribute('colspan', 6);
						tcol.setAttribute('class', 'cursor-pointer');
						tcol.innerHTML = '<i class="icon icon-arrow-back-up"></i> ...';
						tcol.onclick = () => {
							window.location.href = `<?= site('url_language') ?><?= html($uri_back) ?>?<?= http_build_query(['breadcrumbs' => array_reverse($cb)]) ?>`;
						};

						trow.appendChild(tcol);
						tbody.prepend(trow);
					}
				</script>
			<?php endif; ?>

		</div>
	</section>

	<?php Theme::block('navbar-bottom'); ?>

</main>

<?php Theme::footer(); ?>
