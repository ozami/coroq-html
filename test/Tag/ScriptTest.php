<?php
use Coroq\Html\Tag\Script;
use PHPUnit\Framework\TestCase;

/**
 * @covers Coroq\Html\Tag\Script
 */
class ScriptTest extends TestCase
{
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
