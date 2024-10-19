<?php

############################# ASSETS #############################
Asset::css('main');

Asset::js('main', ['type' => 'module']);

############################# DEMO DATA #############################
function getHeaderMenu()
{
  return [
    [
      'name' => 'Home',
      'link' => routeLink('home')
    ],
    [
      'name' => 'Route guide',
      'link' => routeLink('guide', null, ['query-param' => 'dummy'])
    ],
    [
      'name' => 'Route closure test',
      'link' => routeLink('user-payment', ['uid' => 111, 'pid' => 222])
    ]
  ];
}

function getFeatures()
{
  return [
    [
      'icon' => '⚙️',
      'title' => 'Gulp environment',
      'text' => 'Modern & Automated development environment with it\'s all benefits'
    ],
    [
      'icon' => '🏗️',
      'title' => 'PHP Admin Engine',
      'text' => 'Flexible & fast PHP based MVC engine built for developers'
    ],
    [
      'icon' => '📁',
      'title' => 'Structured',
      'text' => 'Well thought-out and convenient project structure'
    ],
    [
      'icon' => '🛠️',
      'title' => 'Rich features',
      'text' => 'Ready-to-use utils, styled components, helpers etc.'
    ],
    [
      'icon' => '⚡',
      'title' => 'Optimized content',
      'text' => 'Convenient, modern and SEO friendly templates'
    ],
    [
      'icon' => '🤖',
      'title' => 'Аutomation features',
      'text' => 'Hot reload, SASS preprocessor, assets auto minifier etc.'
    ]
  ];
}
