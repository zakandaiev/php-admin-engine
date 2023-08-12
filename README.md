# PHP Admin Engine
* Designed for developers
* Flexible framework based on PHP
* Supports MVC pattern
* Coded in OOP style
* Modular add-ons system
* CMS-ready modules out of box
* Multilingual

## Server environment requirements
* PHP 7.4+
  * extenstion: fileinfo, mbstring, pdo, pdo_mysql, timezonedb
* MySQL 5.6+ or MariaDB 10.3+

## Installation
1. Download/clone this repository to your web server
2. Install dependencies with `composer upgrade` command
3. Open the site and fill out the installation form
4. Create & enjoy ;)

## TODO
* engine: modules install
* admin: form.js - add navigator vibrate
* admin: modals - add espace button
* admin: create collapse.js
* admin: remake popover & tooltip to fixed pos
* admin: tooltip handle title attr, but not data-title
* admin: uploads interface (scan /upload directory)
* admin: wysiwyg insert from uploads interface
* admin: wysiwyg add image upload in js (but before handle /upload routes for get, post, delete)
* admin: dashboard, profile, pages, messages, groups, settings, logs, modules... interfaces
* admin: handle login & access to routes due to group, registration, reset password
* api + swagger:
  * /api/template/$path - load Template::loadT... for ajax/fetch
  * /api/data/$data - show data from DB
* install: instalation page
* other jobs based on old (but ready) https://github.com/zakandaiev/adminkit-engine
