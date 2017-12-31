<?php
namespace Coroq\Html;

class Br extends \Coroq\Html
{
  public function __construct()
  {
    parent::__construct();
    $this->tag("br");
  }

  public static function nl2br(\Coroq\Html $html)
  {
    $children = array_reduce($html->getChildren(), function($children, $child) {
      if ($child instanceof \Coroq\Html) {
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
