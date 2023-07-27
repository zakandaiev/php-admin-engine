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

			<h2 class="section__title">Forms</h2>

			<div class="row fill gap-xs cols-xs-1 cols-md-2">
				<div class="col">
					<div class="row fill gap-xs cols-xs-1">

						<div class="col">
							<div class="box">
								<div class="box__header">
									<h5 class="box__title">Input</h5>
								</div>
								<div class="box__body">
									<input type="text" placeholder="Input">
								</div>
							</div>
						</div>

						<div class="col">
							<div class="box">
								<div class="box__header">
									<h5 class="box__title">Textarea</h5>
								</div>
								<div class="box__body">
									<textarea placeholder="Textarea"></textarea>
								</div>
							</div>
						</div>

						<div class="col">
							<div class="box">
								<div class="box__header">
									<h5 class="box__title">Checkboxes</h5>
								</div>
								<div class="box__body">

									<label>
										<input type="checkbox">
										<span>Option</span>
									</label>

									<br>

									<label>
										<input type="checkbox" disabled>
										<span>Option disabled</span>
									</label>

									<br>

									<label class="m-b-0 m-r-3">
										<input type="checkbox">
										<span>Option 1</span>
									</label>

									<label class="m-b-0 m-r-3">
										<input type="checkbox">
										<span>Option 2</span>
									</label>

									<label class="m-b-0 m-r-3">
										<input type="checkbox">
										<span>Option 3</span>
									</label>

								</div>
							</div>
						</div>

						<div class="col">
							<div class="box">
								<div class="box__header">
									<h5 class="box__title">Radios</h5>
								</div>
								<div class="box__body">

									<label>
										<input type="radio" name="radio-example">
										<span>Option</span>
									</label>

									<br>

									<label>
										<input type="radio" name="radio-example" disabled>
										<span>Option disabled</span>
									</label>

									<br>

									<label class="m-b-0 m-r-3">
										<input type="radio" name="radio-example">
										<span>Option 1</span>
									</label>

									<label class="m-b-0 m-r-3">
										<input type="radio" name="radio-example">
										<span>Option 2</span>
									</label>

									<label class="m-b-0 m-r-3">
										<input type="radio" name="radio-example">
										<span>Option 3</span>
									</label>

								</div>
							</div>
						</div>

						<div class="col">
							<div class="box">
								<div class="box__header">
									<h5 class="box__title">Switches</h5>
								</div>
								<div class="box__body">

									<label class="switch">
										<input type="checkbox">
										<span class="switch__slider"></span>
										<span>Switcher</span>
									</label>

									<br>

									<label class="switch">
										<input type="checkbox" disabled>
										<span class="switch__slider"></span>
										<span>Switcher disabled</span>
									</label>

									<br>

									<label class="switch m-b-0 m-r-3">
										<input type="checkbox">
										<span class="switch__slider"></span>
										<span>Switcher 1</span>
									</label>

									<label class="switch m-b-0 m-r-3">
										<input type="checkbox">
										<span class="switch__slider"></span>
										<span>Switcher 2</span>
									</label>

									<label class="switch m-b-0 m-r-3">
										<input type="checkbox">
										<span class="switch__slider"></span>
										<span>Switcher 3</span>
									</label>

								</div>
							</div>
						</div>

						<div class="col">
							<div class="box">
								<div class="box__header">
									<h5 class="box__title">Groups</h5>
								</div>
								<div class="box__body">

									<div class="input-group">
										<span>@</span>
										<input type="text" placeholder="Username">
									</div>

									<div class="input-group">
										<input type="text" placeholder="Email">
										<span>@example.com</span>
									</div>

									<div class="input-group">
										<input type="text" placeholder="Search">
										<a href="#">Go!</a>
									</div>

									<div class="input-group">
										<select data-placeholder="Select...">
											<option data-placeholder="true"><option>
											<option>One</option>
											<option>Two</option>
											<option>Three</option>
										</select>
										<button type="button" class="cursor-pointer">Go!</button>
									</div>

									<div class="input-group">
										<button type="button" class="cursor-pointer"><i class="icon icon-minus"></i></button>
										<input type="number" placeholder="Amount">
										<button type="button" class="cursor-pointer"><i class="icon icon-plus"></i></button>
									</div>

								</div>
							</div>
						</div>

					</div>
				</div>

				<div class="col">
					<div class="row fill gap-xs cols-xs-1">

						<div class="col">
							<div class="box">
								<div class="box__header">
									<h5 class="box__title">Selects</h5>
								</div>
								<div class="box__body">
									<select data-placeholder="Single">
										<option data-placeholder="true"></option>
										<option>One</option>
										<option>Two</option>
										<option>Three</option>
										<option disabled>Disabled</option>
									</select>

									<select multiple data-placeholder="Multiple">
										<optgroup label="Dairy">
											<option value="Milk">Milk</option>
											<option value="Cheese">Cheese</option>
											<option value="Butter">Butter</option>
											<option value="Ice Cream">Ice Cream</option>
										</optgroup>
										<optgroup label="Meat">
											<option value="Beef">Beef</option>
											<option value="Ham">Ham</option>
											<option value="Pork">Pork</option>
											<option value="Sausage">Sausage</option>
											<option value="Chicken">Chicken</option>
											<option value="Turkey">Turkey</option>
										</optgroup>
										<optgroup label="Fruits">
											<option value="Apple">Apple</option>
											<option value="Banana">Banana</option>
											<option value="Grape">Grape</option>
											<option value="Orange">Orange</option>
											<option value="Strawberry">Strawberry</option>
											<option value="Blueberry">Blueberry</option>
											<option value="Raspberry">Raspberry</option>
										</optgroup>
										<optgroup label="Vegetables">
											<option value="Carrot">Carrot</option>
											<option value="Tomato">Tomato</option>
											<option value="Broccoli">Broccoli</option>
											<option value="Celery">Celery</option>
											<option value="Corn">Corn</option>
											<option value="Pumpkin">Pumpkin</option>
											<option value="Kale">Kale</option>
											<option value="Spinach">Spinach</option>
										</optgroup>
									</select>
								</div>
							</div>
						</div>

						<div class="col">
						<div class="box">
							<div class="box__header">
								<h5 class="box__title">Dates</h5>
							</div>
							<div class="box__body">

								<div class="row cols-xs-2 gap-xs">

									<div class="col">
										<label>Date</label>
										<input type="text" data-picker="date">
									</div>

									<div class="col">
										<label>DateTime</label>
										<input type="text" data-picker="datetime">
									</div>

									<div class="col">
										<label>Multiple</label>
										<input type="text" data-picker="date" data-multiple>
									</div>

									<div class="col">
										<label>Range</label>
										<input type="text" data-picker="date" data-range>
									</div>

									<div class="col">
										<label>Month picker</label>
										<input type="text" data-picker="month">
									</div>

									<div class="col">
										<label>Time picker</label>
										<input type="text" data-picker="time">
									</div>

								</div>

							</div>
						</div>
					</div>

					<div class="col">
						<div class="box">
							<div class="box__header">
								<h5 class="box__title">Input Masks</h5>
							</div>
							<div class="box__body">

								<div class="row cols-xs-2 gap-xs">

									<div class="col">
										<label>Phone</label>
										<input type="text" data-maska="+38 (###) ### ## ##">
									</div>

									<div class="col">
										<label>HEX-color</label>
										<input type="text" data-maska="!#HHHHHH" data-maska-tokens="H:[0-9a-fA-F]">
									</div>

									<div class="col">
										<label>IP-address</label>
										<input type="text" data-maska="#00.#00.#00.#00" data-maska-tokens="0:[0-9]:optional">
									</div>

									<div class="col">
										<label>Money</label>
										<input type="text" data-maska="0.99" data-maska-tokens="0:\d:multiple|9:\d:optional">
									</div>

								</div>

							</div>
						</div>
					</div>

					</div>

				</div>

			</div>

		</div>
	</section>

	<?php Theme::block('navbar-bottom'); ?>

</main>

<?php Theme::footer(); ?>
