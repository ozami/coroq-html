<?php
use Coroq\Html;

class HtmlTest extends PHPUnit_Framework_TestCase
{
  const specials = "&<>'\"";
  const escaped = "&amp;&lt;&gt;&#039;&quot;";

  /**
   * @covers Coroq\Html::escape
   */
  public function testEscape()
  {
    foreach (["", null, "&", "<", ">", "'", '"', "あ", "a"] as $s) {
      $this->assertSame(
        htmlspecialchars($s, ENT_QUOTES),
        Html::escape($s)
      );
    }
  }
  
  /**
   * @covers Coroq\Html::__toString
   */
  public function test__toStringCanEscapeEmptyString()
  {
    $this->assertSame("", (new Html())->__toString());
  }
  
  /**
   * @covers Coroq\Html::__toString
   */
  public function test__toStringCanEscapeSpecialCharacters()
  {
    $this->assertSame(
      self::escaped,
      (new Html())->append(self::specials)->__toString()
    );
  }
  
  /**
   * @covers Coroq\Html::__toString
   */
  public function test__toStringCanConstructTag()
  {
    $this->assertSame(
      "<p></p>",
      (new Html())->tag("p")->__toString()
    );
  }
  
  /**
   * @covers Coroq\Html::__toString
   */
  public function test__toStringCanConstructSelfCloseTag()
  {
    $this->assertSame(
      "<br />",
      (new Html())->tag("br")->__toString()
    );
  }

  /**
   * @covers Coroq\Html::__toString
   */
  public function test__toStringCanConstructTagWithAttributes()
  {
    $this->assertSame(
      '<br id="test" clear="both" />',
      (new Html())->tag("br")->id("test")->attr("clear", "both")->__toString()
    );
  }

  /**
   * @covers Coroq\Html::__toString
   */
  public function test__toStringCanConstructTagContainsText()
  {
    $this->assertSame(
      "<p>" . self::escaped . "</p>",
      (new Html())->tag("p")->append(self::specials)->__toString()
    );
  }
  
  /**
   * @covers Coroq\Html::tag
   */
  public function testTagCanSetTag()
  {
    $this->assertSame(
      "test",
      (new Html())->tag("test")->getTag()
    );
  }
  
  /**
   * @covers Coroq\Html::tag
   */
  public function testTagCanUnSetTag()
  {
    $this->assertSame(
      "",
      (new Html())->tag("test")->tag("")->getTag()
    );
  }
  
  /**
   * @covers Coroq\Html::tag
   */
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
}
