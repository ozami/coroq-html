<?php
use Coroq\Html\Br;
use Coroq\Html;

class BrTest extends PHPUnit_Framework_TestCase
{
  /**
   * @covers Coroq\Html\Br::nl2br
   */
  public function testNl2BrCanProcessSingleChild()
  {
    $text = "\ntest\ntest\n\ntest\n";
    $h = new Html();
    $h->append($text);
    $this->assertSame(
      nl2br($text),
      (string)Br::nl2br($h)
    );
  }
  
  /**
   * @covers Coroq\Html\Br::nl2br
   */
  public function testNl2BrCanProcessMultipleChild()
  {
    $text = "\ntest\ntest\n\ntest\n";
    $h = new Html();
    $h->append($text);
    $h->append($text);
    $this->assertSame(
      nl2br($text . $text),
      (string)Br::nl2br($h)
    );
  }
  
  /**
   * @covers Coroq\Html\Br::nl2br
   */
  public function testNl2BrCanProcessRecursively()
  {
    $text = "\ntest\ntest\n\ntest\n";
    $h = new Html();
    $h->append($text);
    $h->append((new Html())->tag("p")->append($text));
    $h->append($text);
    $this->assertSame(
      nl2br("$text<p>$text</p>$text"),
      (string)Br::nl2br($h)
    );
  }
}
