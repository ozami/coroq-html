<?php
use Coroq\Html\Script;
use Coroq\Html;

class ScriptTest extends PHPUnit_Framework_TestCase
{
  /**
   * @covers Coroq\Html\Script::bridge
   */
  public function testBridgeCanEncodeScalarValue()
  {
    foreach (["text", 1, false, null] as $value) {
      $h = (new Script())->bridge("test", $value);
      $encoded = base64_encode(json_encode($value));
      $this->assertSame(
        "<script>var test = JSON.parse(atob('$encoded'));</script>",
        (string)$h
      );
    }
  }
}
