<?php

############################# ADMIN SIDEBAR #############################
Hook::setData('admin.sidebar', []);

Hook::register('admin_sidebar_append', function($route) {
	$sidebar = Hook::getData('admin.sidebar') ?? [];
	$sidebar[] = $route;
	Hook::setData('admin.sidebar', $sidebar);
});

Hook::register('admin_sidebar_prepend', function($route) {
	$sidebar = Hook::getData('admin.sidebar') ?? [];
	array_unshift($sidebar, $route);
	Hook::setData('admin.sidebar', $sidebar);
});

Hook::register('admin_sidebar_append_after', function($position, $append_route) {
	$sidebar = Hook::getData('admin.sidebar') ?? [];
	$sidebar_new = [];

	foreach($sidebar as $route) {
		$sidebar_new[] = $route;

		if(is_string($route['route']) && trim($route['route'], '/') === trim($position, '/')) {
			$sidebar_new[] = $append_route;
		}
	}

	Hook::setData('admin.sidebar', $sidebar_new);
});

// ############################# NOTIFICATION #############################
// $GLOBALS['admin_notification'] = [];

// Hook::register('notification_add', function($type, $data) {
// 	if(empty($type) || empty($data)) {
// 		return false;
// 	}

// 	$GLOBALS['admin_notification'][$type] = $data;
// });
// Hook::register('notification_modify', function($type, $data) {
// 	if(!isset($GLOBALS['admin_notification'][$type]) || empty($data)) {
// 		return false;
// 	}

// 	$GLOBALS['admin_notification'][$type] = $data;
// });

// ############################# TRANSLATION #############################
// Hook::register('translation_add', function($data) {
// 	Log::write('Translation: ' . $data['file'] . ' added for ' . $data['module'] . ' module by user ID: ' . User::get()->id . ' from IP: ' . Request::$ip, 'translation');
// });
// Hook::register('translation_edit', function($data) {
// 	Log::write('Translation: ' . $data['file'] . ' edited for ' . $data['module'] . ' module by user ID: ' . User::get()->id . ' from IP: ' . Request::$ip, 'translation');
// });

// ############################# GROUP #############################
// Hook::register('group_add', function($data) {
// 	Log::write('Group ID: ' . $data->form_data['item_id'] . ' added by user ID: ' . User::get()->id . ' from IP: ' . Request::$ip, 'group');
// });
// Hook::register('group_edit', function($data) {
// 	Log::write('Group ID: ' . $data->form_data['item_id'] . ' edited by user ID: ' . User::get()->id . ' from IP: ' . Request::$ip, 'group');
// });
// Hook::register('group_delete', function($data) {
// 	Log::write('Group ID: ' . $data->form_data['item_id'] . ' deleted by user ID: ' . User::get()->id . ' from IP: ' . Request::$ip, 'group');
// });

// ############################# USER #############################
// Hook::register('user_add', function($data) {
// 	Log::write('User ID: ' . $data->form_data['item_id'] . ' added by user ID: ' . User::get()->id . ' from IP: ' . Request::$ip, 'user');
// });
// Hook::register('user_edit', function($data) {
// 	Log::write('User ID: ' . $data->form_data['item_id'] . ' edited by user ID: ' . User::get()->id . ' from IP: ' . Request::$ip, 'user');
// });
// Hook::register('user_delete', function($data) {
// 	Log::write('User ID: ' . $data->form_data['item_id'] . ' deleted by user ID: ' . User::get()->id . ' from IP: ' . Request::$ip, 'user');
// });

// ############################# COMMENT #############################
// Hook::register('comment_add', function($data) {
// 	$page = \Module\Admin\Model\Page::getInstance()->getPage($data->fields['page_id']);
// 	$parent = null;
// 	$notification_type = 'comment_add';
// 	if(!empty($data->fields['parent'])) {
// 		$notification_type = 'comment_reply';
// 		$parent = \Module\Admin\Model\Comment::getInstance()->getCommentById($data->fields['parent']);
// 	}

// 	$comment_data = new \stdClass();
// 	$comment_data->author = $data->fields['author'];
// 	$comment_data->message = $data->fields['message'];
// 	$comment_data->url = $page->url;
// 	$comment_data->title = $page->title;
// 	$comment_data->parent_author = $parent->author ?? null;

// 	Notification::create($notification_type, $comment_data->author, $comment_data);

// 	if($comment_data->author !== $page->author) {
// 		Notification::create($notification_type, $page->author, $comment_data);
// 	} else if($comment_data->author !== $comment_data->parent_author) {
// 		Notification::create($notification_type, $page->author, $comment_data);
// 	}

