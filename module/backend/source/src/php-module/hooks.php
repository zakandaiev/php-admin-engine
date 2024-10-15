<?php

use engine\module\Hook;
use engine\module\Module;

############################# SIDEBAR #############################
function formatRoute($route = [])
{
  $route['module'] = Module::getName();

  if (isset($route['name']) && is_array($route['name'])) {
    $innerRoutes = [];

    foreach ($route['name'] as $key => $item) {
      if (!isset($item) || !is_array($item) || !isset($item['name'])) {
        continue;
      }

      $innerRoutes[$key] = [...$item, 'module' => Module::getName()];
    }

    if (empty($innerRoutes)) {
      return false;
    }

    $route['name'] = $innerRoutes;
  }

  return $route;
}

Hook::setData('sidebar', []);

Hook::register('sidebar.append', function ($route) {
  if (!isset($route['name'])) {
    return false;
  }

  $sidebar = Hook::getData('sidebar') ?? [];
  $sidebar[] = formatRoute($route);
  Hook::setData('sidebar', $sidebar);
});

Hook::register('sidebar.prepend', function ($route) {
  if (!isset($route['name'])) {
    return false;
  }

  $sidebar = Hook::getData('sidebar') ?? [];
  array_unshift($sidebar, formatRoute($route));
  Hook::setData('sidebar', $sidebar);
});

// ############################# NOTIFICATION #############################
// $GLOBALS['notification'] = [];

// Hook::register('notification_add', function($type, $data) {
// 	if(empty($type) || empty($data)) {
// 		return false;
// 	}

// 	$GLOBALS['notification'][$type] = $data;
// });
// Hook::register('notification_modify', function($type, $data) {
// 	if(!isset($GLOBALS['notification'][$type]) || empty($data)) {
// 		return false;
// 	}

// 	$GLOBALS['notification'][$type] = $data;
// });

// ############################# TRANSLATION #############################
// Hook::register('translation_add', function($data) {
// 	Log::write('Translation: ' . $data['file'] . ' added for ' . $data['module'] . ' module by user ID: ' . User::get()->id . ' from IP: ' . Request::ip(), 'translation');
// });
// Hook::register('translation_edit', function($data) {
// 	Log::write('Translation: ' . $data['file'] . ' edited for ' . $data['module'] . ' module by user ID: ' . User::get()->id . ' from IP: ' . Request::ip(), 'translation');
// });

// ############################# GROUP #############################
// Hook::register('group_add', function($data) {
// 	Log::write('Group ID: ' . $data->form_data['item_id'] . ' added by user ID: ' . User::get()->id . ' from IP: ' . Request::ip(), 'group');
// });
// Hook::register('group_edit', function($data) {
// 	Log::write('Group ID: ' . $data->form_data['item_id'] . ' edited by user ID: ' . User::get()->id . ' from IP: ' . Request::ip(), 'group');
// });
// Hook::register('group_delete', function($data) {
// 	Log::write('Group ID: ' . $data->form_data['item_id'] . ' deleted by user ID: ' . User::get()->id . ' from IP: ' . Request::ip(), 'group');
// });

// ############################# USER #############################
// Hook::register('user_add', function($data) {
// 	Log::write('User ID: ' . $data->form_data['item_id'] . ' added by user ID: ' . User::get()->id . ' from IP: ' . Request::ip(), 'user');
// });
// Hook::register('user_edit', function($data) {
// 	Log::write('User ID: ' . $data->form_data['item_id'] . ' edited by user ID: ' . User::get()->id . ' from IP: ' . Request::ip(), 'user');
// });
// Hook::register('user_delete', function($data) {
// 	Log::write('User ID: ' . $data->form_data['item_id'] . ' deleted by user ID: ' . User::get()->id . ' from IP: ' . Request::ip(), 'user');
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

// 	Log::write('Comment ID: ' . $data->form_data['item_id'] . ' added by user ID: ' . User::get()->id . ' from IP: ' . Request::ip(), 'comment');
// });
// Hook::register('comment_edit', function($data) {
// 	Log::write('Comment ID: ' . $data->form_data['item_id'] . ' edited by user ID: ' . User::get()->id . ' from IP: ' . Request::ip(), 'comment');
// });
// Hook::register('comment_delete', function($data) {
// 	Log::write('Comment ID: ' . $data->form_data['item_id'] . ' deleted by user ID: ' . User::get()->id . ' from IP: ' . Request::ip(), 'comment');
// });
// Hook::register('comment_toggle', function($data) {
// 	$type = $data->fields['is_approved'] ? 'approved' : 'disapproved';

