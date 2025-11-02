<?php
namespace Coroq\Html;

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
  public function __toString(): string
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
  public function getEscapedChildren(): string
  {
    return array_reduce($this->_children, function($html, $child) {
      return $html . static::escape($child);
    }, "");
  }

  /**
   * @return string
   */
  public function getEscapedAttributes(): string
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
   * @return $this
   */
  public function children(array $children): self
  {
    $this->_children = $children;
    return $this;
  }

  /**
   * @param mixed $content
   * @return $this
   */
  public function append($content): self
  {
    $this->_children[] = $content;
    return $this;
  }

  /**
   * @param mixed $content
   * @return $this
   */
  public function prepend($content): self
  {
    array_unshift($this->_children, $content);
    return $this;
  }
  
  /**
   * @return array
   */
  public function getChildren(): array
  {
    return $this->_children;
  }

  /**
   * @param string $tag
   * @return $this
   */
  public function tag($tag): self
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
  public function getTag(): string
  {
    return $this->_tag;
  }

  /**
   * @param string $name
   * @param mixed $value
   * @return $this
   */
  public function attr($name, $value): self
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
   * @return $this
   */
  public function attrs(array $attributes): self
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
    return $this->_attributes[$name] ?? null;
  }
  
  /**
   * @return array
   */
  public function getAttrs(): array
  {
    return $this->_attributes;
  }

  /**
   * @param mixed $id
   * @return $this
   */
  public function id($id): self
  {
    return $this->attr("id", $id);
  }

  /**
   * @param string $name
   * @param mixed $value
   * @return $this
   */
  public function style($name, $value): self
  {
    $existing = $this->_attributes["style"] ?? "";
    return $this->attr("style", ltrim("$existing $name: $value;"));
  }

  /**
   * @param array $styles
   * @return $this
   */
  public function styles(array $styles): self
  {
    foreach ($styles as $name => $value) {
      $this->style($name, $value);
    }
    return $this;
  }

  /**
   * @param string $class_name
   * @return $this
   */
  public function addClass($class_name): self
  {
    $existing = $this->_attributes["class"] ?? "";
    return $this->attr("class", ltrim("$existing $class_name"));
  }

  /**
   * Set data attribute
   *
   * @param string $name Attribute name (without 'data-' prefix)
   * @param mixed $value Attribute value
   * @return $this
   */
  public function data($name, $value): self
  {
    return $this->attr("data-$name", $value);
  }

  /**
   * @param string $value
   * @return $this
   */
  public function autocomplete($value): self
  {
    return $this->attr("autocomplete", $value);
  }

  /**
   * @param string $value
   * @return $this
   */
  public function placeholder($value): self
  {
    return $this->attr("placeholder", $value);
  }

  /**
   * @param string $value
   * @return $this
   */
  public function rows($value): self
  {
    return $this->attr("rows", $value);
  }

  /**
   * @param string $close
   * @return $this
   */
  public function close(string $close): self
  {
    $this->_close = $close;
    return $this;
  }

  /**
   * @return string
   */
  public function calcClose(): string
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
   * @return $this
   */
  public function apply(callable $callable): self
  {
    $args = func_get_args();
    $args[0] = $this;
    call_user_func_array($callable, $args);
    return $this;
  }

  /**
   * Execute callback if condition is true
   *
   * @param bool $condition The condition to check
   * @param callable $callback Function to execute if true, receives $this as first argument
   * @return $this
   */
  public function when(bool $condition, callable $callback): self
  {
    if ($condition) {
      return $this->apply($callback);
    }
    return $this;
  }

  /**
   * Iterate over items and execute callback for each
   *
   * @param iterable $items The items to iterate over
   * @param callable $callback Function to execute for each item, receives ($this, $value, $key)
   * @return $this
   */
  public function each(iterable $items, callable $callback): self
  {
    foreach ($items as $key => $value) {
      $callback($this, $value, $key);
    }
    return $this;
  }

  /**
   * Wraps this element with another Html element
   *
   * @param Html|null $wrapper The Html element to wrap this element with. If null, creates a new empty Html.
   * @return Html The wrapper element (with this element as child)
   */
  public function wrap(?Html $wrapper = null): Html
  {
    if ($wrapper === null) {
      $wrapper = new Html();
    }
    return $wrapper->append($this);
  }

  /**
   * @param mixed $s
   * @return string
   */
  public static function escape($s): string
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
