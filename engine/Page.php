<?php

namespace Engine;

class Page {
	protected static $page;
	protected static $meta = [];

	public function __construct() {
		self::$page = new \stdClass();

		self::$page->title = Engine::NAME;
		self::$page->no_index_no_follow = false;

		$breadcrumb = new \stdClass();
		$breadcrumb->items = [];
		$breadcrumb->options = [];

		self::$page->breadcrumb = $breadcrumb;

		return $this;
	}

	public static function get($key = null) {
		return isset($key) ? @self::$page->{$key} : self::$page;
	}

	public static function has($key) {
		return isset(self::$page->{$key});
	}

	public static function set($key, $data = null) {
		self::$page->{$key} = $data;

		return true;
	}

	public static function breadcrumb($key = null, $mixed = null) {
		if(!isset($key)) {
			self::get('breadcrumb');
		}

		switch($key) {
			case 'add': {
				$crumb = new \stdClass();

				$crumb->name = @$mixed['name'];
				$crumb->url = @$mixed['url'];

				if(isset($mixed['key'])) {
					self::$page->breadcrumb->items[$mixed['key']] = $crumb;
				}
				else {
					self::$page->breadcrumb->items[] = $crumb;
				}

				break;
			}
			case 'has': {
				return isset(self::$page->breadcrumb->items[$mixed]);
			}
			case 'get': {
				return isset($mixed) ? @self::$page->breadcrumb->items[$mixed] : self::$page->breadcrumb->items;
			}
			case 'edit': {
				if(!self::breadcrumb('has', @$mixed['key'])) {
					return false;
				}

				$crumb = self::breadcrumb('get', @$mixed['key']);

				$crumb->name = @$mixed['name'];
				$crumb->url = @$mixed['url'];
			}
		}

		return true;
	}

