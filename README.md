# WORK IN PROGRESS, TODO:

* **NEW todos below**:
* engine: rewrite old classes (missing ones)
* backend module: rewrite from scratch
* frontend module: rewrite from scratch
* install module: rewrite from scratch
* dev module: refactor all uis
* dev module: create interface that will generate I18n files for modules
* dev module: create interface that will install/uninstall modules
* demo module that extends backend module: rewrite from scratch
* all: replace all tabs with spaces
* all: replace all em to rem
* all: replace all .active to .block_active

* **OLD todos below**:
* engine: replace all strtok with explode
* engine: modules install
* engine: remake Form from static to non-static concept (static should be only token creation methods)
* engine: cached queries would fail with pagination
* admin: create form-behavior.js
* admin: create foreign-form.js
* admin: create collapse.js
* admin: wysiwyg add image upload in js (but before handle /admin/upload routes for get, post, delete)
* admin: dashboard
* admin: profile
* admin: pages interface
* admin: comments interface
* admin: menu interface
* admin: translations interface
* admin: messages interface
* admin: logs interface
* admin: handle login & access to routes due to group, registration, reset password
* admin: change favicons, logos to branded
* admin: fix data-copy from awesomecs + update gist
* admin: quill add margin-bottom to every
* admin: automatically set values to form input's in edit pages (setFieldValue)
* admin: uploads interface (scan /upload directory)
* admin: insert files from uploads interface to wysiwyg, forms etc.
* dev: refactor charts initialization, add default double-theme colors etc.

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

## Roadmap
* create an api module:
  * swagger
  * /api/template/$path - load Template::loadT... for ajax/fetch
  * /api/data/$data - show data from DB
* docs: create docs module and plug MkDocs or create own builder
  * /docs/dev - documentation for developers
  * /docs/user - documentation for users
