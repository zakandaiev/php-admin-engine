<?php

namespace engine\util;

use DateTime;
use DateTimeZone;
use engine\Config;
use engine\http\Cookie;
use engine\i18n\I18n;

class Date
{
  protected static $userTimezone;

  public static function getUserTimezone()
  {
    if (empty(self::$userTimezone) && Cookie::has(Config::getProperty('userTimezoneKey', 'cookie'))) {
      self::$userTimezone = new DateTimeZone(timezone_name_from_abbr('', abs(intval(Cookie::get(Config::getProperty('userTimezoneKey', 'cookie')))) * 60, 0));
    } else if (empty(self::$userTimezone)) {
      self::$userTimezone = new DateTimeZone(date_default_timezone_get());
    }

    return self::$userTimezone;
  }

  public static function format($date = null, $format = null, $considerTimezone = null)
  {
    $timestamp = $date ?? time();
    $timestamp = is_numeric($timestamp) ? $timestamp : strtotime($timestamp);

    $dateTime = new DateTime('@' . $timestamp);
    if ($considerTimezone) {
      $dateTime->setTimezone(self::getUserTimezone());
    }

    return $dateTime->format($format ?? 'd.m.Y');
  }

  public static function when($date = null, $format = null, $considerTimezone = null)
  {
    $timestamp = $date ?? time();
    $timestamp = is_numeric($date) ? $date : strtotime($date ?? time());

    $dateTime = new DateTime('@' . $timestamp);
    if ($considerTimezone) {
      $dateTime->setTimezone(self::getUserTimezone());
    }

    $today = (new DateTime('today', self::getUserTimezone()))->format('d.m.Y');
    $yesterday = (new DateTime('yesterday', self::getUserTimezone()))->format('d.m.Y');

    $dateDay = $dateTime->format('d.m.Y');
    if ($dateDay === $today) {
      $date = I18n::translate('date.today_at', $dateTime->format('H:i'));
    } else if ($dateDay === $yesterday) {
      $date = I18n::translate('date.yesterday_at', $dateTime->format('H:i'));
    } else {
      $date = self::format($timestamp, $format ?? 'd.m.Y', $considerTimezone);
    }

    return $date;
  }

  public static function left($date)
  {
    $now = time();
    $then = is_numeric($date) ? $date : strtotime($date ?? time());

    if ($then - $now < 0) {
      return I18n::translate('date.left.expired');
    }

    $difference = abs($then - $now);
    $left = [];

    $month = floor($difference / 2592000);
    if (0 < $month) {
      $left['month'] = I18n::translate('date.left.month', $month);
    }

    $days = floor($difference / 86400) % 30;
    if (0 < $days) {
      $left['days'] = I18n::translate('date.left.days', $days);
    }

    $hours = floor($difference / 3600) % 24;
    if (0 < $hours) {
      $left['hours'] = I18n::translate('date.left.hours', $hours);
    }

    $minutes = floor($difference / 60) % 60;
    if (0 < $minutes) {
      $left['minutes'] = I18n::translate('date.left.minutes', $minutes);
    }

    if (0 < count($left)) {
      $datediff = implode(' ', $left);

      return $datediff;
    }

    return I18n::translate('date.left.few_seconds');
  }
}
