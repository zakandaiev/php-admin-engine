<?php

namespace engine\util;

class Debug
{
  public static function dd(...$data)
  {
    $output = "\n<!-- DEBUG START -->\n";

    $output .= '<pre style="display:block;width:100%;overflow:auto;margin:10px 0;padding:1em;background:#1b1b1b;color:#fff;font-size:1em;font-family:SFMono-Regular,Consolas,Liberation,Mono,Menlo,monospace;font-weight:400;line-height:1.4;border-radius:0.5em;">';

    $trace = debug_backtrace();
    $traceLevel = 0;
    foreach ($trace as $key => $item) {
      if (@$item['function'] === 'debug') {
        $traceLevel = $key;
        break;
      }
    }
    $fileName = $trace[$traceLevel]['file'];
    $fileLine = $trace[$traceLevel]['line'];

    $output .= "<span style=\"color:#2f4f4f\">$fileName : $fileLine</span>\n\n";

    foreach ($data as $key => $item) {
      ob_start();
      var_dump($item);
      $itemOutputRaw = ob_get_clean();
      $itemOutput = $itemOutputRaw;

      $itemOutput = preg_replace('/int|float|string|bool|array|object/', '<span style="color:#00bfff">$0</span>', $itemOutput);
      $itemOutput = preg_replace('/(\()([\w\d\\\\]*)(\))/', '<span style="color:#00ced1">$1</span><span style="color:#ff7f50">$2</span><span style="color:#00ced1">$3</span>', $itemOutput);
      $itemOutput = preg_replace('/(\[)(.*)(\])/', '<span style="color:#00ced1">$1</span><span style="color:#e06c75">$2</span><span style="color:#00ced1">$3</span>', $itemOutput);
      $itemOutput = preg_replace('/{|}/', '<span style="color:#00ced1">$0</span>', $itemOutput);
      $itemOutput = preg_replace('/(=>)\s+/', '<span style="color:#00ced1">$0</span>', $itemOutput);
      $itemOutput = preg_replace('/\s*=>\s*/', ' => ', $itemOutput);

      $output .= $itemOutput;

      $output .= "<!--\n\n$itemOutputRaw\n-->";

      if (isset($data[$key + 1])) {
        $output .= '<hr style="display:block;margin:10px 0;border:1px dashed #2f4f4f">';
      }
    }

    $output .= '</pre>';

    $output .= "\n<!-- DEBUG END -->\n";

    echo $output;
  }

  public static function trace($level = null)
  {
    self::dd($level ? (isset(debug_backtrace()[$level]) ? debug_backtrace()[$level] : debug_backtrace()) : debug_backtrace());
  }
}
