<?php

use Coroq\Html\Html;
use PHPUnit\Framework\TestCase;

use function Coroq\Html\h;
use function Coroq\Html\p;

/**
 * @covers Coroq\Html\h
 * @covers Coroq\Html\p
 */
class ShorthandsTest extends TestCase
{
  public function testHReturnsHtmlAsPassed()
  {
    $html = new Html();
    $this->assertSame($html, h($html));
  }

  public function testHWrapsString()
  {
    $html = h("test");
    $this->assertInstanceOf(Html::class, $html);
    $this->assertEquals("", $html->getTag());
    $this->assertEquals("test", $html->getEscapedChildren());
  }

  public function testPEscapesHtmlSpecialCharacters()
  {
    $this->expectOutputString("&amp;&lt;&gt;&#039;&quot;");
    p("&<>'\"");
  }
}
