<?php

namespace engine\theme;

use engine\Config;
use engine\Engine;
use engine\http\Request;
use engine\util\File;

class Page
{
  protected static $page;
  protected static $meta = [];

  public function __construct()
  {
    self::$page = new \stdClass();

    self::$page->title = Engine::NAME;
    self::$page->no_index_no_follow = false;
    self::$page->breadcrumb = [];

    return $this;
  }

  public static function has($key)
  {
    return isset(self::$page->{$key});
  }

  public static function set($key, $data = null)
  {
    self::$page->{$key} = $data;

    return true;
  }

  public static function get($key = null)
  {
    return isset($key) ? @self::$page->{$key} : self::$page;
  }

  public static function breadcrumb(...$args)
  {
    $key = @$args[0];

    if (!isset($key)) {
      return self::get('breadcrumb');
    }

    switch ($key) {
      case 'set': {
          $crumb = new \stdClass();

          $crumb->name = @$args[1];
          $crumb->url = @$args[2];

          if (self::breadcrumb('has', @$args[3])) {
            self::$page->breadcrumb[$args[3]] = $crumb;
          } else {
            self::$page->breadcrumb[] = $crumb;
          }

          break;
        }
      case 'has': {
          return isset(self::$page->breadcrumb[@$args[1]]);
        }
      case 'get': {
          return isset($args[1]) ? @self::$page->breadcrumb[$args[1]] : self::$page->breadcrumb;
        }
    }

    return true;
  }

