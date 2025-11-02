<?php
declare(strict_types=1);

namespace Coroq\Html\Tag;

use Coroq\Html\Html;

class Br extends Html
{
  public function __construct()
  {
    parent::__construct();
    $this->tag("br");
  }

  public static function nl2br(Html $html): Html
  {
    $children = array_reduce($html->getChildren(), function($children, $child) {
      if ($child instanceof Html) {
        $children[] = static::nl2br($child);
      }
      else {
        $parts = preg_split("#(\r\n|\r|\n)#", "$child");
        $children[] = array_shift($parts);
        foreach ($parts as $p) {
          $children[] = new Br();
          $children[] = "\n$p";
        }
      }
      return $children;
    }, []);
    return $html->children($children);
  }
}
