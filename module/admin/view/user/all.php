<?php

$title = __('admin.user.users');

Page::set('title', $title);

Breadcrumb::add($title);

$filter_builder = new FilterBuilder('user');
$filter_selected = $filter_builder->getSelected();
?>

<?php Theme::header(); ?>

<?php Theme::block('sidebar'); ?>

<main class="page-content__inner">

	<?php Theme::block('navbar-top'); ?>

	<section class="section section_grow section_offset">
		<div class="container-fluid">

			<?php Theme::breadcrumb(); ?>

			<h2 class="section__title">
				<span><?= $title ?></span>

				<div class="section__actions">
					<?php if(Request::has('show-filters')): ?>
						<a href="<?= site('permalink') ?>" class="btn btn_secondary" data-tooltip="top" title="<?= __('admin.filter.show_filters') ?>"><i class="icon icon-filter-minus"></i></a>
					<?php else: ?>
						<a href="<?= link_filter('show-filters') ?>" class="btn btn_secondary" data-tooltip="top" title="<?= __('admin.filter.show_filters') ?>"><i class="icon icon-filter-plus"></i></a>
					<?php endif; ?>
					<a href="<?= site('url_language') ?>/admin/user/add" class="btn btn_primary"><?= __('admin.user.add_user') ?></a>
				</div>
			</h2>

			<div class="row gap-xs">
				<?php if(Request::has('show-filters')): ?>
					<div class="col-xs-12 col-xxl-3 order-xs-1 order-xxl-2">

						<div class="box">
							<?php if(!empty($filter_selected)): ?>
								<div class="box__header">
									<h4 class="box__title"><?= __('admin.filter.selected') ?></h4>
									<?= $filter_builder->renderSelected(); ?>
								</div>
							<?php endif; ?>
							<div class="box__body">
								<?= $filter_builder->render(); ?>
							</div>
						</div>

					</div>
				<?php endif; ?>
				<div class="col-xs-12<?php if(Request::has('show-filters')): ?> col-xxl-9 order-xs-1 order-xxl-1<?php endif; ?>">

					<div class="box">
						<div class="box__body">
							<?php if(!empty($users)): ?>
								<table class="table table_striped table_sm">
									<thead>
										<tr>
											<th><a href="<?= link_sort('oid') ?>">ID</a></th>
											<th><a href="<?= link_sort('oname') ?>"><?= __('admin.user.name') ?></a></th>
											<th><a href="<?= link_sort('ogroups') ?>"><?= __('admin.user.groups_count') ?></a></th>
											<th><a href="<?= link_sort('ocreated') ?>"><?= __('admin.user.date_created') ?></a></th>
											<th><a href="<?= link_sort('oauth') ?>"><?= __('admin.user.auth_date') ?></a></th>
											<th><a href="<?= link_sort('oenabled') ?>"><?= __('admin.user.is_enabled') ?></a></th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										<?php foreach($users as $user): ?>
											<tr>
												<td><?= $user->id ?></td>
												<td><?= $user->fullname ?></td>
												<td><a href="/admin/group"><?= $user->count_groups ?></a></td>
												<td><?= date_when($user->date_created, 'd.m.Y H:i') ?></td>
												<td>
													<?php if($user->auth_date): ?>
														<a href="<?= sprintf(SERVICE['ip_checker'], $user->auth_ip) ?>" target="_blank"><?= date_when($user->auth_date, 'd.m.Y H:i') ?></a>
													<?php else: ?>
														<i class="icon icon-minus"></i>
													<?php endif; ?>
												</td>
												<td>
													<?php
														$enabled_tooltip = $user->is_enabled ? __('admin.user.deactivate_this_user') : __('admin.user.activate_this_user');
													?>
													<button type="button" data-action="<?= Form::edit('user/toggle', $user->id) ?>" data-fields='[{"key":"is_enabled","value":<?= $user->is_enabled ?>}]' data-redirect="this" data-tooltip="top" data-title="<?= $enabled_tooltip ?>" class="table__action">
														<?= icon_boolean($user->is_enabled) ?>
													</button>
												</td>
												<td class="table__actions">
													<a href="<?= site('url_language') ?>/admin/profile/<?= $user->id ?>" data-tooltip="top" data-title="<?= __('admin.view') ?>" class="table__action">
														<i class="icon icon-eye"></i>
													</a>
													<a href="<?= site('url_language') ?>/admin/user/edit/<?= $user->id ?>" data-tooltip="top" data-title="<?= __('admin.edit') ?>" class="table__action">
														<i class="icon icon-edit"></i>
													</a>
													<button type="button" data-action="<?= Form::delete('user/edit', $user->id) ?>" data-confirm="<?= __('admin.user.delete_confirm', $user->fullname) ?>" data-tooltip="top" data-title="<?= __('admin.delete') ?>" class="table__action">
														<i class="icon icon-trash"></i>
													</button>
												</td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
							<?php else: ?>
								<h5 class="box__subtitle"><?= __('admin.user.there_are_no_users_yet') ?></h5>
							<?php endif; ?>

							<?php Theme::pagination(); ?>
						</div>
					</div>

				</div>
			</div>

		</div>
	</section>

	<?php Theme::block('navbar-bottom'); ?>

</main>

<?php Theme::footer(); ?>
