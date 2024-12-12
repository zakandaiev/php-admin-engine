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
The open source framework written in PHP for easy creation of web solutions of any complexity.

## Demo
* [Frontend](https://ae.zakandaiev.com)
* [Backend](https://ae.zakandaiev.com/backend)

## Documentation
* [Homepage](https://zakandaiev.github.io/php-admin-engine/)
* [Get started](https://zakandaiev.github.io/php-admin-engine/guide/)
* [Customization](https://zakandaiev.github.io/php-admin-engine/guide/customization/)

## Features
* Flexible PHP framework
* Designed for developers
* Supports MVC pattern
* Coded in OOP style
* Bunch of useful utils
* Modular add-ons system
* Multilingual/i18n support
* CMS-ready modules out of box

## Server environment requirements
* PHP 7.4+
  * extenstions: fileinfo, mbstring, pdo, pdo_mysql, tzinfo, timezonedb
* MySQL 5.6+ or MariaDB 10.3+

## Installation
1. Download [latest release](https://github.com/zakandaiev/php-admin-engine/releases) and extract to your web server
2. Install dependencies with `composer upgrade` command
3. Open the site and fill out the installation form
4. Use as is or customize with your own modules

## Roadmap
* create an api module:
  * /api/template/$path - data fetching with Template::loadTe...
  * /api/data/$data - get raw data from DB
* create docs module:
  * /docs/api - api module documentation for developers
  * /docs/dev - engine documentation for developers
  * /docs/user - engine documentation for users