// 	Log::write('Comment ID: ' . $data->form_data['item_id'] . ' ' . $type . ' by user ID: ' . User::get()->id . ' from IP: ' . Request::ip(), 'comment');
// });

// ############################# MENU #############################
// Hook::register('menu_add', function($data) {
// 	Log::write('Menu ID: ' . $data->form_data['item_id'] . ' added by user ID: ' . User::get()->id . ' from IP: ' . Request::ip(), 'menu');
// });
// Hook::register('menu_edit', function($data) {
// 	Log::write('Menu ID: ' . $data->form_data['item_id'] . ' edited by user ID: ' . User::get()->id . ' from IP: ' . Request::ip(), 'menu');
// });
// Hook::register('menu_delete', function($data) {
// 	Log::write('Menu ID: ' . $data->form_data['item_id'] . ' deleted by user ID: ' . User::get()->id . ' from IP: ' . Request::ip(), 'menu');
// });
// Hook::register('menu_items_edit', function($data) {
// 	Log::write('Menu ID: ' . $data->form_data['item_id'] . ' changed items by user ID: ' . User::get()->id . ' from IP: ' . Request::ip(), 'menu');
// });

// ############################# PAGE #############################
// Hook::register('page_add', function($data) {
// 	Sitemap::update();

// 	Log::write('Page ID: ' . $data->form_data['item_id'] . ' added by user ID: ' . User::get()->id . ' from IP: ' . Request::ip(), 'page');

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

// 	Log::write('Page ID: ' . $data->form_data['item_id'] . ' edited by user ID: ' . User::get()->id . ' from IP: ' . Request::ip(), 'page');
// });

// Hook::register('page_delete', function($data) {
// 	Sitemap::update();

// 	Log::write('Page ID: ' . $data->form_data['item_id'] . ' deleted by user ID: ' . User::get()->id . ' from IP: ' . Request::ip(), 'page');
// });

// ############################# PROFILE #############################
// Hook::register('user_change_login', function($data) {
// 	Mail::send('ChangeLogin', $data->email, $data);

// 	Notification::create('user_change_login', $data->id, $data);

// 	Log::write('User ID: ' . $data->id . ' changed login from IP: ' . Request::ip(), 'user');
// });
// Hook::register('user_change_password', function($data) {
// 	Mail::send('ChangePassword', $data->email, $data);

// 	Notification::create('user_change_password', $data->id, $data);

// 	Log::write('User ID: ' . $data->id . ' changed password from IP: ' . Request::ip(), 'user');
// });
// Hook::register('user_change_email', function($data) {
// 	Mail::send('ChangeEmail', $data->email, $data);

// 	Notification::create('user_change_email', $data->id, $data);

// 	Log::write('User ID: ' . $data->id . ' changed email from IP: ' . Request::ip(), 'user');
// });
// Hook::register('user_change_name', function($data) {
// 	Notification::create('user_change_name', $data->id, $data);

// 	Log::write('User ID: ' . $data->id . ' changed name from IP: ' . Request::ip(), 'user');
// });
// Hook::register('user_change_contacts', function($data) {
// 	Log::write('User ID: ' . $data->form_data['item_id'] . ' changed contact information from IP: ' . Request::ip(), 'user');
// });

