<?php Theme::header(); ?>

<?php Theme::block('sidebar'); ?>

<main class="page-content__inner">

	<?php Theme::block('navbar-top'); ?>

	<section class="section section_grow section_offset">
		<div class="container-fluid">

			<nav class="breadcrumb">
				<span class="breadcrumb__item"><a href="/">Home</a></span>
				<span class="breadcrumb__item">Dev UI</span>
			</nav>

			<h2 class="section__title">E-Commerce</h2>

			<div class="row fill cols-xs-1 cols-md-2 gap-xs">

				<div class="col">
					<div class="row gap-xs cols-xs-1 cols-sm-2">

						<div class="col">
							<div class="box h-100">
								<div class="box__header box__header_icon">
									<h4 class="box__title">
										<span>Sales</span>
										<span class="box__icon box__icon_primary">
											<i class="icon icon-truck"></i>
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
										<span>Earnings</span>
										<span class="box__icon box__icon_primary">
											<i class="icon icon-currency-dollar"></i>
										</span>
									</h4>
								</div>
								<div class="box__body">
									<h3 class="m-t--2">$21.300</h3>
									<span class="label label_light label_success">+7.75%</span>
									<span class="color-muted">since last month</span>
								</div>
							</div>
						</div>

						<div class="col">
							<div class="box h-100">
								<div class="box__header box__header_icon">
									<h4 class="box__title">
										<span>Orders</span>
										<span class="box__icon box__icon_primary">
											<i class="icon icon-shopping-cart"></i>
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
										<span>Visitors</span>
										<span class="box__icon box__icon_primary">
											<i class="icon icon-users-group"></i>
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
							<h4 class="box__title">Polar area chart</h4>
						</div>
						<div class="box__body">
							<canvas class="chart">
								{"type":"line","data":{"labels":["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],"datasets":[{"label":"Sales ($)","fill":true,"borderColor":"#3b7ddd","data":[2115,1562,1584,1892,1587,1923,2566,2448,2805,3438,2917,3327]}]},"options":{"maintainAspectRatio":false,"legend":{"display":false},"tooltips":{"intersect":false},"hover":{"intersect":true},"plugins":{"filler":{"propagate":false}},"scales":{"xAxes":[{"reverse":true,"gridLines":{"color":"rgba(0,0,0,0.0)"}}],"yAxes":[{"ticks":{"stepSize":1000},"display":true,"borderDash":[3,3],"gridLines":{"color":"rgba(0,0,0,0.0)","fontColor":"#fff"}}]}}}
							</canvas>
						</div>
					</div>
				</div>

				<div class="col">
					<div class="box h-100">
						<div class="box__header">
							<h4 class="box__title">Calendar</h4>
						</div>
						<div class="box__body">
							<div class="calendar" data-calendar>2023-07-04 - 2023-07-20</div>
						</div>
					</div>
				</div>

				<div class="col">
					<div class="box h-100">
						<div class="box__header">
							<h4 class="box__title">Latest products</h4>
						</div>
						<div class="box__body">
							<table class="table table_borderless">
								<thead>
									<tr>
										<th>Name</th>
										<th>Price</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>
											<div class="d-flex gap-2">
												<div class="flex-shrink-0 w-4rem h-4rem p-2 background-body fit-cover radius">
													<img class="d-inline-block w-100 h-100" src="https://demo.adminkit.io/img/icons/brand-1.svg" data-fancybox>
												</div>
												<div>
													<p class="m-b-0"><strong>Product #1</strong></p>
													<p class="color-muted"><small>Brandr #1</small></p>
												</div>
											</div>
										</td>
										<td>$100.00</td>
										<td class="text-right">
											<a href="#" class="btn btn_outline btn_cancel">View</a>
										</td>
									</tr>
									<tr>
										<td>
											<div class="d-flex gap-2">
												<div class="flex-shrink-0 w-4rem h-4rem p-2 background-body fit-cover radius">
													<img class="d-inline-block w-100 h-100" src="https://demo.adminkit.io/img/icons/brand-2.svg" data-fancybox>
												</div>
												<div>
													<p class="m-b-0"><strong>Product #2</strong></p>
													<p class="color-muted"><small>Brandr #2</small></p>
												</div>
											</div>
										</td>
										<td>$200.00</td>
										<td class="text-right">
											<a href="#" class="btn btn_outline btn_cancel">View</a>
										</td>
									</tr>
									<tr>
										<td>
											<div class="d-flex gap-2">
												<div class="flex-shrink-0 w-4rem h-4rem p-2 background-body fit-cover radius">
													<img class="d-inline-block w-100 h-100" src="https://demo.adminkit.io/img/icons/brand-5.svg" data-fancybox>
												</div>
												<div>
													<p class="m-b-0"><strong>Product #3</strong></p>
													<p class="color-muted"><small>Brandr #3</small></p>
												</div>
											</div>
										</td>
										<td>$300.00</td>
										<td class="text-right">
											<a href="#" class="btn btn_outline btn_cancel">View</a>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>

			</div>

		</div>
	</section>

	<?php Theme::block('navbar-bottom'); ?>

</main>

<?php Theme::footer(); ?>
