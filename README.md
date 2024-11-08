# WORK IN PROGRESS, TODO:

* **NEW todos below**:
* engine: rewrite old classes (missing ones)
* backend module: rewrite from scratch
* frontend module: rewrite from scratch
* install module: rewrite from scratch
* dev module: create interface that will generate I18n files for modules
* dev module: create interface that will install/uninstall modules
* all files: replace all tabs with spaces
* all files: replace all em to rem
* all files: replace all .active to .block_active

* **OLD todos below**:
* engine: replace all strtok with explode
* engine: modules install
* engine: cached queries would fail with pagination
* admin: change favicons, logos to branded
* admin: fix data-copy from awesomecs + update gist
* admin: uploads interface (scan /upload directory)
* admin: insert files from uploads interface to wysiwyg, forms etc.
* dev: refactor charts initialization, add default double-theme colors etc.

# PHP Admin Engine
* Designed for developers
* Flexible framework based on PHP
* Supports MVC pattern
* Coded in OOP style
* Modular add-ons system
* CMS-ready modules
* Multilingual support
* Useful utils
* And many-many more...

## Server environment requirements
* PHP 7.4+
  * extenstion: fileinfo, mbstring, pdo, pdo_mysql, tzinfo, timezonedb
* MySQL 5.6+ or MariaDB 10.3+

## Installation
1. Download this repository in .zip and extract to your web server
2. Install dependencies with `composer upgrade` command
3. Open the site and fill out the installation form
4. Use as is or create your own modules & enjoy ;)

## Roadmap
* create an api module:
  * /api/template/$path - load Template::loadT... for ajax/fetch
  * /api/data/$data - show data from DB
* create docs module:
  * /docs/api - api module documentation for developers
  * /docs/dev - engine documentation for developers
  * /docs/user - engine documentation for users
