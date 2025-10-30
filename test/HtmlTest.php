<?php
use Coroq\Html\Html;
use PHPUnit\Framework\TestCase;

/**
 * @covers Coroq\Html\Html
 */
class HtmlTest extends TestCase
{
  const specials = "&<>'\"";
  const escaped = "&amp;&lt;&gt;&#039;&quot;";

  public function testEscape()
  {
    foreach (["", null, "&", "<", ">", "あ", "a"] as $s) {
      $this->assertSame(
        htmlspecialchars($s, ENT_QUOTES, "UTF-8"),
        Html::escape($s)
      );
    }
  }
  
  public function test__toStringCanEscapeEmptyString()
  {
    $this->assertSame("", (new Html())->__toString());
  }
  
  public function test__toStringCanEscapeSpecialCharacters()
  {
    $this->assertSame(
      self::escaped,
      (new Html())->append(self::specials)->__toString()
    );
  }
  
  public function test__toStringCanConstructTag()
  {
    $this->assertSame(
      "<p></p>",
      (new Html())->tag("p")->__toString()
    );
  }
  
  public function test__toStringCanConstructSelfCloseTag()
  {
    $this->assertSame(
      "<br />",
      (new Html())->tag("br")->close(Html::SELF_CLOSE)->__toString()
    );
  }

  public function test__toStringCanConstructTagWithAttributes()
  {
    $this->assertSame(
      '<br id="test" clear="both">',
      (new Html())->tag("br")->id("test")->attr("clear", "both")->__toString()
    );
  }

  public function test__toStringCanConstructTagContainsText()
  {
    $this->assertSame(
      "<p>" . self::escaped . "</p>",
      (new Html())->tag("p")->append(self::specials)->__toString()
    );
  }
  
  public function testTagCanSetTag()
  {
    $this->assertSame(
      "test",
      (new Html())->tag("test")->getTag()
    );
  }
  
  public function testTagCanUnSetTag()
  {
    $this->assertSame(
      "",
      (new Html())->tag("test")->tag("")->getTag()
    );
  }
  
  public function testTagThrowsExceptionForInvalidTagName()
  {
    foreach (str_split("!\"#$%&'()=^~\\|@`[{}];+*,<>/?\r\n\t ") as $c) {
      try {
        (new Html())->tag($c);
        $this->fail("Could not ban '$c'");
      }
      catch (\Exception $e) {
        $this->assertTrue($e instanceof \InvalidArgumentException);
      }
    }
  }

  public function testWrapWithNullCreatesEmptyWrapper()
  {
    $inner = (new Html())->tag("span")->append("Text");
    $result = $inner->wrap(null);
    $this->assertSame(
      "<span>Text</span>",
      $result->__toString()
    );
  }

  public function testWrapWithConfiguredWrapper()
  {
    $inner = (new Html())->tag("span")->append("Text");
    $wrapper = (new Html())->tag("div")->addClass("container");
    $result = $inner->wrap($wrapper);
    $this->assertSame(
      '<div class="container"><span>Text</span></div>',
      $result->__toString()
    );
  }

  public function testWrapReturnsWrapper()
  {
    $inner = (new Html())->tag("span")->append("Text");
    $wrapper = (new Html())->tag("div");
    $result = $inner->wrap($wrapper);
    $this->assertSame($wrapper, $result);
  }

  public function testWrapCanChainOnWrapper()
  {
    $inner = (new Html())->tag("span")->append("Text");
    $result = $inner->wrap((new Html())->tag("div"))->addClass("test");
    $this->assertSame(
      '<div class="test"><span>Text</span></div>',
      $result->__toString()
    );
  }

  public function testWrapWithEmptyWrapper()
  {
    $inner = (new Html())->tag("p")->append("Content");
    $result = $inner->wrap((new Html())->tag("section"));
    $this->assertSame(
      '<section><p>Content</p></section>',
      $result->__toString()
    );
  }

  public function testWrapPreservesInnerElementAttributes()
  {
    $inner = (new Html())->tag("a")->attr("href", "#")->append("Link");
    $result = $inner->wrap((new Html())->tag("li"));
    $this->assertSame(
      '<li><a href="#">Link</a></li>',
      $result->__toString()
    );
  }
}
