<?php
use Coroq\Html\Html;
use Coroq\Html\Tag\Br;
use PHPUnit\Framework\TestCase;

/**
 * @covers Coroq\Html\Tag\Br
 */
class BrTest extends TestCase
{
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
