<?php

namespace Engine;

class Page {
	private static $page = [];

	public function __construct() {
		self::$page =  [
			'title' => Engine::NAME,
			'no_index_no_follow' => false
		];
	}

	public static function get($key = null) {
		return $key ? @self::$page[$key] : self::$page;
	}

	public static function has($key) {
		return isset(self::$page[$key]);
	}

	public static function set($key, $data = null) {
		self::$page[$key] = $data;

		return true;
	}

	public static function meta($key = null) {
		switch($key) {
			case 'no_index_no_follow': {
				$no_index_no_follow = '';

				if(site('no_index_no_follow') || self::get('no_index_no_follow')) {
					$no_index_no_follow = '<meta name="robots" content="noindex, nofollow">';
				}

				return $no_index_no_follow;
			}
			case 'title': {
				return self::get('title') . ' — ' . site('name');
			}
			case 'seo_description': {
				$description = self::get('seo_description') ?? site('name');
				$site_description = site('description');

				if(!empty($site_description)) {
					$description = '. ' . $site_description;
				}

				return $description;
			}
			case 'seo_keywords': {
				return self::get('seo_keywords') ?? trim(preg_replace('/[\s\.;]+/', ',', self::meta('seo_description')) ?? '', ',');
			}
			case 'seo_image': {
				$image = self::get('seo_image') ?? self::get('image') ?? site('logo_public');
				return !empty($image) ? site('url') . '/' . $image : null;
			}
			case 'locale': {
				return lang('locale', null, '_');
			}
			case 'meta_og': {
				return '
					<meta property="og:type" content="website">
					<meta property="og:site_name" content="' . site('name') . '">
					<meta property="og:locale" content="' . self::meta('locale') . '">
					<meta property="og:url" content="' . site('permalink') . '">
					<meta property="og:title" content="' . self::meta('title') . '">
					<meta property="og:description" content="' . self::meta('seo_description') . '">
					<meta property="og:keywords" content="' . self::meta('seo_keywords') . '">
					<meta property="og:image" content="' . self::meta('seo_image') . '">
				';
			}
			case 'meta_twitter': {
				return '
					<meta property="twitter:card" content="summary">
					<meta property="twitter:url" content="' . site('permalink') . '">
					<meta property="twitter:title" content="' . self::meta('title') . '">
					<meta property="twitter:description" content="' . self::meta('seo_description') . '">
					<meta property="twitter:image" content="' . self::meta('seo_image') . '">
				';
			}
			case 'alt_languages': {
				$languages = site('languages');

				$output = '<link rel="alternate" href="' . site('url') . '" hreflang="x-default">';

				foreach($languages as $language) {
					if(site('language_current') !== $language['key']) {
						$output .= '<link rel="alternate" href="' . site('url') . '/' . $language['key'] . '" hreflang="' . $language['key'] . '">';
					}
				}

				return $output;
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
					return $favicon;
				}

				switch(file_extension($icon_path)) {
					case 'ico': {
						$favicon = '<link rel="icon" type="image/x-icon" sizes="any" href="' . site('url') . '/' . $icon_path . '">';
						break;
					}
					case 'png': {
						$favicon = '
							<link rel="icon" type="image/png" href="' . site('url') . '/' . $icon_path . '">
							<link rel="apple-touch-icon" href="' . site('url') . '/' . $icon_path . '">
						';
						break;
					}
					case 'svg': {
						$favicon = '<link rel="icon" type="image/svg+xml" href="' . site('url') . '/' . $icon_path . '">';
						break;
					}
				}

				return $favicon;
			}
			case 'engine_script': {
				return '
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
							color: {}
						};
					</script>
				';
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
			<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">

			<meta name="description" content="' . $seo_description . '">
			<meta name="keywords" content="' . $seo_keywords . '">

			' . $meta_og . '

			' . $meta_twitter . '

			<link rel="canonical" href="' . $permalink . '">

			<link rel="image_src" href="' . $seo_image . '">

			' . $alt_languages . '

			' . $favicon . '

			' . $engine_script . '

			' . $analytics_gtag . '
		';

		return $meta;
	}
}
