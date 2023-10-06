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
* admin: select values from db automatically for edit-templates (setFieldValue)
* helper: refactor font-size-
* all: Server::answer -> Request::answer
* engine: modules install
* admin: create data-behavior.js
* admin: create foreign-form.js
* admin: create collapse.js
* admin: uploads interface (scan /upload directory)
* admin: wysiwyg insert from uploads interface
* admin: wysiwyg add image upload in js (but before handle /upload routes for get, post, delete)
* admin: dashboard, profile, pages, messages, groups, settings, logs, modules... interfaces
* admin: handle login & access to routes due to group, registration, reset password
* api + swagger:
  * /api/template/$path - load Template::loadT... for ajax/fetch
  * /api/data/$data - show data from DB
* install: instalation page
* dev: refactor charts initialization, add default double-theme colors etc.
* other jobs based on old (but ready) https://github.com/zakandaiev/adminkit-engine
* docs: create docs module and plug MkDocs or create own builder
  * /docs/dev - documentation for developers
  * /docs/user - documentation for users
