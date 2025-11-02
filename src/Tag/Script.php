<?php
declare(strict_types=1);

namespace Coroq\Html\Tag;

use Coroq\Html\Html;

class Script extends Html
{
  public function __construct()
  {
    parent::__construct();
    $this->tag("script");
  }
  
  public function src($src): self
  {
    return $this->attr("src", $src);
  }

  public function bridge(string $name, $value): self
  {
    if (!preg_match("#^[a-zA-Z_][a-zA-Z0-9_]*$#", $name)) {
      throw new \InvalidArgumentException();
    }
    $code = "var $name = " . static::bridgeCode($value) . ";";
    return $this->append(new NoEscape($code));
  }

  public static function bridgeCode($value): string
  {
    $json = json_encode($value);
    if ($json === false) {
      throw new \InvalidArgumentException();
    }
    $base64 = base64_encode($json);
    if ($base64 === false) {
      throw new \RuntimeException();
    }
    $code = "JSON.parse(atob('$base64'))";
    return $code;
  }
}