// ############################# RUN #############################
// // NOTIFICATIONS
// Hook::run('notification_add', 'user_register', [
// 		'name' => t('Registration'),
// 		'icon' => 'user-plus',
// 		'color' => 'success',
// 		'user_can_manage' => false
// 	]
// );
// Hook::run('notification_add', 'user_restore', [
// 		'name' => t('Password restore'),
// 		'icon' => 'unlock',
// 		'color' => 'danger',
// 		'user_can_manage' => false
// 	]
// );
// Hook::run('notification_add', 'user_authorize', [
// 		'name' => t('Authorization'),
// 		'icon' => 'log-in',
// 		'color' => 'warning',
// 		'type' => 'web'
// 	]
// );
// Hook::run('notification_add', 'user_change_name', [
// 		'name' => t('Name change'),
// 		'icon' => 'edit',
// 		'color' => 'warning',
// 		'type' => 'web'
// 	]
// );
// Hook::run('notification_add', 'user_change_email', [
// 		'name' => t('Email change'),
// 		'icon' => 'mail',
// 		'color' => 'danger'
// 	]
// );
// Hook::run('notification_add', 'user_change_login', [
// 		'name' => t('Login change'),
// 		'icon' => 'at-sign',
// 		'color' => 'danger'
// 	]
// );
// Hook::run('notification_add', 'user_change_password', [
// 		'name' => t('Password change'),
// 		'icon' => 'lock',
// 		'color' => 'danger'
// 	]
// );
// Hook::run('notification_add', 'page_add', [
// 		'name' => t('Page creation'),
// 		'icon' => 'file-text',
// 		'color' => 'primary',
// 		'type' => 'web'
// 	]
// );
// Hook::run('notification_add', 'page_add_category', [
// 		'name' => t('Category creation'),
// 		'icon' => 'folder',
// 		'color' => 'primary',
// 		'type' => 'web'
// 	]
// );
// Hook::run('notification_add', 'comment_add', [
// 		'name' => t('New comment'),
// 		'icon' => 'message-square',
// 		'color' => 'primary',
// 		'type' => 'web'
// 	]
// );
// Hook::run('notification_add', 'comment_reply', [
// 		'name' => t('New comment reply'),
// 		'icon' => 'corner-down-right',
// 		'color' => 'primary',
// 		'type' => 'web'
// 	]
// );

############################# RUN - SIDEBAR #############################
Hook::run('sidebar.append', [
  'icon' => 'home',
  'text' => t('sidebar.dashboard'),
  'name' => 'dashboard',
  'isPublic' => true
]);

Hook::run('sidebar.append', [
  'icon' => 'user-circle',
  'label' => function () {
    $notifications_count = User::get()->notifications_count;
    return $notifications_count > 0 ? $notifications_count : null;
  },
  'text' => t('sidebar.profile'),
  'name' => 'profile',
  'isPublic' => true
]);

Hook::run('sidebar.append', [
  'text' => t('sidebar.content'),
  'isSeparator' => true,
  'name' => 'page'
]);

Hook::run('sidebar.append', [
  'icon' => 'file-text',
  'text' => t('sidebar.pages'),
  'name' => 'page'
]);

Hook::run('sidebar.append', [
  'icon' => 'message',
  'label' => function () {
    $count = \module\backend\model\Comment::getInstance()->countUnapprovedComments();
    return $count > 0 ? $count : null;
  },
  'text' => t('sidebar.comments'),
  'name' => 'comment'
]);

Hook::run('sidebar.append', [
  'icon' => 'menu-2',
  'text' => t('sidebar.menu'),
  'name' => 'menu'
]);

Hook::run('sidebar.append', [
  'icon' => 'world',
  'text' => t('sidebar.translations'),
  'name' => 'translation'
]);

Hook::run('sidebar.append', [
  'text' => t('sidebar.administration'),
  'isSeparator' => true,
  'name' => 'user'
]);

Hook::run('sidebar.append', [
  'icon' => 'message-circle',
  'label' => function () {
    $count = \module\backend\model\Feedback::getInstance()->countUnreadContacts();
    return $count > 0 ? $count : null;
  },
  'text' => t('sidebar.feedback'),
  'name' => 'feedback'
]);

Hook::run('sidebar.append', [
  'icon' => 'users',
  'text' => t('sidebar.users'),
  'name' => [
    t('sidebar.groups') => [
      'name' => 'group-list'
    ],
    t('sidebar.users') => [
      'name' => 'user-list'
    ]
  ]
]);

Hook::run('sidebar.append', [
  'icon' => 'settings',
  'text' => t('sidebar.settings'),
  'name' => [
    t('sidebar.main') => [
      'name' => 'setting-section',
      'parameter' => ['section' => 'main']
    ],
    t('sidebar.site') => [
      'name' => 'setting-section',
      'parameter' => ['section' => 'site']
    ],
    t('sidebar.contacts') => [
      'name' => 'setting-section',
      'parameter' => ['section' => 'contact']
    ]
  ]
]);
