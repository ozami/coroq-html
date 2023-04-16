<?php
use Coroq\Html\Br;
use Coroq\Html;
use PHPUnit\Framework\TestCase;

class BrTest extends TestCase
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
      nl2br($text, false),
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
      nl2br($text . $text, false),
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
      nl2br("$text<p>$text</p>$text", false),
      (string)Br::nl2br($h)
    );
  }
}
