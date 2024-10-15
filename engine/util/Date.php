<?php

namespace engine\util;

use engine\i18n\I18n;

class Date
{
  public static function format($date = null, $format = 'd.m.Y')
  {
    $timestamp = $date ?? time();

    $timestamp = is_numeric($timestamp) ? $timestamp : strtotime($timestamp);

    return date($format, $timestamp);
  }

  public static function when($date = null, $format = 'd.m.Y')
  {
    $timestamp = $date ?? time();
    $timestamp = is_numeric($date) ? $date : strtotime($date ?? time());

    $dateDay = date('d.m.Y', $timestamp);
    $today = date('d.m.Y');
    $yesterday = date('d.m.Y', mktime(0, 0, 0, date('m'), date('d') - 1, date('Y')));

    if ($dateDay === $today) {
      $date = I18n::translate('date.today_at', date('H:i', $timestamp));
    } else if ($yesterday === $dateDay) {
      $date = I18n::translate('date.yesterday_at', date('H:i', $timestamp));
    } else {
      $date = self::format($timestamp, $format);
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