// 	Log::write('Comment ID: ' . $data->form_data['item_id'] . ' added by user ID: ' . User::get()->id . ' from IP: ' . Request::$ip, 'comment');
// });
// Hook::register('comment_edit', function($data) {
// 	Log::write('Comment ID: ' . $data->form_data['item_id'] . ' edited by user ID: ' . User::get()->id . ' from IP: ' . Request::$ip, 'comment');
// });
// Hook::register('comment_delete', function($data) {
// 	Log::write('Comment ID: ' . $data->form_data['item_id'] . ' deleted by user ID: ' . User::get()->id . ' from IP: ' . Request::$ip, 'comment');
// });
// Hook::register('comment_toggle', function($data) {
// 	$type = $data->fields['is_approved'] ? 'approved' : 'disapproved';

// 	Log::write('Comment ID: ' . $data->form_data['item_id'] . ' ' . $type . ' by user ID: ' . User::get()->id . ' from IP: ' . Request::$ip, 'comment');
// });

// ############################# MENU #############################
// Hook::register('menu_add', function($data) {
// 	Log::write('Menu ID: ' . $data->form_data['item_id'] . ' added by user ID: ' . User::get()->id . ' from IP: ' . Request::$ip, 'menu');
// });
// Hook::register('menu_edit', function($data) {
// 	Log::write('Menu ID: ' . $data->form_data['item_id'] . ' edited by user ID: ' . User::get()->id . ' from IP: ' . Request::$ip, 'menu');
// });
// Hook::register('menu_delete', function($data) {
// 	Log::write('Menu ID: ' . $data->form_data['item_id'] . ' deleted by user ID: ' . User::get()->id . ' from IP: ' . Request::$ip, 'menu');
// });
// Hook::register('menu_items_edit', function($data) {
// 	Log::write('Menu ID: ' . $data->form_data['item_id'] . ' changed items by user ID: ' . User::get()->id . ' from IP: ' . Request::$ip, 'menu');
// });

// ############################# PAGE #############################
// Hook::register('page_add', function($data) {
// 	Sitemap::update();

// 	Log::write('Page ID: ' . $data->form_data['item_id'] . ' added by user ID: ' . User::get()->id . ' from IP: ' . Request::$ip, 'page');

// 	$page_origin = \Module\Admin\Model\Page::getInstance()->getPage($data->form_data['item_id']);

// 	$notification_type = $page_origin->is_category ? 'page_add_category' : 'page_add';

// 	$page_data = new \stdClass();

// 	$page_data->url = $page_origin->url;
// 	$page_data->author = $page_origin->author;

// 	$page_data->title = $data->fields['title'];
// 	$page_data->image = $data->fields['image'];
// 	$page_data->excerpt = $data->fields['excerpt'];

// 	Notification::create($notification_type, $page_data->author, $page_data);
// });

// Hook::register('page_edit', function($data) {
// 	Sitemap::update();

// 	Log::write('Page ID: ' . $data->form_data['item_id'] . ' edited by user ID: ' . User::get()->id . ' from IP: ' . Request::$ip, 'page');
// });

// Hook::register('page_delete', function($data) {
// 	Sitemap::update();

// 	Log::write('Page ID: ' . $data->form_data['item_id'] . ' deleted by user ID: ' . User::get()->id . ' from IP: ' . Request::$ip, 'page');
// });

// ############################# PROFILE #############################
// Hook::register('user_change_login', function($data) {
// 	Mail::send('ChangeLogin', $data->email, $data);

// 	Notification::create('user_change_login', $data->id, $data);

// 	Log::write('User ID: ' . $data->id . ' changed login from IP: ' . Request::$ip, 'user');
// });
// Hook::register('user_change_password', function($data) {
// 	Mail::send('ChangePassword', $data->email, $data);

// 	Notification::create('user_change_password', $data->id, $data);

// 	Log::write('User ID: ' . $data->id . ' changed password from IP: ' . Request::$ip, 'user');
// });
// Hook::register('user_change_email', function($data) {
// 	Mail::send('ChangeEmail', $data->email, $data);

// 	Notification::create('user_change_email', $data->id, $data);

// 	Log::write('User ID: ' . $data->id . ' changed email from IP: ' . Request::$ip, 'user');
// });
// Hook::register('user_change_name', function($data) {
// 	Notification::create('user_change_name', $data->id, $data);

// 	Log::write('User ID: ' . $data->id . ' changed name from IP: ' . Request::$ip, 'user');
// });
// Hook::register('user_change_contacts', function($data) {
// 	Log::write('User ID: ' . $data->form_data['item_id'] . ' changed contact information from IP: ' . Request::$ip, 'user');
// });

