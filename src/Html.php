<?php
namespace Coroq;

class Html implements HtmlInterface
{
  const AUTO_CLOSE = "auto";
  const CLOSE = "close";
  const NO_CLOSE = "no";
  const SELF_CLOSE = "self";

  public $_tag = "";
  public $_children = [];
  public $_attributes = [];
  public $_close = self::AUTO_CLOSE;

  public function __construct()
  {
  }

  public function __toString()
  {
    if ($this->_tag == "") {
      return $this->getEscapedChildren();
    }
    $html = "<" . $this->_tag;
    $attributes = $this->getEscapedAttributes();
    if ($attributes != "") {
      $html .= " " . $attributes;
    }
    $close = $this->calcClose();
    if ($close == self::SELF_CLOSE) {
      $html .= " />";
    }
    else {
      $html .= ">" . $this->getEscapedChildren();
      if ($close == self::CLOSE) {
        $html .= "</$this->_tag>";
      }
    }
    return $html;
  }

  public function getEscapedChildren()
  {
    return array_reduce($this->_children, function($html, $child) {
      return $html . static::escape($child);
    }, "");
  }

  public function getEscapedAttributes()
  {
    $html = [];
    foreach ($this->_attributes as $name => $value) {
      if ($value === false || $value === null) {
        // ignore
      }
      elseif ($value === true) {
        $html[] = $name;
      }
      else {
        $html[] = $name . '="' . static::escape("$value") . '"';
      }
    }
    return join(" ", $html);
  }

  public function children(array $children)
  {
    $this->_children = $children;
    return $this;
  }

  public function append($content)
  {
    $this->_children[] = $content;
    return $this;
  }

  public function prepend($content)
  {
    array_unshift($this->_children, $content);
    return $this;
  }
  
  public function getChildren()
  {
    return $this->_children;
  }

  public function tag($tag)
  {
    $tag = "$tag";
    // TODO: support characters defined in <http://www.w3.org/TR/xml11/#NT-NameStartChar>
    if (preg_match("/[^-:A-Za-z0-9_.]/", $tag)) {
      throw new \InvalidArgumentException();
    }
    $this->_tag = $tag;
    return $this;
  }
  
  public function getTag()
  {
    return $this->_tag;
  }

  public function attr($name, $value)
  {
    $name = "$name";
    // TODO: support characters defined in <http://www.w3.org/TR/xml11/#attdecls>
    if (preg_match("/[^-:A-Za-z0-9_.]/", $name)) {
      throw new \InvalidArgumentException();
    }
    if ($name == "") {
      throw new \InvalidArgumentException();
    }
    $this->_attributes[$name] = $value;
    return $this;
  }

  public function attrs(array $attributes)
  {
    foreach ($attributes as $name => $value) {
      $this->attr($name, $value);
    }
    return $this;
  }
  
  public function getAttr($name)
  {
    return @$this->_attributes[$name];
  }
  
  public function getAttrs()
  {
    return $this->_attributes;
  }

  public function id($id)
  {
    return $this->attr("id", $id);
  }

  public function style($name, $value)
  {
    return $this->attr("style", ltrim(@$this->_attributes["style"] . " $name: $value;"));
  }

  public function styles(array $styles)
  {
    foreach ($styles as $name => $value) {
      $this->style($name, $value);
    }
    return $this;
  }

  public function addClass($class_name)
  {
    return $this->attr("class", ltrim(@$this->_attributes["class"] . " $class_name"));
  }

  public function close($close)
  {
    $this->_close = $close;
    return $this;
  }

  public function calcClose()
  {
    if ($this->_close != self::AUTO_CLOSE) {
      return $this->_close;
    }
    static $empties = [
      "area", "base", "br", "col", "command", "embed",
      "hr", "img", "input", "keygen", "link", "meta",
      "param", "source", "track", "wbr",
    ];
    if (in_array($this->_tag, $empties)) {
      return self::NO_CLOSE;
    }
    return self::CLOSE;
  }
  
  public function call($callable)
  {
    $args = func_get_args();
    array_unshift($args, $this);
    call_user_func_array($callable, $args);
    return $this;
  }

  public static function escape($s)
  {
    if ($s instanceof HtmlInterface) {
      return "$s";
    }
    if (!preg_match("##u", $s)) {
      throw new \InvalidArgumentException();
    }
    return htmlspecialchars("$s", ENT_QUOTES, "UTF-8");
  }
}
