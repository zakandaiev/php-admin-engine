<?php

namespace engine\util;

class Debug
{
  public static function dd(...$data)
  {
    foreach ($data as $key => $item) {
      if ($key === 0) {
        echo '<hr>';
      }

      echo '<pre style="
        display: block;
        width: 100%;
        overflow: auto;
        margin: 0;
        padding: 1em;
        background: #1b1b1b;
        color: #fff;
        font-size: 1em;
        font-family: SFMono-Regular, Consolas, Liberation Mono, Menlo, monospace;
        font-weight: 400;
        line-height: 1.4;
        border-radius: 0.5em;
      ">';

      var_dump($item);

      echo '</pre>';

      if (isset($data[$key + 1])) {
        echo '<br>';
      } else {
        echo '<hr>';
      }
    }
  }

  public static function trace($level = null)
  {
    self::dd($level ? (isset(debug_backtrace()[$level]) ? debug_backtrace()[$level] : debug_backtrace()) : debug_backtrace());
  }
}
