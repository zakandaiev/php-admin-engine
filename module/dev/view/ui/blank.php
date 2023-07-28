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

			<h2 class="section__title">Blank Page</h2>

			<div class="box">
				<div class="box__header">
					<h4 class="box__title">Title</h4>
					<h5 class="box__subtitle">Subitle</h5>
				</div>
				<div class="box__body">
					Lorem ipsum, dolor sit amet consectetur adipisicing elit. Veniam vitae laborum corrupti quis in assumenda asperiores architecto ducimus alias sunt ab distinctio ut consectetur amet laudantium quas, autem est eveniet!
				</div>
			</div>

		</div>
	</section>

	<?php Theme::block('navbar-bottom'); ?>

</main>

<?php Theme::footer(); ?>
