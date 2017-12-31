<?php
namespace Coroq\Html;

class Script extends \Coroq\Html
{
  public function __construct()
  {
    parent::__construct();
    $this->tag("script");
  }
  
  public function src($src)
  {
    return $this->attr("src", $src);
  }
  
  public function bridge($name, $value)
  {
    if (!preg_match("#^[a-zA-Z_][a-zA-Z0-9_]*$#", $name)) {
      throw new \InvalidArgumentException();
    }
    $code = "var $name = " . static::bridgeData($value) . ";";
    return $this->append($code);
  }

  public static function bridgeData($value)
  {
    $json = json_encode($value, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    if ($json === false) {
      throw new \InvalidArgumentException();
    }
    $base64 = base64_encode($json);
    $code = "JSON.parse(atob('$base64'))";
    return $code;
  }
}
