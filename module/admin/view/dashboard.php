<?php
	Page::set('title', __('admin.dashboard.title'));
?>

<?php Theme::header(); ?>

<?php Theme::block('sidebar'); ?>

<main class="page-content__inner">

	<?php Theme::block('navbar-top'); ?>

	<section class="section section_grow section_offset">
		<div class="container-fluid">

			<h2 class="section__title"><?=  __('admin.dashboard.title') ?></h2>

			<div class="row fill cols-xs-1 cols-md-2 gap-xs">

				<div class="col">
					<div class="row gap-xs cols-xs-1 cols-sm-2">

						<div class="col">
							<div class="box h-100">
								<div class="box__header box__header_icon">
									<h4 class="box__title">
										<span>Users</span>
										<span class="box__icon box__icon_primary">
											<i class="icon icon-users"></i>
										</span>
									</h4>
								</div>
								<div class="box__body">
									<h3 class="m-t--2">44</h3>
									<span class="label label_light label_warning">-2.25%</span>
									<span class="color-muted">since last month</span>
								</div>
							</div>
						</div>

						<div class="col">
							<div class="box h-100">
								<div class="box__header box__header_icon">
									<h4 class="box__title">
										<span>Messages</span>
										<span class="box__icon box__icon_primary">
											<i class="icon icon-message-circle"></i>
										</span>
									</h4>
								</div>
								<div class="box__body">
									<h3 class="m-t--2">44</h3>
									<span class="label label_light label_success">+7.75%</span>
									<span class="color-muted">since last month</span>
								</div>
							</div>
						</div>

						<div class="col">
							<div class="box h-100">
								<div class="box__header box__header_icon">
									<h4 class="box__title">
										<span>Pages</span>
										<span class="box__icon box__icon_primary">
											<i class="icon icon-file-text"></i>
										</span>
									</h4>
								</div>
								<div class="box__body">
									<h3 class="m-t--2">69</h3>
									<span class="label label_light label_error">-5.25%</span>
									<span class="color-muted">since last month</span>
								</div>
							</div>
						</div>

						<div class="col">
							<div class="box h-100">
								<div class="box__header box__header_icon">
									<h4 class="box__title">
										<span>Comments</span>
										<span class="box__icon box__icon_primary">
											<i class="icon icon-message"></i>
										</span>
									</h4>
								</div>
								<div class="box__body">
									<h3 class="m-t--2">10 525</h3>
									<span class="label label_light label_error">-22.25%</span>
									<span class="color-muted">since last month</span>
								</div>
							</div>
						</div>

					</div>
				</div>

				<div class="col">
					<div class="box h-100">
						<div class="box__header">
							<h4 class="box__title">Last logins</h4>
						</div>
						<div class="box__body">
						<table class="table table_sm">
								<thead>
									<tr>
										<th>Name</th>
										<th>Date</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>Vanessa Tucker (admin@adm.in)</td>
										<td>12.07.2023 at 11:47</td>
										<td class="table__action">
											<a href="#" data-tooltip="top" title="View"><i class="icon icon-eye"></i></a>
											<a href="#" data-tooltip="top" title="Edit"><i class="icon icon-edit"></i></a>
											<a href="#" data-tooltip="top" title="Delete"><i class="icon icon-trash"></i></a>
										</td>
									</tr>
									<tr>
										<td>Vanessa Tucker (admin@adm.in)</td>
										<td>12.07.2023 at 11:47</td>
										<td class="table__action">
											<a href="#" data-tooltip="top" title="View"><i class="icon icon-eye"></i></a>
											<a href="#" data-tooltip="top" title="Edit"><i class="icon icon-edit"></i></a>
											<a href="#" data-tooltip="top" title="Delete"><i class="icon icon-trash"></i></a>
										</td>
									</tr>
									<tr>
										<td>Vanessa Tucker (admin@adm.in)</td>
										<td>12.07.2023 at 11:47</td>
										<td class="table__action">
											<a href="#" data-tooltip="top" title="View"><i class="icon icon-eye"></i></a>
											<a href="#" data-tooltip="top" title="Edit"><i class="icon icon-edit"></i></a>
											<a href="#" data-tooltip="top" title="Delete"><i class="icon icon-trash"></i></a>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>

				<div class="col">
					<div class="box h-100">
						<div class="box__header">
							<h4 class="box__title">Last posts</h4>
						</div>
						<div class="box__body">
							<table class="table table_borderless">
								<thead>
									<tr>
										<th>Title</th>
										<th>Date</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>
											<div class="d-flex gap-3">
												<img class="d-block flex-shrink-0 w-4rem h-4rem fit-cover radius" src="https://demo.zakandaiev.com/upload/demo/post-1.jpg" data-fancybox>
												<div>
													<p class="m-b-0"><strong>Lorem post #1</strong></p>
													<p class="color-muted"><small>Category name</small></p>
												</div>
											</div>
										</td>
										<td>12.07.2023 at 11:47</td>
										<td class="text-right">
											<a href="#" class="btn btn_outline btn_cancel">View</a>
										</td>
									</tr>
									<tr>
										<td>
											<div class="d-flex gap-3">
												<img class="d-block flex-shrink-0 w-4rem h-4rem fit-cover radius" src="https://demo.zakandaiev.com/upload/demo/post-2.jpg" data-fancybox>
												<div>
													<p class="m-b-0"><strong>Lorem post #2</strong></p>
													<p class="color-muted"><small>Category name</small></p>
												</div>
											</div>
										</td>
										<td>12.07.2023 at 11:47</td>
										<td class="text-right">
											<a href="#" class="btn btn_outline btn_cancel">View</a>
										</td>
									</tr>
									<tr>
										<td>
											<div class="d-flex gap-3">
												<img class="d-block flex-shrink-0 w-4rem h-4rem fit-cover radius" src="https://demo.zakandaiev.com/upload/demo/post-3.jpg" data-fancybox>
												<div>
													<p class="m-b-0"><strong>Lorem post #3</strong></p>
													<p class="color-muted"><small>Category name</small></p>
												</div>
											</div>
										</td>
										<td>12.07.2023 at 11:47</td>
										<td class="text-right">
											<a href="#" class="btn btn_outline btn_cancel">View</a>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>

				<div class="col">
					<div class="box h-100">
						<div class="box__header">
							<h4 class="box__title">Calendar</h4>
						</div>
						<div class="box__body">
							<div class="calendar" data-calendar></div>
						</div>
					</div>
				</div>

			</div>

		</div>
	</section>

	<?php Theme::block('navbar-bottom'); ?>

</main>

<?php Theme::footer(); ?>
