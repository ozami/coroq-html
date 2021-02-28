<?php
namespace Coroq;

class Html implements HtmlInterface
{
  const AUTO_CLOSE = "auto";
  const CLOSE = "close";
  const NO_CLOSE = "no";
  const SELF_CLOSE = "self";

  /** @var string */
  public $_tag = "";
  /** @var array */
  public $_children = [];
  /** @var array */
  public $_attributes = [];
  /** @var string */
  public $_close = self::AUTO_CLOSE;

  public function __construct()
  {
  }

  /**
   * @return string
   */
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

  /**
   * @return string
   */
  public function getEscapedChildren()
  {
    return array_reduce($this->_children, function($html, $child) {
      return $html . static::escape($child);
    }, "");
  }

  /**
   * @return string
   */
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

  /**
   * @param array $children
   * @return Html
   */
  public function children(array $children)
  {
    $this->_children = $children;
    return $this;
  }

  /**
   * @param mixed $content
   * @return Html
   */
  public function append($content)
  {
    $this->_children[] = $content;
    return $this;
  }

  /**
   * @param mixed $content
   * @return Html
   */
  public function prepend($content)
  {
    array_unshift($this->_children, $content);
    return $this;
  }
  
  /**
   * @return array
   */
  public function getChildren()
  {
    return $this->_children;
  }

  /**
   * @param string $tag
   * @return Html
   */
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
  
  /**
   * @return string
   */
  public function getTag()
  {
    return $this->_tag;
  }

  /**
   * @param string $name
   * @param mixed $value
   * @return Html
   */
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

  /**
   * @param array $attributes
   * @return Html
   */
  public function attrs(array $attributes)
  {
    foreach ($attributes as $name => $value) {
      $this->attr($name, $value);
    }
    return $this;
  }
  
  /**
   * @param string $name
   * @return mixed
   */
  public function getAttr($name)
  {
    return @$this->_attributes[$name];
  }
  
  /**
   * @return array
   */
  public function getAttrs()
  {
    return $this->_attributes;
  }

  /**
   * @param mixed $id
   * @return Html
   */
  public function id($id)
  {
    return $this->attr("id", $id);
  }

  /**
   * @param string $name
   * @param mixed $value
   * @return Html
   */
  public function style($name, $value)
  {
    return $this->attr("style", ltrim(@$this->_attributes["style"] . " $name: $value;"));
  }

  /**
   * @param array $styles
   * @return Html
   */
  public function styles(array $styles)
  {
    foreach ($styles as $name => $value) {
      $this->style($name, $value);
    }
    return $this;
  }

  /**
   * @param string $class_name
   * @return Html
   */
  public function addClass($class_name)
  {
    return $this->attr("class", ltrim(@$this->_attributes["class"] . " $class_name"));
  }

  /**
   * @param string $value
   * @return $this
   */
  public function autocomplete($value)
  {
    return $this->attr("autocomplete", $value);
  }

  /**
   * @param string $value
   * @return $this
   */
  public function placeholder($value)
  {
    return $this->attr("placeholder", $value);
  }

  /**
   * @param string $value
   * @return $this
   */
  public function rows($value)
  {
    return $this->attr("rows", $value);
  }

  /**
   * @param string $close
   * @return Html
   */
  public function close($close)
  {
    $this->_close = $close;
    return $this;
  }

  /**
   * @return string
   */
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
  
  /**
   * @param callable $callable
   * @param mixed $args... optional
   * @return Html
   */
  public function call($callable)
  {
    $args = func_get_args();
    array_unshift($args, $this);
    call_user_func_array($callable, $args);
    return $this;
  }

  /**
   * @param mixed $s
   * @return string
   */
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
