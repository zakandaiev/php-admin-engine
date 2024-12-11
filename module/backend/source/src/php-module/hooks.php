<?php

use engine\module\Hook;
use engine\module\Module;
use engine\util\Log;

############################# EXTEND PAGE META #############################
Hook::setData('page.meta', []);

Hook::register('page.meta.add', function ($data) {
  if (!is_string($data) || empty($data)) {
    return false;
  }

  $meta = Hook::getData('page.meta') ?? [];
  $meta[] = $data;

  Hook::setData('page.meta', $meta);
});

############################# EXTEND SETTING MODEL #############################
Hook::setData('setting.column', []);

Hook::register('setting.column.add', function ($columnName, $columnModel) {
  if (empty($columnName) || !is_array($columnModel) || empty($columnModel)) {
    return false;
  }

  $meta = Hook::getData('setting.column') ?? [];
  $meta[$columnName] = formatSettingColumn($columnModel);

  Hook::setData('setting.column', $meta);
});

function formatSettingColumn($columnModel = [])
{
  $columnModel['module'] = $columnModel['module'] ?? Module::getName();

  return $columnModel;
}

############################# EXTEND SIDEBAR #############################
Hook::setData('sidebar', []);

Hook::register('sidebar.append', function ($route) {
  if (!isset($route['name'])) {
    return false;
  }

  $sidebar = Hook::getData('sidebar') ?? [];
  $sidebar[] = formatSidebarRoute($route);
  Hook::setData('sidebar', $sidebar);
});

Hook::register('sidebar.prepend', function ($route) {
  if (!isset($route['name'])) {
    return false;
  }

  $sidebar = Hook::getData('sidebar') ?? [];
  array_unshift($sidebar, formatSidebarRoute($route));
  Hook::setData('sidebar', $sidebar);
});

Hook::register('sidebar.append.after', function ($id, $routeToAppend) {
  $sidebar = Hook::getData('sidebar') ?? [];

  $sidebarFormatted = [];
  foreach ($sidebar as $route) {
    if (is_string($route['name']) && @$route['id'] === $id) {
      $sidebarFormatted[] = $route;
      $sidebarFormatted[] = formatSidebarRoute($routeToAppend);
    } else if (is_array($route['name'])) {
      foreach ($route['name'] as $routeInner) {
        if (@$routeInner['id'] === $id) {
          $route['name'][] = formatSidebarRoute($routeToAppend);
        }
      }
      $sidebarFormatted[] = $route;
    } else {
      $sidebarFormatted[] = $route;
    }
  }

  Hook::setData('sidebar', $sidebarFormatted);
});

function formatSidebarRoute($route = [])
{
  $route['module'] = $route['module'] ?? Module::getName();

  if (isset($route['name']) && is_array($route['name'])) {
    $innerRoutes = [];

    foreach ($route['name'] as $innerRoute) {
      if (!isset($innerRoute) || !is_array($innerRoute) || !isset($innerRoute['name'])) {
        continue;
      }

      $innerRoute['module'] = $innerRoute['module'] ?? Module::getName();

      $innerRoutes[] = $innerRoute;
    }

    $route['name'] = $innerRoutes;
  }

  return $route;
}

// TODO - form execute post -> group model use post & call hook
// ############################# GROUP #############################
Hook::register('group.add', function ($data) {
  Log::write('add ' . $data->id, 'group');
});
Hook::register('group.edit', function ($data) {
  Log::write('edit ' . $data->id, 'group');
});
Hook::register('group.delete', function ($data) {
  Log::write('delete ' . $data->id, 'group');
});

// ############################# USER #############################
Hook::register('user.add', function ($data) {
  Log::write('add ' . $data->id, 'user');
});
Hook::register('user.edit', function ($data) {
  Log::write('edit ' . $data->id, 'user');
});
Hook::register('user.delete', function ($data) {
  Log::write('delete ' . $data->id, 'user');
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

############################# SET SIDEBAR #############################
Hook::run('sidebar.append', [
  'id' => 'backend.dashboard',
  'icon' => 'home',
  'text' => t('dashboard.sidebar'),
  'name' => 'dashboard',
  'isPublic' => true
]);

Hook::run('sidebar.append', [
  'id' => 'backend.profile',
  'icon' => 'user-circle',
  // 'label' => function () {
  //   return User::get()->notifications_count;
  // },
  'text' => t('profile.sidebar'),
  'name' => 'profile',
  'isPublic' => true
]);

Hook::run('sidebar.append', [
  'id' => 'backend.page-separator',
  'text' => t('content.sidebar'),
  'isSeparator' => true,
  'name' => 'page'
]);

Hook::run('sidebar.append', [
  'id' => 'backend.page',
  'icon' => 'file-text',
  'text' => t('page.sidebar'),
  'name' => 'page'
]);

Hook::run('sidebar.append', [
  'id' => 'backend.comment',
  'icon' => 'message',
  // 'label' => function () {
  //   return \module\backend\model\Comment::getInstance()->countUnapprovedComments();
  // },
  'text' => t('comment.sidebar'),
  'name' => 'comment'
]);

Hook::run('sidebar.append', [
  'id' => 'backend.menu',
  'icon' => 'menu-2',
  'text' => t('menu.sidebar'),
  'name' => 'menu'
]);

Hook::run('sidebar.append', [
  'id' => 'backend.translation',
  'icon' => 'world',
  'text' => t('translation.sidebar'),
  'name' => 'translation.list',
  'activeRoutes' => ['translation.edit']
]);

Hook::run('sidebar.append', [
  'id' => 'backend.user-separator',
  'text' => t('administration.sidebar'),
  'isSeparator' => true,
  'name' => 'user'
]);

Hook::run('sidebar.append', [
  'id' => 'backend.feedback',
  'icon' => 'message-circle',
  'label' => function () {
    return \module\backend\model\Feedback::getInstance()->countUnreadFeedback();
  },
  'text' => t('feedback.sidebar'),
  'name' => 'feedback.list',
  'activeRoutes' => ['feedback.reply']
]);

Hook::run('sidebar.append', [
  'id' => 'backend.group',
  'icon' => 'users',
  'text' => t('user.sidebar'),
  'name' => [
    [
      'id' => 'backend.group.list',
      'text' => t('group.sidebar'),
      'name' => 'group.list',
      'activeRoutes' => ['group.add', 'group.edit', 'group.translation.edit']
    ],
    [
      'id' => 'backend.user.list',
      'text' => t('user.sidebar'),
      'name' => 'user.list',
      'activeRoutes' => ['user.add', 'user.edit']
    ]
  ]
]);

Hook::run('sidebar.append', [
  'id' => 'backend.setting',
  'icon' => 'settings',
  'text' => t('setting.sidebar.setting'),
  'name' => [
    [
      'id' => 'backend.setting.engine',
      'text' => t('setting.sidebar.engine'),
      'name' => 'setting.section',
      'parameter' => ['section' => 'engine']
    ],
    [
      'id' => 'backend.setting.backend',
      'text' => t('setting.sidebar.backend'),
      'name' => 'setting.section',
      'parameter' => ['section' => 'backend']
    ],
    [
      'id' => 'backend.setting.frontend',
      'text' => t('setting.sidebar.frontend'),
      'name' => 'setting.section',
      'parameter' => ['section' => 'frontend']
    ]
  ]
]);
