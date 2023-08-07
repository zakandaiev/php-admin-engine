# PHP Admin Engine
* Designed for developers
* PHP based flexible framework with CMS-ready modules
* Supports MVC pattern
* Coded in OOP style
* Modular add-ons system
* Multilingual

## Web environment requirements
* PHP 7.4+
* MySQL 5.6+ or MariaDB 10.3+

## Installation
1. Download/clone this repository to your web server
2. Install dependencies with `composer upgrade` command
3. Open the site and fill out the installation form
4. Create & enjoy ;)

## TODO
* engine: Breadcrumbs to Page::breadcrumbs()
* engine: new Classifier or function to get_classifier()
* engine: modules install
* admin: collapse
* admin: remake popover & tooltip to fixed pos
* admin: uploads interface (scan /upload directory)
* admin: wysiwyg insert from uploads interface
* admin: classifiers interface
* admin: interface builder
* api + swagger:
  * /api/template/$path - load Template::loadT... for ajax/fetch
  * /api/data/$data - show data from DB