	public static function meta($key = null) {
		if(!empty($key) && isset(self::$meta[$key])) {
			return self::$meta[$key];
		}

		switch($key) {
			case 'no_index_no_follow': {
				$no_index_no_follow = '';

				if(site('no_index_no_follow') || self::get('no_index_no_follow')) {
					$no_index_no_follow = '<meta name="robots" content="noindex, nofollow">';
				}

				self::$meta['no_index_no_follow'] = $no_index_no_follow;

				return $no_index_no_follow;
			}
			case 'title': {
				$title = self::get('title') . ' — ' . site('name');

				self::$meta['title'] = $title;

				return $title;
			}
			case 'seo_description': {
				$seo_description = self::get('seo_description') ?? site('name');
				$site_description = site('description');

				if(!empty($site_description)) {
					$seo_description = '. ' . $site_description;
				}

				self::$meta['seo_description'] = $seo_description;

				return $seo_description;
			}
			case 'seo_keywords': {
				$seo_keywords = self::get('seo_keywords') ?? trim(preg_replace('/[\s\.;]+/', ',', self::meta('seo_description')) ?? '', ',');

				self::$meta['seo_keywords'] = $seo_keywords;

				return $seo_keywords;
			}
			case 'seo_image': {
				$image = self::get('seo_image') ?? self::get('image') ?? site('logo_public');

				$seo_image = !empty($image) ? site('url') . '/' . $image : null;

				self::$meta['seo_image'] = $seo_image;

				return $seo_image;
			}
			case 'locale': {
				$locale = lang('locale', null, '_');

				self::$meta['locale'] = $locale;

				return $locale;
			}
			case 'meta_og': {
				$meta_og = '
					<meta property="og:type" content="website">
					<meta property="og:site_name" content="' . site('name') . '">
					<meta property="og:locale" content="' . self::meta('locale') . '">
					<meta property="og:url" content="' . site('permalink') . '">
					<meta property="og:title" content="' . self::meta('title') . '">
					<meta property="og:description" content="' . self::meta('seo_description') . '">
					<meta property="og:keywords" content="' . self::meta('seo_keywords') . '">
				';

				if(!empty(self::meta('seo_image'))) {
					$meta_og .= '
						<meta property="og:image" content="' . self::meta('seo_image') . '">
					';
				}

				self::$meta['meta_og'] = $meta_og;

				return $meta_og;
			}
			case 'meta_twitter': {
				$meta_twitter = '
					<meta property="twitter:card" content="summary">
					<meta property="twitter:url" content="' . site('permalink') . '">
					<meta property="twitter:title" content="' . self::meta('title') . '">
					<meta property="twitter:description" content="' . self::meta('seo_description') . '">
				';

				if(!empty(self::meta('seo_image'))) {
					$meta_twitter .= '
						<meta property="twitter:image" content="' . self::meta('seo_image') . '">
					';
				}

				self::$meta['meta_twitter'] = $meta_twitter;

				return $meta_twitter;
			}
			case 'alt_languages': {
				$languages = site('languages');

				$alt_languages = '<link rel="alternate" href="' . site('url') . '" hreflang="x-default">';

				foreach($languages as $language) {
					if(site('language_current') !== $language['key']) {
						$alt_languages .= '<link rel="alternate" href="' . site('url') . '/' . $language['key'] . '" hreflang="' . $language['key'] . '">';
					}
				}

				self::$meta['alt_languages'] = $alt_languages;

				return $alt_languages;
			}
			case 'favicon': {
				$favicon = '
					<link rel="icon" type="image/x-icon" sizes="any" href="' . Asset::url() . '/favicon.ico">
					<link rel="icon" type="image/png" href="' . Asset::url() . '/favicon.png">
					<link rel="icon" type="image/svg+xml" href="' . Asset::url() . '/favicon.svg">
					<link rel="apple-touch-icon" href="' . Asset::url() . '/favicon.png">
				';

				$icon_path = site('favicon');

				if(empty($icon_path)) {
					self::$meta['favicon'] = $favicon;
					return $favicon;
				}

				$icon_extension = file_extension($icon_path);

				switch($icon_extension) {
					case 'ico': {
						$favicon = '<link rel="icon" type="image/x-icon" sizes="any" href="' . site('url') . '/' . $icon_path . '">';
						break;
					}
					case 'svg': {
						$favicon = '<link rel="icon" type="image/svg+xml" href="' . site('url') . '/' . $icon_path . '">';
						break;
					}
					default: {
						$favicon = '
							<link rel="icon" type="image/' . $icon_extension . '" href="' . site('url') . '/' . $icon_path . '">
							<link rel="apple-touch-icon" href="' . site('url') . '/' . $icon_path . '">
						';
						break;
					}
				}

				self::$meta['favicon'] = $favicon;

				return $favicon;
			}
			case 'engine_script': {
				$engine_script = '
					<script>
						const ENGINE = {
							language: {
								key: "' . site('language_current') . '",
								region: "' . site('language_current_region') . '",
							},
							csrf: {
								key: "' . COOKIE_KEY['csrf'] . '",
								token: "' . Request::$csrf . '"
							},
							pagination_limit: ' . site('pagination_limit') . ',
							translation: {},
							theme: {},
							api: {
								delay_ms: 1000,
								timeout_ms: 15000
							}
						};
					</script>
				';

				self::$meta['engine_script'] = $engine_script;

				return $engine_script;
			}
			case 'analytics_gtag': {
				$gtag_id = site('analytics_gtag');
				$analytics_gtag = '';

				if(!empty(site('analytics_gtag'))) {
					$analytics_gtag = '
						<script async src="https://www.googletagmanager.com/gtag/js?id=' . $gtag_id . '"></script>
						<script>
							window.dataLayer = window.dataLayer || [];
							function gtag(){dataLayer.push(arguments);}
							gtag("js", new Date());
							gtag("config", "' . $gtag_id . '");
						</script>
					';
				}

				self::$meta['analytics_gtag'] = $analytics_gtag;

				return $analytics_gtag;
			}
		}

		$title = self::meta('title');
		$charset = site('charset');
		$author = Engine::AUTHOR;
		$seo_description = self::meta('seo_description');
		$seo_keywords = self::meta('seo_keywords');
		$seo_image = self::meta('seo_image');
		$meta_og = self::meta('meta_og');
		$meta_twitter = self::meta('meta_twitter');
		$permalink = site('permalink');
		$alt_languages = self::meta('alt_languages');
		$favicon = self::meta('favicon');
		$engine_script = self::meta('engine_script');
		$analytics_gtag = self::meta('analytics_gtag');

		$meta = self::meta('no_index_no_follow');
		$meta .= '
			<title>' . $title . '</title>

			<meta charset="' . $charset . '">
			<meta name="author" content="' . $author . '">
			<meta http-equiv="X-UA-Compatible" content="IE=edge">
			<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">

			<meta name="description" content="' . $seo_description . '">
			<meta name="keywords" content="' . $seo_keywords . '">

			' . $meta_og . '

			' . $meta_twitter . '

			<link rel="canonical" href="' . $permalink . '">
		';

		if(!empty($seo_image)) {
			$meta .= '
				<link rel="image_src" href="' . $seo_image . '">
			';
		}

		$meta .= '
			' . $alt_languages . '

			' . $favicon . '

			' . $engine_script . '

			' . $analytics_gtag . '
		';

		return $meta;
	}
}
