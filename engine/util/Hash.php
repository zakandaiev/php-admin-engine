<?php

namespace engine\util;

class Hash
{
  public static function token($length = 16)
  {
    return bin2hex(random_bytes($length / 2));
  }

  public static function password($password)
  {
    return password_hash($password, PASSWORD_DEFAULT);
  }
}
