<?php

namespace engine\util;

class Hash
{
  public static function token($length = null)
  {
    return bin2hex(random_bytes(($length ?? 16) / 2));
  }

  public static function password($password)
  {
    return password_hash($password, PASSWORD_DEFAULT);
  }

  public static function passwordVerify($password, $hash)
  {
    return password_verify($password, $hash);
  }
}