  public static function meta($key = null)
  {
    if (!empty($key) && isset(self::$meta[$key])) {
      return self::$meta[$key];
    }

    switch ($key) {
      case 'no_index_no_follow': {
          $noIndexNoFollow = '';

          if (site('no_index_no_follow') || self::get('no_index_no_follow')) {
            $noIndexNoFollow = '<meta name="robots" content="noindex, nofollow">';
          }

          self::$meta['no_index_no_follow'] = $noIndexNoFollow;

          return $noIndexNoFollow;
        }
      case 'title': {
          $title = self::get('title') . ' — ' . site('name');

          self::$meta['title'] = $title;

          return $title;
        }
      case 'seo_description': {
          $seoDescription = self::get('seo_description') ?? site('name');
          $siteDescription = site('description');

          if (!empty($siteDescription)) {
            $seoDescription = '. ' . $siteDescription;
          }

          self::$meta['seo_description'] = $seoDescription;

          return $seoDescription;
        }
      case 'seo_keywords': {
          $seoKeywords = self::get('seo_keywords') ?? trim(preg_replace('/[\s\.;]+/', ',', self::meta('seo_description')) ?? '', ',');

          self::$meta['seo_keywords'] = $seoKeywords;

          return $seoKeywords;
        }
      case 'seo_image': {
          $image = self::get('seo_image') ?? self::get('image') ?? site('logo');

          $seoImage = !empty($image) ? site('url') . '/' . $image : null;

          self::$meta['seo_image'] = $seoImage;

          return $seoImage;
        }
      case 'locale': {
          $locale = lang('locale', null, '_');

          self::$meta['locale'] = $locale;

          return $locale;
        }
      case 'meta_og': {
          $metaOg = '<meta property="og:type" content="website">';
          $metaOg .= '<meta property="og:site_name" content="' . site('name') . '">';
          $metaOg .= '<meta property="og:locale" content="' . self::meta('locale') . '">';
          $metaOg .= '<meta property="og:url" content="' . site('permalink') . '">';
          $metaOg .= '<meta property="og:title" content="' . self::meta('title') . '">';
          $metaOg .= '<meta property="og:description" content="' . self::meta('seo_description') . '">';
          $metaOg .= '<meta property="og:keywords" content="' . self::meta('seo_keywords') . '">';

          if (!empty(self::meta('seo_image'))) {
            $metaOg .= '<meta property="og:image" content="' . self::meta('seo_image') . '">';
          }

          self::$meta['meta_og'] = $metaOg;

          return $metaOg;
        }
      case 'meta_twitter': {
          $metaTwitter = '<meta property="twitter:card" content="summary">';
          $metaTwitter .= '<meta property="twitter:url" content="' . site('permalink') . '">';
          $metaTwitter .= '<meta property="twitter:title" content="' . self::meta('title') . '">';
          $metaTwitter .= '<meta property="twitter:description" content="' . self::meta('seo_description') . '">';

          if (!empty(self::meta('seo_image'))) {
            $metaTwitter .= '<meta property="twitter:image" content="' . self::meta('seo_image') . '">';
          }

          self::$meta['meta_twitter'] = $metaTwitter;

          return $metaTwitter;
        }
      case 'alt_languages': {
          $languages = site('languages');
          $currentLanguage = site('language_current');

          $altLanguages = '<link rel="alternate" href="' . site('url') . '" hreflang="x-default">';

          foreach ($languages as $language) {
            if ($currentLanguage !== $language['key']) {
              $altLanguages .= '<link rel="alternate" href="' . site('url') . '/' . $language['key'] . '" hreflang="' . $language['key'] . '">';
            }
          }

          self::$meta['alt_languages'] = $altLanguages;

          return $altLanguages;
        }
      case 'favicon': {
          $favicon = '<link rel="icon" type="image/x-icon" sizes="any" href="' . Asset::url() . '/favicon.ico">';
          $favicon .= '<link rel="icon" type="image/png" href="' . Asset::url() . '/favicon.png">';
          $favicon .= '<link rel="icon" type="image/svg+xml" href="' . Asset::url() . '/favicon.svg">';
          $favicon .= '<link rel="apple-touch-icon" href="' . Asset::url() . '/favicon.png">';

          $iconPath = site('favicon');

          if (empty($iconPath)) {
            self::$meta['favicon'] = $favicon;
            return $favicon;
          }

          $iconExtension = File::getExtension($iconPath);

          switch ($iconExtension) {
            case 'ico': {
                $favicon = '<link rel="icon" type="image/x-icon" sizes="any" href="' . site('url') . '/' . $iconPath . '">';
                break;
              }
            case 'svg': {
                $favicon = '<link rel="icon" type="image/svg+xml" href="' . site('url') . '/' . $iconPath . '">';
                break;
              }
            default: {
                $favicon = '<link rel="icon" type="image/' . $iconExtension . '" href="' . site('url') . '/' . $iconPath . '">';
                $favicon .= '<link rel="apple-touch-icon" href="' . site('url') . '/' . $iconPath . '">';
                break;
              }
          }

          self::$meta['favicon'] = $favicon;

          return $favicon;
        }
      case 'engine_script': {
          $engineScript = '
<script>
  const Engine = {
    backend: {
      delayMs: ' . Config::getProperty('backendDelayMs', 'engine') . ',
      timeoutMs: ' . Config::getProperty('backendTimeoutMs', 'engine') . '
    },
    csrf: {
      key: "' . Config::getProperty('csrfKey', 'cookie') . '",
      token: "' . Request::csrfToken() . '"
    },
    language: {
      key: "' . lang() . '",
      region: "' . lang('region') . '",
      locale: "' . lang('locale') . '",
    },
    i18n: {},
    site: {
      charset: "' . site('charset') . '",
      language: "' . site('language') . '",
      languageCurrent: "' . site('language_current') . '",
      name: "' . site('name') . '",
      permalink: "' . site('permalink') . '",
      uri: "' . site('uri') . '",
      url: "' . site('url') . '",
      urlLanguage: "' . site('url_language') . '",
      version: "' . site('version') . '",
    },
    theme: {}
  };
</script>';

          self::$meta['engine_script'] = $engineScript;

          return $engineScript;
        }
      case 'analytics_gtag': {
          $gtagId = site('analytics_gtag');
          $analyticsGtag = '';

          if (!empty(site('analytics_gtag'))) {
            $analyticsGtag = '
<script async src="https://www.googletagmanager.com/gtag/js?id=' . $gtagId . '"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag("js", new Date());
  gtag("config", "' . $gtagId . '");
</script>';
          }

          self::$meta['analytics_gtag'] = $analyticsGtag;

          return $analyticsGtag;
        }
    }

    $title = self::meta('title');
    $charset = site('charset');
    $author = Engine::AUTHOR;
    $seoDescription = self::meta('seo_description');
    $seoKeywords = self::meta('seo_keywords');
    $seoImage = self::meta('seo_image');
    $metaOg = self::meta('meta_og');
    $metaTwitter = self::meta('meta_twitter');
    $permalink = site('permalink');
    $altLanguages = self::meta('alt_languages');
    $favicon = self::meta('favicon');
    $engineScript = self::meta('engine_script');
    $analyticsGtag = self::meta('analytics_gtag');

    $meta = self::meta('no_index_no_follow');
    $meta .= '<title>' . $title . '</title>';
    $meta .= '<meta charset="' . $charset . '">';
    $meta .= '<meta name="author" content="' . $author . '">';
    $meta .= '<meta http-equiv="X-UA-Compatible" content="IE=edge">';
    $meta .= '<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">';
    $meta .= '<meta name="description" content="' . $seoDescription . '">';
    $meta .= '<meta name="keywords" content="' . $seoKeywords . '">';
    $meta .= $metaOg;
    $meta .= $metaTwitter;
    $meta .= '<link rel="canonical" href="' . $permalink . '">';
    $meta .= !empty($seoImage) ? '<link rel="image_src" href="' . $seoImage . '">' : '';
    $meta .= $altLanguages;
    $meta .= $favicon;
    $meta .= $engineScript;
    $meta .= $analyticsGtag;

    if (Config::getProperty('isEnabled', 'debug')) {
      $meta = str_replace("><", ">\n<", $meta);
    }

    return $meta;
  }
}