// ############################# RUN #############################
// // NOTIFICATIONS
// Hook::run('notification_add', 'user_register', [
// 		'name' => __('Registration'),
// 		'icon' => 'user-plus',
// 		'color' => 'success',
// 		'user_can_manage' => false
// 	]
// );
// Hook::run('notification_add', 'user_restore', [
// 		'name' => __('Password restore'),
// 		'icon' => 'unlock',
// 		'color' => 'danger',
// 		'user_can_manage' => false
// 	]
// );
// Hook::run('notification_add', 'user_authorize', [
// 		'name' => __('Authorization'),
// 		'icon' => 'log-in',
// 		'color' => 'warning',
// 		'type' => 'web'
// 	]
// );
// Hook::run('notification_add', 'user_change_name', [
// 		'name' => __('Name change'),
// 		'icon' => 'edit',
// 		'color' => 'warning',
// 		'type' => 'web'
// 	]
// );
// Hook::run('notification_add', 'user_change_email', [
// 		'name' => __('Email change'),
// 		'icon' => 'mail',
// 		'color' => 'danger'
// 	]
// );
// Hook::run('notification_add', 'user_change_login', [
// 		'name' => __('Login change'),
// 		'icon' => 'at-sign',
// 		'color' => 'danger'
// 	]
// );
// Hook::run('notification_add', 'user_change_password', [
// 		'name' => __('Password change'),
// 		'icon' => 'lock',
// 		'color' => 'danger'
// 	]
// );
// Hook::run('notification_add', 'page_add', [
// 		'name' => __('Page creation'),
// 		'icon' => 'file-text',
// 		'color' => 'primary',
// 		'type' => 'web'
// 	]
// );
// Hook::run('notification_add', 'page_add_category', [
// 		'name' => __('Category creation'),
// 		'icon' => 'folder',
// 		'color' => 'primary',
// 		'type' => 'web'
// 	]
// );
// Hook::run('notification_add', 'comment_add', [
// 		'name' => __('New comment'),
// 		'icon' => 'message-square',
// 		'color' => 'primary',
// 		'type' => 'web'
// 	]
// );
// Hook::run('notification_add', 'comment_reply', [
// 		'name' => __('New comment reply'),
// 		'icon' => 'corner-down-right',
// 		'color' => 'primary',
// 		'type' => 'web'
// 	]
// );

############################# RUN - SIDEBAR #############################
Hook::run('admin_sidebar_append', [
	'icon' => 'home',
	'name' => __('admin.sidebar.dashboard'),
	'route' => '/admin/dashboard',
	'is_public' => true
]);
Hook::run('admin_sidebar_append', [
	'icon' => 'user-circle',
	'label' => function() {
		$notifications_count = User::get()->notifications_count;
		return $notifications_count > 0 ? $notifications_count : null;
	},
	'name' => __('admin.sidebar.profile'),
	'route' => '/admin/profile',
	'is_public' => true
]);
Hook::run('admin_sidebar_append', [
	'name' => __('admin.sidebar.interaction'),
	'is_separator' => true,
	'route' => '/admin/contact'
]);
Hook::run('admin_sidebar_append', [
	'icon' => 'message-circle',
	'label' => function() {
		$count = \Module\Admin\Model\Contact::getInstance()->countUnreadContacts();
		return $count > 0 ? $count : null;
	},
	'name' => __('admin.sidebar.messages'),
	'route' => '/admin/contact'
]);
Hook::run('admin_sidebar_append', [
	'name' => __('admin.sidebar.content'),
	'is_separator' => true,
	'route' => '/admin/page'
]);
Hook::run('admin_sidebar_append', [
	'icon' => 'file-text',
	'name' => __('admin.sidebar.pages'),
	'route' => '/admin/page'
]);
Hook::run('admin_sidebar_append', [
	'icon' => 'message',
	'label' => function() {
		$count = \Module\Admin\Model\Comment::getInstance()->countUnapprovedComments();
		return $count > 0 ? $count : null;
	},
	'name' => __('admin.sidebar.comments'),
	'route' => '/admin/comment'
]);
Hook::run('admin_sidebar_append', [
	'icon' => 'menu-2',
	'name' => __('admin.sidebar.menu'),
	'route' => '/admin/menu'
]);
Hook::run('admin_sidebar_append', [
	'icon' => 'world',
	'name' => __('admin.sidebar.translations'),
	'route' => '/admin/translation'
]);
Hook::run('admin_sidebar_append', [
	'name' => __('admin.sidebar.administration'),
	'is_separator' => true,
	'route' => '/admin/user'
]);
Hook::run('admin_sidebar_append', [
	'icon' => 'users',
	'name' => __('admin.sidebar.users'),
	'route' => [
		__('admin.sidebar.users') => '/admin/user',
		__('admin.sidebar.groups') => '/admin/group'
	]
]);
Hook::run('admin_sidebar_append', [
	'icon' => 'settings',
	'name' => __('admin.sidebar.settings'),
	'route' => [
		__('admin.sidebar.main') => '/admin/setting/main',
		__('admin.sidebar.site') => '/admin/setting/site',
		__('admin.sidebar.contacts') => '/admin/setting/contact',
		__('admin.sidebar.optimizations') => '/admin/setting/optimization'
	]
]);
