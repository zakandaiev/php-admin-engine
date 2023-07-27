<aside class="sidebar">
	<a class="sidebar__logo" href="https://demo.zakandaiev.com/admin">
		<img src="https://demo.zakandaiev.com/upload/demo/logo-alt.png" alt="Logo">
	</a>
	<nav class="sidebar__nav">
		<a href="/" class="sidebar__item active">
			<i class="icon icon-home"></i>
			<span class="sidebar__text">Dashboard</span>
		</a>
		<a href="/profile" class="sidebar__item">
			<i class="icon icon-user-circle"></i>
			<span class="sidebar__text">Profile</span>
		</a>

		<span class="sidebar__separator">Interaction</span>
		<a href="/message" class="sidebar__item">
			<i class="icon icon-message-circle"></i>
			<span class="sidebar__text">Messages</span>
			<span class="label label_primary">10+</span>
		</a>

		<button type="button" class="sidebar__collapse active">
			<span class="sidebar__item active">
				<i class="icon icon-user-circle"></i>
				<span class="sidebar__text">Collapse</span>
			</span>

			<div class="sidebar__collapse-menu">
				<a href="/message" class="sidebar__collapse-item">
					<span class="sidebar__text">Item 1</span>
				</a>
				<a href="/message" class="sidebar__collapse-item active">
					<span class="sidebar__text">Item 2</span>
					<span class="label label_primary">2</span>
				</a>
			</div>
		</button>

		<a href="/profile" class="sidebar__item">
			<i class="icon icon-user-circle"></i>
			<span class="sidebar__text">Profile</span>
		</a>
	</nav>
</aside>

<script src="<?= Asset::url() ?>/js/sidebar.js"></script>
