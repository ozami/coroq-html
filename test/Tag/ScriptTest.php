<?php
use function Coroq\Html\scriptBridge;
use PHPUnit\Framework\TestCase;

class ScriptTest extends TestCase
{
  public function testBridgeCanEncodeScalarValue()
  {
    foreach (["text", 1, false, null] as $value) {
      $h = scriptBridge("test", $value);
      $encoded = base64_encode(json_encode($value));
      $this->assertSame(
        "<script>var test = JSON.parse(atob('$encoded'));</script>",
        (string)$h
      );
    }
  }
}
