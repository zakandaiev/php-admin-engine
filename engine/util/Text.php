<?php

namespace engine\util;

class Text
{
  public static function html($text = null)
  {
    return htmlspecialchars($text ?? '');
  }

  public static function url($url = null)
  {
    return urlencode($url ?? '');
  }

  public static function tel($tel = null)
  {
    return preg_replace('/[^\d+]+/m', '', $tel ?? '');
  }

  public static function cyrToLat($text = null)
  {
    $text = $text ?? '';

    $replacement = ['а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'e', 'ж' => 'zh', 'з' => 'z', 'и' => 'i', 'й' => 'y', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'kh', 'ц' => 'tz', 'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sch', 'ы' => 'y', 'э' => 'e', 'ю' => 'iu', 'я' => 'ia', 'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D', 'Е' => 'E', 'Ё' => 'E', 'Ж' => 'Zh', 'З' => 'Z', 'И' => 'I', 'Й' => 'Y', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O', 'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F', 'Х' => 'Kh', 'Ц' => 'Tz', 'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sch', 'Ы' => 'Y', 'Э' => 'E', 'Ю' => 'Iu', 'Я' => 'Ia', 'ь' => '', 'Ь' => '', 'ъ' => '', 'Ъ' => '', 'ї' => 'yi', 'і' => 'i', 'ґ' => 'g', 'є' => 'e', 'Ї' => 'Yi', 'І' => 'I', 'Ґ' => 'G', 'Є' => 'E'];

    return strtr($text, $replacement);
  }

  public static function slug($text = null, $delimiter = null)
  {
    $text = $text ?? '';
    $delimiter = $delimiter ?? '-';

    $slug = self::cyrToLat($text);
    $slug = preg_replace('/[^A-Za-z0-9' . $delimiter . ' ]+/', '', $slug);
    $slug = trim($slug);
    $slug = preg_replace('/\s+/', $delimiter, $slug);
    $slug = strtolower($slug ?? '');

    return $slug;
  }

  public static function word($text = null)
  {
    $text = $text ?? '';

    $word = preg_replace('/[^\p{L}\d ]+/iu', '', $text);
    $word = preg_replace('/\s+/', ' ', $word);
    $word = trim($word);

    return $word;
  }

  public static function excerpt($text = null, $maxchar = null, $end = null)
  {
    $text = $text ?? '';
    $maxchar = $maxchar ?? 100;
    $end = $end ?? '...';

    if (strlen($text) > $maxchar) {
      $words = preg_split('/\s/', $text);
      $output = '';
      $i = 0;

      while (1) {
        $length = strlen($output) + strlen($words[$i]);
        if ($length > $maxchar) {
          break;
        } else {
          $output .= ' ' . $words[$i];
          ++$i;
        }
      }

      $output .= $end;
    } else {
      $output = $text;
    }

    return $output;
  }

  public static function pluralValue($number, $values = null)
  {
    // 'one' - 1 комментарий
    // 'few' - 4 комментария
    // 'many' - 5 комментариев

    $number = intval($number);
    $values = $values ?? [];

    $isValues = count($values) === 3;

    $ratioHundreds = ($number % 100) / 10;
    if ($number > 10 && ($ratioHundreds >= 1 && $ratioHundreds <= 2)) {
      return $isValues ? $values[2] : 'many';
    }

    $ratioDecimal = $number % 10;
    if ($ratioDecimal === 1) {
      return $isValues ? $values[0] : 'one';
    } else if ($ratioDecimal === 2 || $ratioDecimal === 3 || $ratioDecimal === 4) {
      return $isValues ? $values[1] : 'few';
    }

    return $isValues ? $values[2] : 'many';
  }
}
