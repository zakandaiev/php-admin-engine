<?php

############################# ASSETS #############################
Asset::css('css/main');

Asset::js('js/main');

############################# BREADCRUMBS #############################
Breadcrumb::setOption('render_homepage', true);
Breadcrumb::setOption('homepage_name', __('dummy.breadcrumb.homepage_name'));
Breadcrumb::setOption('homepage_url', '/');
