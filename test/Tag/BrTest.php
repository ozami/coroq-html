<?php
use Coroq\Html\Html;
use function Coroq\Html\nl2br as htmlNl2br;
use PHPUnit\Framework\TestCase;

class BrTest extends TestCase
{
  public function testNl2BrCanProcessSingleChild()
  {
    $text = "\ntest\ntest\n\ntest\n";
    $h = new Html();
    $h->append($text);
    $this->assertSame(
      nl2br($text, false),
      (string)htmlNl2br($h)
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
      (string)htmlNl2br($h)
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
      (string)htmlNl2br($h)
    );
  }
}
