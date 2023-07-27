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

			<h2 class="section__title">Tabs</h2>

			<div class="row cols-xs-1 cols-md-2 gap-xs">

				<div class="col">
					<div class="tab">
						<nav class="tab__nav">
							<a href="#tab-1" class="tab__nav-item active">Tab 1</a>
							<a href="#tab-2" class="tab__nav-item">Tab 2</a>
							<a href="#tab-3" class="tab__nav-item">Tab 3</a>
						</nav>
						<div id="tab-1" class="tab__body active">
							<h4 class="tab__title">Lorem ipsum 1</h4>
							<p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Non voluptatem repellat error ratione ea ex! Ipsum laudantium veritatis in eligendi vitae ullam aperiam sapiente soluta numquam unde possimus, harum accusamus.</p>
							<p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Non voluptatem repellat error ratione ea ex! Ipsum laudantium veritatis in eligendi vitae ullam aperiam sapiente soluta numquam unde possimus, harum accusamus.</p>
						</div>
						<div id="tab-2" class="tab__body">
							<h4 class="tab__title">Lorem ipsum 2</h4>
							<p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Non voluptatem repellat error ratione ea ex! Ipsum laudantium veritatis in eligendi vitae ullam aperiam sapiente soluta numquam unde possimus, harum accusamus.</p>
							<p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Non voluptatem repellat error ratione ea ex! Ipsum laudantium veritatis in eligendi vitae ullam aperiam sapiente soluta numquam unde possimus, harum accusamus.</p>
						</div>
						<div id="tab-3" class="tab__body">
							<h4 class="tab__title">Lorem ipsum 3</h4>
							<p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Non voluptatem repellat error ratione ea ex! Ipsum laudantium veritatis in eligendi vitae ullam aperiam sapiente soluta numquam unde possimus, harum accusamus.</p>
							<p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Non voluptatem repellat error ratione ea ex! Ipsum laudantium veritatis in eligendi vitae ullam aperiam sapiente soluta numquam unde possimus, harum accusamus.</p>
						</div>
					</div>
				</div>

				<div class="col">
					<div class="tab">
						<nav class="tab__nav tab__nav_icon">
							<a href="#tab-icon-1" class="tab__nav-item active"><i class="icon icon-home"></i></a>
							<a href="#tab-icon-2" class="tab__nav-item"><i class="icon icon-settings"></i></a>
							<a href="#tab-icon-3" class="tab__nav-item"><i class="icon icon-mail"></i></a>
						</nav>
						<div id="tab-icon-1" class="tab__body active">
							<h4 class="tab__title">Lorem ipsum 1</h4>
							<p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Non voluptatem repellat error ratione ea ex! Ipsum laudantium veritatis in eligendi vitae ullam aperiam sapiente soluta numquam unde possimus, harum accusamus.</p>
							<p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Non voluptatem repellat error ratione ea ex! Ipsum laudantium veritatis in eligendi vitae ullam aperiam sapiente soluta numquam unde possimus, harum accusamus.</p>
						</div>
						<div id="tab-icon-2" class="tab__body">
							<h4 class="tab__title">Lorem ipsum 2</h4>
							<p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Non voluptatem repellat error ratione ea ex! Ipsum laudantium veritatis in eligendi vitae ullam aperiam sapiente soluta numquam unde possimus, harum accusamus.</p>
							<p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Non voluptatem repellat error ratione ea ex! Ipsum laudantium veritatis in eligendi vitae ullam aperiam sapiente soluta numquam unde possimus, harum accusamus.</p>
						</div>
						<div id="tab-icon-3" class="tab__body">
							<h4 class="tab__title">Lorem ipsum 3</h4>
							<p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Non voluptatem repellat error ratione ea ex! Ipsum laudantium veritatis in eligendi vitae ullam aperiam sapiente soluta numquam unde possimus, harum accusamus.</p>
							<p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Non voluptatem repellat error ratione ea ex! Ipsum laudantium veritatis in eligendi vitae ullam aperiam sapiente soluta numquam unde possimus, harum accusamus.</p>
						</div>
					</div>
				</div>

				<div class="col">
					<div class="tab tab_vertical">
						<nav class="tab__nav">
							<a href="#tab-vertical-1" class="tab__nav-item active">Tab 1</a>
							<a href="#tab-vertical-2" class="tab__nav-item">Tab 2</a>
							<a href="#tab-vertical-3" class="tab__nav-item">Tab 3</a>
						</nav>
						<div id="tab-vertical-1" class="tab__body active">
							<h4 class="tab__title">Lorem ipsum 1</h4>
							<p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Non voluptatem repellat error ratione ea ex! Ipsum laudantium veritatis in eligendi vitae ullam aperiam sapiente soluta numquam unde possimus, harum accusamus.</p>
							<p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Non voluptatem repellat error ratione ea ex! Ipsum laudantium veritatis in eligendi vitae ullam aperiam sapiente soluta numquam unde possimus, harum accusamus.</p>
						</div>
						<div id="tab-vertical-2" class="tab__body">
							<h4 class="tab__title">Lorem ipsum 2</h4>
							<p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Non voluptatem repellat error ratione ea ex! Ipsum laudantium veritatis in eligendi vitae ullam aperiam sapiente soluta numquam unde possimus, harum accusamus.</p>
							<p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Non voluptatem repellat error ratione ea ex! Ipsum laudantium veritatis in eligendi vitae ullam aperiam sapiente soluta numquam unde possimus, harum accusamus.</p>
						</div>
						<div id="tab-vertical-3" class="tab__body">
							<h4 class="tab__title">Lorem ipsum 3</h4>
							<p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Non voluptatem repellat error ratione ea ex! Ipsum laudantium veritatis in eligendi vitae ullam aperiam sapiente soluta numquam unde possimus, harum accusamus.</p>
							<p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Non voluptatem repellat error ratione ea ex! Ipsum laudantium veritatis in eligendi vitae ullam aperiam sapiente soluta numquam unde possimus, harum accusamus.</p>
						</div>
					</div>
				</div>

				<div class="col">
					<div class="tab tab_vertical">
						<nav class="tab__nav tab__nav_icon">
							<a href="#tab-vertical-icon-1" class="tab__nav-item active"><i class="icon icon-home"></i></a>
							<a href="#tab-vertical-icon-2" class="tab__nav-item"><i class="icon icon-settings"></i></a>
							<a href="#tab-vertical-icon-3" class="tab__nav-item"><i class="icon icon-mail"></i></a>
						</nav>
						<div id="tab-vertical-icon-1" class="tab__body active">
							<h4 class="tab__title">Lorem ipsum 1</h4>
							<p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Non voluptatem repellat error ratione ea ex! Ipsum laudantium veritatis in eligendi vitae ullam aperiam sapiente soluta numquam unde possimus, harum accusamus.</p>
							<p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Non voluptatem repellat error ratione ea ex! Ipsum laudantium veritatis in eligendi vitae ullam aperiam sapiente soluta numquam unde possimus, harum accusamus.</p>
						</div>
						<div id="tab-vertical-icon-2" class="tab__body">
							<h4 class="tab__title">Lorem ipsum 2</h4>
							<p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Non voluptatem repellat error ratione ea ex! Ipsum laudantium veritatis in eligendi vitae ullam aperiam sapiente soluta numquam unde possimus, harum accusamus.</p>
							<p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Non voluptatem repellat error ratione ea ex! Ipsum laudantium veritatis in eligendi vitae ullam aperiam sapiente soluta numquam unde possimus, harum accusamus.</p>
						</div>
						<div id="tab-vertical-icon-3" class="tab__body">
							<h4 class="tab__title">Lorem ipsum 3</h4>
							<p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Non voluptatem repellat error ratione ea ex! Ipsum laudantium veritatis in eligendi vitae ullam aperiam sapiente soluta numquam unde possimus, harum accusamus.</p>
							<p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Non voluptatem repellat error ratione ea ex! Ipsum laudantium veritatis in eligendi vitae ullam aperiam sapiente soluta numquam unde possimus, harum accusamus.</p>
						</div>
					</div>
				</div>

			</div>

		</div>
	</section>

	<?php Theme::block('navbar-bottom'); ?>

</main>

<?php Theme::footer(); ?>
